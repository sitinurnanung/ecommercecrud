<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\Product;
use Illuminate\Http\Request;

class CartController extends Controller
{
    /**
     * Menampilkan isi keranjang
     */
    public function index()
    {
        $cartItems = auth()->user()->carts()->with('product')->get();
        return view('cart.index', compact('cartItems'));
    }

    /**
     * Menambahkan produk ke keranjang
     */
    public function add(Request $request, Product $product)
    {
        // Cek apakah produk sudah ada di keranjang
        $cart = Cart::where('user_id', auth()->id())
                    ->where('product_id', $product->id)
                    ->first();

        if ($cart) {
            // Jika sudah ada, tambah quantity
            $cart->quantity += 1;
            $cart->save();
        } else {
            // Jika belum ada, buat baru
            Cart::create([
                'user_id' => auth()->id(),
                'product_id' => $product->id,
                'quantity' => 1
            ]);
        }

        // Hitung total item di keranjang
        $cartCount = auth()->user()->carts()->count();

        return response()->json([
            'success' => true,
            'cartCount' => $cartCount,
            'message' => 'Produk berhasil ditambahkan ke keranjang!'
        ]);
    }

    /**
     * Update jumlah produk di keranjang
     */
    public function update(Request $request, Cart $cart)
    {
        $request->validate([
            'quantity' => 'required|integer|min:1|max:' . $cart->product->stock
        ]);

        $cart->update([
            'quantity' => $request->quantity
        ]);

        return redirect()->route('cart.index')->with('success', 'Keranjang berhasil diupdate!');
    }

    /**
     * Hapus produk dari keranjang
     */
    public function remove(Cart $cart)
    {
        $cart->delete();
        return redirect()->route('cart.index')->with('success', 'Item berhasil dihapus dari keranjang!');
    }
}