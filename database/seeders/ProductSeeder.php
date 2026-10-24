<?php

namespace Database\Seeders;

use App\Models\Product;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class ProductSeeder extends Seeder
{
    public function run(): void
    {
        $products = [
            [
                'name' => 'Printer Epson EcoTank L3210',
                'description' => 'Printer ink-tank dengan biaya cetak rendah, cocok untuk bisnis dan rumah.',
                'price' => 2750000,
                'stock' => 35,
                'image' => 'images/products/printer-epson.jpg',
            ],
            [
                'name' => 'Power Bank Anker 20000mAh',
                'description' => 'Power bank kapasitas besar dengan fast charging dan dual output port.',
                'price' => 850000,
                'stock' => 100,
                'image' => 'images/products/power-bank-anker.jpg',
            ],
            [
                'name' => 'Keyboard Mekanik Gaming',
                'description' => 'Keyboard gaming dengan tombol mekanik responsif, nyaman digunakan untuk bermain game maupun mengetik.',
                'price' => 700000,
                'stock' => 150,
                'image' => 'images/products/keyboard-mekanik.jpg',
            ],
            [
                'name' => 'Stand Laptop',
                'description' => 'Penyangga laptop yang kokoh dan praktis untuk menjaga posisi laptop lebih nyaman saat digunakan.',
                'price' => 110000,
                'stock' => 80,
                'image' => 'images/products/stand-laptop.jpg',
            ],
            [
                'name' => 'Laptop ASUS ROG Strix G15',
                'description' => 'Laptop gaming dengan prosesor Intel Core i7, RAM 16GB, SSD 512GB, dan NVIDIA RTX 3060.',
                'price' => 19000000,
                'stock' => 90,
                'image' => 'images/products/laptop-asus-rog.jpg',
            ],
            [
                'name' => 'Technics EAH-A800',
                'description' => 'Headphone wireless premium dengan fitur noise cancelling dan suara berkualitas tinggi.',
                'price' => 5200000,
                'stock' => 110,
                'image' => 'images/products/headphone-technics.jpg',
            ],
            [
                'name' => 'SanDisk iXpand Flash Drive Flip',
                'description' => 'Flashdisk OTG dengan konektor Lightning dan USB untuk memindahkan serta menyimpan file dengan praktis.',
                'price' => 750000,
                'stock' => 250,
                'image' => 'images/products/flashdisk-sandisk.jpg',
            ],
            [
                'name' => 'Laptop Gaming ASUS ROG Strix',
                'description' => 'Laptop gaming berperforma tinggi dengan desain premium dan kemampuan grafis yang kuat.',
                'price' => 45000000,
                'stock' => 170,
                'image' => 'images/products/laptop-gaming-asus.jpg',
            ],
            [
                'name' => 'Keyboard Mechanical Keychron K2',
                'description' => 'Keyboard mechanical wireless dengan switch Gateron hot-swappable dan RGB backlight.',
                'price' => 1200000,
                'stock' => 200,
                'image' => 'images/products/keyboard-keychron.jpg',
            ],
            [
                'name' => 'Mouse Logitech MX Master 3S',
                'description' => 'Mouse premium dengan sensor 8K DPI dan scroll wheel magnetic.',
                'price' => 1500000,
                'stock' => 150,
                'image' => 'images/products/mouse-logitech.jpg',
            ],
            [
                'name' => 'Citizen Smart Digital Watch',
                'description' => 'Jam tangan modern dengan desain elegan dan tampilan waktu yang jelas.',
                'price' => 3500000,
                'stock' => 180,
                'image' => 'images/products/citizen-watch.jpg',
            ],
            [
                'name' => 'Kamera Mirrorless Sony A7 IV',
                'description' => 'Kamera mirrorless full-frame 33MP dengan autofocus canggih dan video 4K.',
                'price' => 28000000,
                'stock' => 30,
                'image' => 'images/products/kamera-sony.jpg',
            ],
            [
                'name' => 'Jabra Elite 4 Earphone',
                'description' => 'Earphone wireless dengan suara jernih dan nyaman digunakan.',
                'price' => 2500000,
                'stock' => 150,
                'image' => 'images/products/earphone-jabra.jpg',
            ],
            [
                'name' => 'Gaming Controller',
                'description' => 'Controller gaming dengan desain ergonomis dan tombol responsif untuk pengalaman bermain yang nyaman.',
                'price' => 4500000,
                'stock' => 30,
                'image' => 'images/products/gaming-controller.jpg',
            ],
            [
                'name' => 'HyperX Cloud Alpha Wireless',
                'description' => 'Headset gaming wireless dengan suara jernih, mikrofon fleksibel, dan nyaman digunakan.',
                'price' => 5000000,
                'stock' => 150,
                'image' => 'images/products/headset-hyperx.jpg',
            ],
            [
                'name' => 'Smartwatch Gaming',
                'description' => 'Smartwatch modern dengan layar sentuh dan berbagai fitur pintar untuk aktivitas sehari-hari.',
                'price' => 8500000,
                'stock' => 50,
                'image' => 'images/products/smartwatch-gaming.jpg',
            ],
        ];

        foreach ($products as $product) {
            Product::create([
                'name' => $product['name'],
                'slug' => Str::slug($product['name']),
                'description' => $product['description'],
                'price' => $product['price'],
                'stock' => $product['stock'],
                'image' => $product['image'],
            ]);
        }
    }
}