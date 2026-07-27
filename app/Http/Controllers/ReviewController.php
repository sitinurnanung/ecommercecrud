<?php

namespace App\Http\Controllers;

use App\Models\Review;
use App\Models\Product;
use App\Models\Order;
use Illuminate\Http\Request;

class ReviewController extends Controller
{
    public function store(Request $request, Product $product)
    {
        $request->validate([
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'nullable|string|max:500'
        ]);

        // Cek apakah user sudah pernah review produk ini
        $existingReview = Review::where('user_id', auth()->id())
                                ->where('product_id', $product->id)
                                ->first();

        if ($existingReview) {
            return redirect()->back()->with('error', 'Anda sudah memberikan review untuk produk ini!');
        }

        // Cek apakah user sudah membeli produk ini
        $hasPurchased = Order::where('user_id', auth()->id())
                            ->whereHas('items', function($query) use ($product) {
                                $query->where('product_id', $product->id);
                            })
                            ->exists();

        if (!$hasPurchased) {
            return redirect()->back()->with('error', 'Anda harus membeli produk ini terlebih dahulu untuk memberikan review!');
        }

        Review::create([
            'user_id' => auth()->id(),
            'product_id' => $product->id,
            'order_id' => Order::where('user_id', auth()->id())->latest()->first()->id,
            'rating' => $request->rating,
            'comment' => $request->comment
        ]);

        return redirect()->back()->with('success', 'Review berhasil ditambahkan!');
    }
}