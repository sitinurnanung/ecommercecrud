<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

#[Fillable(['name', 'email', 'password'])]
#[Hidden(['password', 'remember_token'])]
class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    /**
     * Relasi ke Cart (Keranjang Belanja)
     */
    public function carts()
    {
        return $this->hasMany(Cart::class);
    }

    /**
     * Relasi ke Order (Pesanan)
     */
    public function orders()
    {
        return $this->hasMany(Order::class);
    }

    /**
     * Relasi ke Review (Ulasan Produk)
     */
    public function reviews()
    {
        return $this->hasMany(Review::class);
    }
}