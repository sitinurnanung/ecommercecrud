@extends('layouts.app')

@section('title', 'Pesanan Saya')

@section('content')
<div class="container">
    <h1 class="mb-4">📦 Pesanan Saya</h1>
    
    @if($orders->isEmpty())
    <div class="text-center py-5">
        <i class="fas fa-box-open fa-5x text-muted mb-4"></i>
        <h3 class="text-muted">Belum Ada Pesanan</h3>
        <p class="text-muted">Mulai belanja sekarang dan dapatkan produk favoritmu!</p>
        <a href="{{ route('home') }}" class="btn btn-primary btn-lg mt-3">
            <i class="fas fa-shopping-bag"></i> Belanja Sekarang
        </a>
    </div>
    @else
    <div class="row">
        @foreach($orders as $order)
        <div class="col-md-6 mb-4">
            <div class="card shadow-sm">
                <div class="card-header bg-primary text-white">
                    <div class="d-flex justify-content-between align-items-center">
                        <span>
                            <i class="fas fa-receipt"></i> {{ $order->order_number }}
                        </span>
                        <span>
                            @if($order->payment_status == 'paid')
                                <span class="badge bg-success">✅ Lunas</span>
                            @else
                                <span class="badge bg-warning">⏳ Pending</span>
                            @endif
                        </span>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-6">
                            <small class="text-muted">Total</small>
                            <p class="fw-bold text-primary">Rp {{ number_format($order->total_price, 0, ',', '.') }}</p>
                        </div>
                        <div class="col-6 text-end">
                            <small class="text-muted">Status</small>
                            <p>
                                @if($order->status == 'pending')
                                    <span class="badge bg-warning">Menunggu</span>
                                @elseif($order->status == 'processing')
                                    <span class="badge bg-info">Diproses</span>
                                @elseif($order->status == 'completed')
                                    <span class="badge bg-success">Selesai</span>
                                @else
                                    <span class="badge bg-danger">Dibatalkan</span>
                                @endif
                            </p>
                        </div>
                    </div>
                    <p class="text-muted small">
                        <i class="fas fa-calendar"></i> {{ $order->created_at->format('d/m/Y H:i') }}
                    </p>
                    <div class="d-flex gap-2">
                        <a href="{{ route('orders.show', $order) }}" class="btn btn-sm btn-outline-primary">
                            <i class="fas fa-eye"></i> Detail
                        </a>
                        @if($order->payment_status == 'pending')
                        <form action="{{ route('orders.confirm-payment', $order) }}" method="POST" class="d-inline">
                            @csrf
                            <button type="submit" class="btn btn-sm btn-success" onclick="return confirm('Konfirmasi pembayaran untuk order ini?')">
                                <i class="fas fa-check"></i> Konfirmasi Bayar
                            </button>
                        </form>
                        @endif
                    </div>
                </div>
            </div>
        </div>
        @endforeach
    </div>
    @endif
</div>
@endsection