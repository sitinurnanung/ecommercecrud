<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Review;
use App\Models\Product;
use App\Models\User;
use App\Models\Order;

class ReviewSeeder extends Seeder
{
    public function run()
    {
        $user = User::first();
        
        if (!$user) {
            $user = User::create([
                'name' => 'Demo User',
                'email' => 'demo@demo.com',
                'password' => bcrypt('password')
            ]);
        }
        $order = Order::first();
        if (!$order) {
            $order = Order::create([
                'user_id' => $user->id,
                'order_number' => 'INV-DEMO-0001',
                'total_price' => 100000,
                'payment_method' => 'cash',
                'payment_status' => 'paid',
                'status' => 'completed',
                'shipping_address' => 'Jl. Bogor No. 17, Jakarta, Indonesia',
            ]);
        }

        $products = Product::all();
        $comments = [
            'Produk sangat bagus! Kualitas premium.',
            'Sesuai dengan deskripsi, recommended!',
            'Pengiriman cepat, produk original.',
            'Mantap! Puas sekali dengan produk ini.',
            'Okelah, sesuai harga.',
            'Bagus banget, worth it!',
            'Lumayan, tapi masih bisa lebih baik.',
            'Sangat memuaskan, akan beli lagi.',
            'Produk berkualitas, packing aman.',
            'Kurang sesuai ekspektasi, tapi masih ok.',
            'Sangat recommended untuk teman-teman!',
            'Barang sesuai foto, kualitas oke.',
            'Pengiriman tepat waktu, terima kasih.',
            'Produk original, garansi resmi.',
            'Murah tapi kualitas bagus!',
        ];

        $ratings = [3, 4, 5, 4, 5, 3, 4, 5, 4, 3, 5, 4, 5, 3, 4];

        foreach ($products as $index => $product) {
            $reviewCount = rand(4, 7);
            
            for ($i = 0; $i < $reviewCount; $i++) {
                Review::create([
                    'user_id' => $user->id,
                    'product_id' => $product->id,
                    'order_id' => $order->id,
                    'rating' => $ratings[array_rand($ratings)],
                    'comment' => $comments[array_rand($comments)]
                ]);
            }
        }
    }
}