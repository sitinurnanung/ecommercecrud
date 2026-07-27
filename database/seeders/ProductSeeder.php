<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Product;
use App\Models\Category;

class ProductSeeder extends Seeder
{
    public function run()
    {
        // Ambil kategori
        $laptop = Category::where('slug', 'laptop-komputer')->first();
        $smartphone = Category::where('slug', 'smartphone-tablet')->first();
        $audio = Category::where('slug', 'audio-headphone')->first();
        $aksesoris = Category::where('slug', 'aksesoris')->first();
        $kamera = Category::where('slug', 'kamera-fotografi')->first();
        $jaringan = Category::where('slug', 'jaringan-router')->first();

        $products = [
            [
                'name' => 'Laptop ASUS ROG Strix G15',
                'description' => 'Laptop gaming dengan prosesor Intel Core i7, RAM 16GB, SSD 512GB, dan NVIDIA RTX 3060. Cocok untuk gaming dan editing.',
                'price' => 15000000,
                'image' => 'laptop.jpg',
                'stock' => 10,
                'category_id' => $laptop->id
            ],
            [
                'name' => 'Smartphone Samsung Galaxy S23',
                'description' => 'Smartphone flagship dengan layar Dynamic AMOLED 6.1 inch, kamera 50MP, dan baterai 3900mAh.',
                'price' => 12000000,
                'image' => 'samsung.jpg',
                'stock' => 15,
                'category_id' => $smartphone->id
            ],
            [
                'name' => 'Headphone Sony WH-1000XM5',
                'description' => 'Headphone noise-cancelling terbaik dengan kualitas suara premium dan baterai tahan 30 jam.',
                'price' => 4500000,
                'image' => 'headphone.jpg',
                'stock' => 20,
                'category_id' => $audio->id
            ],
            [
                'name' => 'Smartwatch Apple Watch Series 8',
                'description' => 'Smartwatch dengan fitur kesehatan lengkap, GPS, dan tahan air. Layar selalu menyala.',
                'price' => 8000000,
                'image' => 'watch.jpg',
                'stock' => 8,
                'category_id' => $smartphone->id
            ],
            [
                'name' => 'Tablet iPad Pro 12.9',
                'description' => 'Tablet terbaik dengan layar Liquid Retina XDR, chip M2, dan mendukung Apple Pencil.',
                'price' => 18000000,
                'image' => 'ipad.jpg',
                'stock' => 5,
                'category_id' => $smartphone->id
            ],
            [
                'name' => 'Speaker JBL Flip 6',
                'description' => 'Speaker portabel tahan air dengan suara stereo dan bass yang powerful.',
                'price' => 1500000,
                'image' => 'speaker.jpg',
                'stock' => 25,
                'category_id' => $audio->id
            ],
            [
                'name' => 'Monitor LG UltraFine 4K',
                'description' => 'Monitor 27 inch 4K UHD dengan warna akurat, cocok untuk desain dan editing.',
                'price' => 7000000,
                'image' => 'monitor.jpg',
                'stock' => 7,
                'category_id' => $laptop->id
            ],
            [
                'name' => 'Keyboard Mechanical Keychron K2',
                'description' => 'Keyboard mechanical wireless dengan switch Gateron hot-swappable dan RGB backlight.',
                'price' => 1200000,
                'image' => 'keyboard.jpg',
                'stock' => 15,
                'category_id' => $aksesoris->id
            ],
            [
                'name' => 'Mouse Logitech MX Master 3S',
                'description' => 'Mouse premium dengan sensor 8K DPI, scroll wheel magnetic, dan baterai tahan 70 hari.',
                'price' => 1500000,
                'image' => 'mouse.jpg',
                'stock' => 12,
                'category_id' => $aksesoris->id
            ],
            [
                'name' => 'Hardisk External Seagate 2TB',
                'description' => 'Hardisk eksternal portable 2TB dengan USB 3.0, kecepatan transfer tinggi.',
                'price' => 1500000,
                'image' => 'hardisk.jpg',
                'stock' => 20,
                'category_id' => $laptop->id
            ],
            [
                'name' => 'Kamera Mirrorless Sony A7 IV',
                'description' => 'Kamera mirrorless full-frame 33MP dengan autofocus canggih dan video 4K.',
                'price' => 28000000,
                'image' => 'kamera.jpg',
                'stock' => 3,
                'category_id' => $kamera->id
            ],
            [
                'name' => 'Printer Epson EcoTank L3210',
                'description' => 'Printer ink-tank dengan biaya cetak rendah, cocok untuk bisnis dan rumahan.',
                'price' => 2500000,
                'image' => 'printer.jpg',
                'stock' => 10,
                'category_id' => $laptop->id
            ],
            [
                'name' => 'Power Bank Anker 20000mAh',
                'description' => 'Power bank kapasitas besar dengan fast charging dan dual output port.',
                'price' => 600000,
                'image' => 'powerbank.jpg',
                'stock' => 30,
                'category_id' => $smartphone->id
            ],
            [
                'name' => 'Webcam Logitech C920',
                'description' => 'Webcam HD 1080p dengan autofocus dan stereo audio untuk video call dan streaming.',
                'price' => 1200000,
                'image' => 'webcam.jpg',
                'stock' => 15,
                'category_id' => $aksesoris->id
            ],
            [
                'name' => 'Router WiFi TP-Link Archer AX50',
                'description' => 'Router WiFi 6 dual-band dengan kecepatan tinggi dan jangkauan luas.',
                'price' => 2000000,
                'image' => 'router.jpg',
                'stock' => 8,
                'category_id' => $jaringan->id
            ]
        ];

        foreach ($products as $product) {
            Product::create($product);
        }
    }
}