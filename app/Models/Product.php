<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'price',
        'image',
        'stock',
        'category_id'
    ];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function reviews()
    {
        return $this->hasMany(Review::class);
    }

    // Hitung rata-rata rating
    public function getAverageRatingAttribute()
    {
        return $this->reviews()->avg('rating') ?? 0;
    }

    // Hitung total rating
    public function getTotalReviewsAttribute()
    {
        return $this->reviews()->count();
    }

    // Hitung bintang (untuk display)
    public function getStarsAttribute()
    {
        $avg = round($this->average_rating);
        return str_repeat('⭐', $avg) . str_repeat('☆', 5 - $avg);
    }
}