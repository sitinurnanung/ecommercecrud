<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Category;

class CategorySeeder extends Seeder
{
    public function run()
    {
        $categories = [
            ['name' => 'Laptop & Komputer', 'slug' => 'laptop-komputer', 'icon' => '💻', 'description' => 'Laptop, PC, dan aksesoris komputer'],
            ['name' => 'Smartphone & Tablet', 'slug' => 'smartphone-tablet', 'icon' => '📱', 'description' => 'HP, tablet, dan aksesoris'],
            ['name' => 'Audio & Headphone', 'slug' => 'audio-headphone', 'icon' => '🎧', 'description' => 'Headphone, speaker, dan audio'],
            ['name' => 'Aksesoris', 'slug' => 'aksesoris', 'icon' => '🎮', 'description' => 'Keyboard, mouse, dan aksesoris lainnya'],
            ['name' => 'Kamera & Fotografi', 'slug' => 'kamera-fotografi', 'icon' => '📷', 'description' => 'Kamera, lensa, dan peralatan fotografi'],
            ['name' => 'Jaringan & Router', 'slug' => 'jaringan-router', 'icon' => '🌐', 'description' => 'Router, switch, dan perangkat jaringan'],
        ];

        foreach ($categories as $category) {
            Category::create($category);
        }
    }
}