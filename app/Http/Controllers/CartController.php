<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\User;
use Illuminate\Http\Request;

class CartController extends Controller
{
    /**
     * Dapatkan user yang sedang aktif (baik user login maupun sesi guest)
     */
    protected function getActiveUser()
    {
        if (auth()->check()) {
            return auth()->user();
        }

        $guestUserId = session('guest_user_id');
        $user = $guestUserId ? User::find($guestUserId) : null;

        if (!$user) {
            $user = User::firstOrCreate(
                ['email' => 'customer@techcatalog.com'],
                ['name' => 'Pelanggan Toko', 'password' => bcrypt('guest123')]
            );
            session(['guest_user_id' => $user->id]);
        }

        return $user;
    }

    /**
     * Menampilkan isi keranjang belanja
     */
    public function index()
    {
        $user = $this->getActiveUser();
        $cartItems = Cart::where('user_id', $user->id)->with('product')->get();
        $subtotal = $cartItems->sum(fn($item) => ($item->product->price ?? 0) * $item->quantity);

        return view('cart.index', compact('cartItems', 'subtotal'));
    }

    /**
     * Menambahkan produk ke keranjang belanja
     */
    public function add(Request $request, Product $product)
    {
        $user = $this->getActiveUser();
        $qty = max(1, (int) $request->input('quantity', 1));

        if ($product->stock <= 0) {
            return back()->with('error', 'Maaf, stok produk habis!');
        }

        $cart = Cart::where('user_id', $user->id)
                    ->where('product_id', $product->id)
                    ->first();

        if ($cart) {
            $newQty = min($product->stock, $cart->quantity + $qty);
            $cart->update(['quantity' => $newQty]);
        } else {
            Cart::create([
                'user_id' => $user->id,
                'product_id' => $product->id,
                'quantity' => min($product->stock, $qty),
            ]);
        }

        $cartCount = Cart::where('user_id', $user->id)->sum('quantity');

        if ($request->wantsJson()) {
            return response()->json([
                'success' => true,
                'cartCount' => $cartCount,
                'message' => 'Produk berhasil ditambahkan ke keranjang!',
            ]);
        }

        return redirect()->route('cart.index')->with('success', 'Produk "' . $product->name . '" berhasil ditambahkan ke keranjang!');
    }

    /**
     * Update kuantitas produk di keranjang
     */
    public function update(Request $request, Cart $cart)
    {
        $request->validate([
            'quantity' => 'required|integer|min:1|max:' . ($cart->product->stock ?? 999),
        ]);

        $cart->update([
            'quantity' => $request->quantity,
        ]);

        return redirect()->route('cart.index')->with('success', 'Jumlah produk berhasil diperbarui!');
    }

    /**
     * Hapus produk dari keranjang
     */
    public function remove(Cart $cart)
    {
        $cart->delete();
        return redirect()->route('cart.index')->with('success', 'Produk berhasil dihapus dari keranjang.');
    }

    /**
     * Halaman Checkout Keranjang
     */
    public function checkoutPage()
    {
        $user = $this->getActiveUser();
        $cartItems = Cart::where('user_id', $user->id)->with('product')->get();

        if ($cartItems->isEmpty()) {
            return redirect()->route('cart.index')->with('error', 'Keranjang Anda masih kosong. Silakan pilih produk terlebih dahulu.');
        }

        $subtotal = $cartItems->sum(fn($item) => ($item->product->price ?? 0) * $item->quantity);

        return view('cart.checkout', compact('cartItems', 'subtotal', 'user'));
    }

    /**
     * Proses Checkout Keranjang (Konfirmasi Pesanan)
     */
    public function processCheckout(Request $request)
    {
        $user = $this->getActiveUser();
        $cartItems = Cart::where('user_id', $user->id)->with('product')->get();

        if ($cartItems->isEmpty()) {
            return redirect()->route('cart.index')->with('error', 'Keranjang Anda kosong!');
        }

        $request->validate([
            'customer_name' => 'required|string|max:100',
            'customer_phone' => 'required|string|max:25',
            'shipping_address' => 'required|string|min:5',
            'payment_method' => 'required|in:cod,transfer,qris',
            'notes' => 'nullable|string|max:500',
        ]);

        // Hitung total
        $totalPrice = $cartItems->sum(fn($item) => ($item->product->price ?? 0) * $item->quantity);

        // Susun alamat pengiriman lengkap
        $shippingDetails = "Nama: " . trim($request->customer_name) . "\n"
                         . "No. HP/WA: " . trim($request->customer_phone) . "\n"
                         . "Alamat: " . trim($request->shipping_address);
        if (!empty($request->notes)) {
            $shippingDetails .= "\nCatatan: " . trim($request->notes);
        }

        // Buat Order
        $order = Order::create([
            'user_id' => $user->id,
            'order_number' => Order::generateOrderNumber(),
            'total_price' => $totalPrice,
            'payment_method' => $request->payment_method,
            'payment_status' => 'pending',
            'status' => 'pending',
            'shipping_address' => $shippingDetails,
        ]);

        // Simpan Order Items & Kurangi Stok
        foreach ($cartItems as $item) {
            OrderItem::create([
                'order_id' => $order->id,
                'product_id' => $item->product_id,
                'quantity' => $item->quantity,
                'price' => $item->product->price ?? 0,
            ]);

            // Kurangi stok jika produk masih ada
            if ($item->product) {
                $item->product->decrement('stock', $item->quantity);
            }
        }

        // Kosongkan keranjang belanja
        Cart::where('user_id', $user->id)->delete();

        // Arahkan ke status pesanan / struk resmi!
        return redirect()->route('orders.receipt', $order->order_number)
                         ->with('success', 'Pesanan Anda berhasil dikonfirmasi! Nomor Invoice: ' . $order->order_number);
    }
}