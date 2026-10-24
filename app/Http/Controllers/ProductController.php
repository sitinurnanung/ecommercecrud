<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index()
    {
        $products = Product::latest()->paginate(8);
        return view('products.index', compact('products'));
    }

   public function edit(Product $product)
{
    // Laravel otomatis mencari berdasarkan slug karena method getRouteKeyName()
    return view('admin.products.edit', compact('product'));
}
}