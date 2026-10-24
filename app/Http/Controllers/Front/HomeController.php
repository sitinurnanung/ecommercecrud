<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    /**
     * Tampilkan halaman utama katalog produk (Storefront) dengan filter, search, & kategori
     */
    public function index(Request $request)
    {
        $search = $request->query('search');
        $categorySlug = $request->query('category');
        $sort = $request->query('sort');

        // Pastikan kategori dasar tersedia
        $categories = Category::all();
        if ($categories->isEmpty()) {
            $defaultCategories = [
                ['name' => 'Laptop & Komputer', 'slug' => 'laptop-komputer', 'icon' => '💻'],
                ['name' => 'Audio & Headphone', 'slug' => 'audio-headphone', 'icon' => '🎧'],
                ['name' => 'Aksesoris & Hardware', 'slug' => 'aksesoris-hardware', 'icon' => '🎮'],
                ['name' => 'Kamera & Optik', 'slug' => 'kamera-optik', 'icon' => '📷'],
            ];
            foreach ($defaultCategories as $cat) {
                Category::firstOrCreate(['slug' => $cat['slug']], $cat);
            }
            $categories = Category::all();
        }

        // Tautkan produk yang belum memiliki category_id secara otomatis berdasarkan nama produk
        Product::whereNull('category_id')->get()->each(function ($p) use ($categories) {
            $name = strtolower($p->name);
            if (str_contains($name, 'laptop') || str_contains($name, 'printer') || str_contains($name, 'stand')) {
                $cat = $categories->firstWhere('slug', 'laptop-komputer');
            } elseif (str_contains($name, 'audio') || str_contains($name, 'headphone') || str_contains($name, 'earphone') || str_contains($name, 'headset')) {
                $cat = $categories->firstWhere('slug', 'audio-headphone');
            } elseif (str_contains($name, 'kamera')) {
                $cat = $categories->firstWhere('slug', 'kamera-optik');
            } else {
                $cat = $categories->firstWhere('slug', 'aksesoris-hardware') ?? $categories->first();
            }
            if ($cat) {
                $p->update(['category_id' => $cat->id]);
            }
        });

        // Query Produk dengan Relasi Kategori
        $query = Product::with('category');

        // Filter Pencarian
        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', '%' . $search . '%')
                  ->orWhere('description', 'like', '%' . $search . '%');
            });
        }

        // Filter Kategori
        if ($categorySlug) {
            $query->whereHas('category', function ($q) use ($categorySlug) {
                $q->where('slug', $categorySlug);
            });
        }

        // Urutan Sortir
        if ($sort === 'price_asc') {
            $query->orderBy('price', 'asc');
        } elseif ($sort === 'price_desc') {
            $query->orderBy('price', 'desc');
        } elseif ($sort === 'name_asc') {
            $query->orderBy('name', 'asc');
        } else {
            $query->latest();
        }

        $products = $query->paginate(12)->withQueryString();

        return view('front.index', compact('products', 'categories', 'search', 'categorySlug', 'sort'));
    }

    /**
     * Tampilkan detail info 1 produk berdasarkan slug
     */
    public function show(Product $product)
    {
        $product->load('category');
        return view('front.show', compact('product'));
    }
}
