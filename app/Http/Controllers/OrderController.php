<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function index()
    {
        $orders = auth()->user()->orders()
                    ->with('items.product')
                    ->orderBy('created_at', 'desc')
                    ->get();
        
        return view('orders.index', compact('orders'));
    }

    public function store(Request $request)
    {
        $cartItems = auth()->user()->carts()->with('product')->get();

        if ($cartItems->isEmpty()) {
            return redirect()->route('cart.index')->with('error', 'Keranjang kosong!');
        }

        // Validasi request
        $request->validate([
            'payment_method' => 'required|in:cash,transfer,credit_card',
            'shipping_address' => 'required|string|min:10',
        ]);

        $total = $cartItems->sum(function ($item) {
            return $item->product->price * $item->quantity;
        });

        // Buat order
        $order = Order::create([
            'user_id' => auth()->id(),
            'order_number' => Order::generateOrderNumber(),
            'total_price' => $total,
            'payment_method' => $request->payment_method,
            'payment_status' => 'pending',
            'status' => 'pending',
            'shipping_address' => $request->shipping_address
        ]);

        // Simpan order items
        foreach ($cartItems as $item) {
            OrderItem::create([
                'order_id' => $order->id,
                'product_id' => $item->product_id,
                'quantity' => $item->quantity,
                'price' => $item->product->price
            ]);

            // Kurangi stok
            $item->product->decrement('stock', $item->quantity);
        }

        // Hapus cart
        auth()->user()->carts()->delete();

        return redirect()->route('orders.index')
                ->with('success', 'Pesanan berhasil dibuat! Order #' . $order->order_number);
    }

    // Fitur konfirmasi pembayaran
    public function confirmPayment(Order $order)
    {
        // Pastikan order milik user yang login
        if ($order->user_id !== auth()->id()) {
            abort(403);
        }

        // Update status pembayaran
        $order->update([
            'payment_status' => 'paid',
            'status' => 'processing'
        ]);

        return redirect()->route('orders.index')
                ->with('success', 'Pembayaran berhasil dikonfirmasi!');
    }

    // Fitur detail order
    public function show(Order $order)
    {
        if ($order->user_id !== auth()->id()) {
            abort(403);
        }

        $order->load('items.product');
        return view('orders.show', compact('order'));
    }
}