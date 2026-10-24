<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\User;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    /**
     * Pembelian langsung dari tombol "Beli Sekarang" di halaman produk
     */
    public function directBuy(Request $request, Product $product)
    {
        $validated = $request->validate([
            'quantity' => 'required|integer|min:1|max:' . max(1, $product->stock),
            'customer_name' => 'required|string|max:100',
            'customer_phone' => 'required|string|max:25',
            'shipping_address' => 'required|string|min:5',
            'payment_method' => 'required|in:cod,transfer,qris',
            'notes' => 'nullable|string|max:500',
        ]);

        if ($product->stock < $request->quantity) {
            return back()->with('error', 'Maaf, stok produk tidak mencukupi (tersisa: ' . $product->stock . ' unit).');
        }

        // Tentukan user pembeli
        $user = auth()->user();
        if (!$user) {
            $user = User::firstOrCreate(
                ['email' => 'customer@techcatalog.com'],
                [
                    'name' => $request->customer_name,
                    'password' => bcrypt('customer123'),
                ]
            );
        }

        // Susun data pengiriman lengkap
        $shippingDetails = "Nama: " . trim($request->customer_name) . "\n"
                         . "No. HP/WA: " . trim($request->customer_phone) . "\n"
                         . "Alamat: " . trim($request->shipping_address);
        if (!empty($request->notes)) {
            $shippingDetails .= "\nCatatan: " . trim($request->notes);
        }

        $totalPrice = $product->price * $request->quantity;

        // Buat Order baru
        $order = Order::create([
            'user_id' => $user->id,
            'order_number' => Order::generateOrderNumber(),
            'total_price' => $totalPrice,
            'payment_method' => $request->payment_method,
            'payment_status' => 'pending',
            'status' => 'pending',
            'shipping_address' => $shippingDetails,
        ]);

        // Simpan Order Item
        OrderItem::create([
            'order_id' => $order->id,
            'product_id' => $product->id,
            'quantity' => $request->quantity,
            'price' => $product->price,
        ]);

        // Kurangi stok produk
        $product->decrement('stock', $request->quantity);

        return redirect()->route('orders.receipt', $order->order_number)
                         ->with('success', 'Pesanan Anda berhasil dibuat! Nomor Invoice: ' . $order->order_number);
    }

    /**
     * Tampilkan struk / nota pembelian resmi
     */
    public function receipt($order_number)
    {
        $order = Order::with('items.product')->where('order_number', $order_number)->firstOrFail();
        return view('orders.receipt', compact('order'));
    }

    /**
     * Tampilkan daftar pesanan untuk Admin
     */
    public function adminIndex(Request $request)
    {
        $status = $request->query('status');
        $orders = Order::with(['items.product', 'user'])
            ->when($status, fn($q) => $q->where('status', $status))
            ->latest()
            ->paginate(15)
            ->withQueryString();

        $stats = [
            'total' => Order::count(),
            'pending' => Order::where('status', 'pending')->count(),
            'processing' => Order::where('status', 'processing')->count(),
            'completed' => Order::where('status', 'completed')->count(),
            'revenue' => Order::where('payment_status', 'paid')->sum('total_price'),
        ];

        return view('admin.orders.index', compact('orders', 'stats', 'status'));
    }

    /**
     * Update status pesanan / pembayaran oleh Admin
     */
    public function adminUpdateStatus(Request $request, Order $order)
    {
        $request->validate([
            'status' => 'nullable|in:pending,processing,completed,cancelled',
            'payment_status' => 'nullable|in:pending,paid',
        ]);

        if ($request->has('status')) {
            $order->status = $request->status;
        }

        if ($request->has('payment_status')) {
            $order->payment_status = $request->payment_status;
        }

        $order->save();

        return back()->with('success', 'Status pesanan #' . $order->order_number . ' berhasil diperbarui.');
    }

    /**
     * Daftar pesanan milik user yang sedang login (Riwayat Pesanan Saya)
     */
    public function index(Request $request)
    {
        if (!auth()->check()) {
            return redirect()->route('login');
        }

        // Ambil filter status dari query string
        $status = $request->query('status');

        $orders = auth()->user()->orders()
                    ->with('items.product')
                    ->when($status, fn($q) => $q->where('status', $status))
                    ->orderBy('created_at', 'desc')
                    ->paginate(10)
                    ->withQueryString();

        return view('orders.index', compact('orders', 'status'));
    }

    /**
     * Checkout dari cart / keranjang
     */
    public function store(Request $request)
    {
        if (!auth()->check()) {
            return redirect()->route('home');
        }

        $cartItems = auth()->user()->carts()->with('product')->get();

        if ($cartItems->isEmpty()) {
            return redirect()->route('cart.index')->with('error', 'Keranjang kosong!');
        }

        $request->validate([
            'payment_method' => 'required|in:cash,transfer,credit_card,cod,qris',
            'shipping_address' => 'required|string|min:10',
        ]);

        $total = $cartItems->sum(function ($item) {
            return $item->product->price * $item->quantity;
        });

        $order = Order::create([
            'user_id' => auth()->id(),
            'order_number' => Order::generateOrderNumber(),
            'total_price' => $total,
            'payment_method' => $request->payment_method,
            'payment_status' => 'pending',
            'status' => 'pending',
            'shipping_address' => $request->shipping_address,
        ]);

        foreach ($cartItems as $item) {
            OrderItem::create([
                'order_id' => $order->id,
                'product_id' => $item->product_id,
                'quantity' => $item->quantity,
                'price' => $item->product->price,
            ]);

            $item->product->decrement('stock', $item->quantity);
        }

        auth()->user()->carts()->delete();

        return redirect()->route('orders.receipt', $order->order_number)
                ->with('success', 'Pesanan berhasil dibuat! Order #' . $order->order_number);
    }

    /**
     * Detail Pesanan
     */
    public function show(Order $order)
    {
        $order->load('items.product');
        return view('orders.show', compact('order'));
    }

    /**
     * Konfirmasi pembayaran (non-COD)
     */
    public function confirmPayment(Order $order)
    {
        $order->update([
            'payment_status' => 'paid',
            'status' => 'processing',
        ]);

        return back()->with('success', 'Pembayaran berhasil dikonfirmasi!');
    }

    /**
     * Konfirmasi pesanan COD
     */
    public function confirmCOD(Order $order)
    {
        $order->update([
            'payment_status' => 'paid',
            'status' => 'completed',
        ]);

        return back()->with('success', 'Pesanan COD berhasil dikonfirmasi!');
    }

    /**
     * Batalkan pesanan (hanya untuk pemilik pesanan & status pending)
     */
    public function cancelOrder(Order $order)
    {
        // Pastikan hanya pemilik pesanan yang bisa membatalkan
        if ($order->user_id !== auth()->id()) {
            abort(403, 'Anda tidak berhak membatalkan pesanan ini.');
        }

        // Hanya pesanan dengan status 'pending' yang bisa dibatalkan
        if ($order->status !== 'pending') {
            return back()->with('error', 'Pesanan tidak bisa dibatalkan karena status sudah ' . $order->status . '.');
        }

        // Kembalikan stok produk
        foreach ($order->items as $item) {
            if ($item->product) {
                $item->product->increment('stock', $item->quantity);
            }
        }

        // Update status pesanan
        $order->update(['status' => 'cancelled']);

        return back()->with('success', 'Pesanan #' . $order->order_number . ' berhasil dibatalkan.');
    }
}