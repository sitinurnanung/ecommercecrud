<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Category;

class CategorySeeder extends Seeder
{
    public function run()
    {
        $categories = [
            ['name' => 'Laptop & Komputer', 'slug' => 'laptop-komputer', 'description' => 'Laptop, PC, dan aksesoris komputer'],
            ['name' => 'Smartphone & Tablet', 'slug' => 'smartphone-tablet',  'description' => 'HP, tablet, dan aksesoris'],
            ['name' => 'Audio & Headphone', 'slug' => 'audio-headphone', 'description' => 'Headphone, speaker, dan audio'],
            ['name' => 'Aksesoris', 'slug' => 'aksesoris', 'description' => 'Keyboard, mouse, dan aksesoris lainnya'],
            ['name' => 'Kamera & Fotografi', 'slug' => 'kamera-fotografi',  'description' => 'Kamera, lensa, dan peralatan fotografi'],
            ['name' => 'Jaringan & Router', 'slug' => 'jaringan-router',  'description' => 'Router, switch, dan perangkat jaringan'],
        ];

        foreach ($categories as $category) {
            Category::create($category);
        }
    }
}