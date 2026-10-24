<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'order_number',
        'total_price',
        'payment_method',
        'payment_status',
        'status',
        'shipping_address'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }
    
    public static function generateOrderNumber()
    {
        $prefix = 'INV-' . date('Ymd') . '-';
        $lastOrder = self::where('order_number', 'LIKE', $prefix . '%')
                        ->orderBy('id', 'desc')
                        ->first();
        
        if ($lastOrder) {
            $lastNumber = intval(substr($lastOrder->order_number, -4));
            $newNumber = str_pad($lastNumber + 1, 4, '0', STR_PAD_LEFT);
        } else {
            $newNumber = '0001';
        }
        
        return $prefix . $newNumber;
    }

    public function getFormattedTotalPriceAttribute(): string
    {
        return 'Rp ' . number_format($this->total_price, 0, ',', '.');
    }

    public function getCustomerNameAttribute(): string
    {
        if (preg_match('/Nama:\s*([^\n\r]+)/', $this->shipping_address ?? '', $matches)) {
            return trim($matches[1]);
        }
        return $this->user->name ?? 'Pelanggan';
    }

    public function getCustomerPhoneAttribute(): string
    {
        if (preg_match('/No\. HP\/WA:\s*([^\n\r]+)/', $this->shipping_address ?? '', $matches)) {
            return trim($matches[1]);
        }
        return '-';
    }

    public function getShippingAddressOnlyAttribute(): string
    {
        if (preg_match('/Alamat:\s*([\s\S]+?)(?:\nCatatan:|$)/', $this->shipping_address ?? '', $matches)) {
            return trim($matches[1]);
        }
        return $this->shipping_address ?? '-';
    }

    public function getNotesAttribute(): ?string
    {
        if (preg_match('/Catatan:\s*([\s\S]+)$/', $this->shipping_address ?? '', $matches)) {
            return trim($matches[1]);
        }
        return null;
    }

    public function getStatusLabelAttribute(): string
    {
        return match($this->status) {
            'pending' => 'Menunggu Konfirmasi',
            'processing' => 'Sedang Diproses',
            'completed' => 'Selesai',
            'cancelled' => 'Dibatalkan',
            default => ucfirst($this->status),
        };
    }

    public function getPaymentStatusLabelAttribute(): string
    {
        return match($this->payment_status) {
            'paid' => 'Lunas',
            'pending' => 'Belum Lunas',
            default => ucfirst($this->payment_status),
        };
    }

    public function getPaymentMethodLabelAttribute(): string
    {
        return match($this->payment_method) {
            'cod' => 'COD (Bayar di Tempat)',
            'transfer' => 'Transfer Bank',
            'qris' => 'QRIS / E-Wallet',
            'cash' => 'Tunai',
            'credit_card' => 'Kartu Kredit',
            default => strtoupper($this->payment_method),
        };
    }
}