@extends('layouts.app')

@section('title', 'Keranjang Belanja')

@section('content')
<div class="container">
    <h1 class="mb-4">🛒 Keranjang Belanja</h1>
    
    @if($cartItems->isEmpty())
    <div class="text-center py-5">
        <i class="fas fa-shopping-cart fa-4x text-muted mb-3"></i>
        <h3>Keranjang kosong</h3>
        <p class="text-muted">Mulai belanja sekarang!</p>
        <a href="{{ route('home') }}" class="btn btn-primary">Lihat Produk</a>
    </div>
    @else
    <div class="row">
        <div class="col-lg-8">
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>Produk</th>
                            <th>Harga</th>
                            <th>Jumlah</th>
                            <th>Subtotal</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php $total = 0 @endphp
                        @foreach($cartItems as $item)
                        @php 
                            $subtotal = $item->product->price * $item->quantity;
                            $total += $subtotal;
                        @endphp
                        <tr>
                            <td>{{ $item->product->name }}</td>
                            <td>Rp {{ number_format($item->product->price, 0, ',', '.') }}</td>
                            <td>
                                <form action="{{ route('cart.update', $item->id) }}" method="POST" class="d-inline">
                                    @csrf
                                    @method('PATCH')
                                    <input type="number" name="quantity" value="{{ $item->quantity }}" min="1" max="{{ $item->product->stock }}" class="form-control form-control-sm" style="width: 70px; display: inline-block;">
                                    <button type="submit" class="btn btn-sm btn-primary">Update</button>
                                </form>
                            </td>
                            <td>Rp {{ number_format($subtotal, 0, ',', '.') }}</td>
                            <td>
                                <form action="{{ route('cart.remove', $item->id) }}" method="POST">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Hapus item ini?')">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                    <tfoot>
                        <tr>
                            <td colspan="3" class="text-end fw-bold">Total:</td>
                            <td class="fw-bold text-primary">Rp {{ number_format($total, 0, ',', '.') }}</td>
                            <td></td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
        
        <div class="col-lg-4">
            <div class="card shadow-sm">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0">Checkout</h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('checkout') }}" method="POST">
                        @csrf
                        
                        <div class="mb-3">
                            <label class="form-label fw-bold">Total Bayar</label>
                            <h4 class="text-primary">Rp {{ number_format($total, 0, ',', '.') }}</h4>
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label fw-bold">Metode Pembayaran</label>
                            <select name="payment_method" class="form-select" required>
                                <option value="">Pilih metode...</option>
                                <option value="cash">💰 Tunai</option>
                                <option value="transfer">🏦 Transfer Bank</option>
                                <option value="credit_card">💳 Kartu Kredit</option>
                            </select>
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label fw-bold">Alamat Pengiriman</label>
                            <textarea name="shipping_address" class="form-control" rows="3" placeholder="Masukkan alamat lengkap" required></textarea>
                        </div>
                        
                        <button type="submit" class="btn btn-success w-100">
                            <i class="fas fa-check"></i> Checkout Sekarang
                        </button>
                    </form>
                </div>
            </div>
            
            <div class="mt-3">
                <a href="{{ route('home') }}" class="btn btn-secondary w-100">
                    <i class="fas fa-arrow-left"></i> Lanjut Belanja
                </a>
            </div>
        </div>
    </div>
    @endif
</div>
@endsection