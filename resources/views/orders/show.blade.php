@extends('layouts.app')

@section('title', 'Detail Pesanan')

@section('content')
<div class="container">
    <div class="card shadow-sm">
        <div class="card-header bg-primary text-white">
            <div class="d-flex justify-content-between align-items-center">
                <h5 class="mb-0">
                    <i class="fas fa-receipt"></i> Detail Pesanan #{{ $order->order_number }}
                </h5>
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
            <!-- Informasi Pesanan -->
            <div class="row mb-4">
                <div class="col-md-6">
                    <div class="card bg-light">
                        <div class="card-body">
                            <h6 class="text-primary mb-3"><i class="fas fa-info-circle"></i> Informasi Pesanan</h6>
                            <table class="table table-sm table-borderless mb-0">
                                <tr>
                                    <td class="fw-bold" style="width: 40%;">Nomor Order</td>
                                    <td>: <strong>{{ $order->order_number }}</strong></td>
                                </tr>
                                <tr>
                                    <td class="fw-bold">Tanggal</td>
                                    <td>: {{ $order->created_at->format('d/m/Y H:i') }}</td>
                                </tr>
                                <tr>
                                    <td class="fw-bold">Status Pesanan</td>
                                    <td>: 
                                        @if($order->status == 'pending')
                                            <span class="badge bg-warning">Menunggu</span>
                                        @elseif($order->status == 'processing')
                                            <span class="badge bg-info">Diproses</span>
                                        @elseif($order->status == 'completed')
                                            <span class="badge bg-success">Selesai</span>
                                        @else
                                            <span class="badge bg-danger">Dibatalkan</span>
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <td class="fw-bold">Status Pembayaran</td>
                                    <td>: 
                                        @if($order->payment_status == 'paid')
                                            <span class="badge bg-success">✅ Lunas</span>
                                        @else
                                            <span class="badge bg-warning">⏳ Menunggu Pembayaran</span>
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <td class="fw-bold">Metode Pembayaran</td>
                                    <td>: 
                                        @if($order->payment_method == 'cash')
                                            💰 Tunai
                                        @elseif($order->payment_method == 'transfer')
                                            🏦 Transfer Bank
                                        @elseif($order->payment_method == 'credit_card')
                                            💳 Kartu Kredit
                                        @else
                                            {{ ucfirst($order->payment_method) }}
                                        @endif
                                    </td>
                                </tr>
                            </table>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="card bg-light">
                        <div class="card-body">
                            <h6 class="text-primary mb-3"><i class="fas fa-map-marker-alt"></i> Alamat Pengiriman</h6>
                            @if($order->shipping_address)
                                <p class="border p-2 rounded bg-white mb-0">
                                    {{ $order->shipping_address }}
                                </p>
                            @else
                                <p class="text-muted">Tidak ada alamat pengiriman</p>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <!-- Daftar Produk -->
            <h6 class="text-primary mb-3"><i class="fas fa-box"></i> Produk yang Dibeli</h6>
            <div class="table-responsive">
                <table class="table table-hover table-bordered">
                    <thead class="table-light">
                        <tr>
                            <th>#</th>
                            <th>Produk</th>
                            <th class="text-center">Qty</th>
                            <th class="text-end">Harga</th>
                            <th class="text-end">Subtotal</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php $no = 1; @endphp
                        @foreach($order->items as $item)
                        <tr>
                            <td>{{ $no++ }}</td>
                            <td>
                                <strong>{{ $item->product->name }}</strong>
                                <br>
                                <small class="text-muted">{{ Str::limit($item->product->description, 50) }}</small>
                            </td>
                            <td class="text-center">{{ $item->quantity }}</td>
                            <td class="text-end">Rp {{ number_format($item->price, 0, ',', '.') }}</td>
                            <td class="text-end">Rp {{ number_format($item->price * $item->quantity, 0, ',', '.') }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                    <tfoot>
                        <tr class="table-primary">
                            <th colspan="4" class="text-end">Total Pembayaran</th>
                            <th class="text-end">
                                <strong>Rp {{ number_format($order->total_price, 0, ',', '.') }}</strong>
                            </th>
                        </tr>
                    </tfoot>
                </table>
            </div>

            <!-- Tombol Aksi -->
            <div class="d-flex justify-content-between align-items-center mt-4">
                <a href="{{ route('orders.index') }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-left"></i> Kembali ke Pesanan
                </a>
                
                <div class="d-flex gap-2">
                    @if($order->payment_status == 'pending')
                        <form action="{{ route('orders.confirm-payment', $order) }}" method="POST">
                            @csrf
                            <button type="submit" class="btn btn-success" onclick="return confirm('Yakin ingin mengkonfirmasi pembayaran untuk order #{{ $order->order_number }}?')">
                                <i class="fas fa-check-circle"></i> Konfirmasi Pembayaran
                            </button>
                        </form>
                    @endif
                    
                    <button class="btn btn-primary" onclick="window.print()">
                        <i class="fas fa-print"></i> Cetak
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    @media print {
        .btn, .navbar, .d-flex.gap-2, .container > .mt-4 {
            display: none !important;
        }
        .card {
            border: none !important;
            box-shadow: none !important;
        }
        .card-header {
            background-color: #0d6efd !important;
            color: white !important;
            -webkit-print-color-adjust: exact !important;
            print-color-adjust: exact !important;
        }
        .badge {
            -webkit-print-color-adjust: exact !important;
            print-color-adjust: exact !important;
        }
        .table-primary {
            background-color: #0d6efd !important;
            color: white !important;
            -webkit-print-color-adjust: exact !important;
            print-color-adjust: exact !important;
        }
        .bg-light {
            background-color: #f8f9fa !important;
            -webkit-print-color-adjust: exact !important;
            print-color-adjust: exact !important;
        }
    }
</style>
@endsection