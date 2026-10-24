<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Skema tabel products untuk menyimpan data katalog produk
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // Nama produk
            $table->string('slug')->unique(); // Slug unik untuk URL SEO-friendly
            $table->text('description'); // Deskripsi lengkap produk
            $table->decimal('price', 12, 2); // Harga produk (menggunakan decimal agar presisi)
            $table->integer('stock')->default(0); // Jumlah stok barang
            $table->string('image')->nullable(); // Path foto produk di storage
            $table->timestamps(); // Created_at dan updated_at
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Hapus tabel jika di-rollback
        Schema::dropIfExists('products');
    }
};