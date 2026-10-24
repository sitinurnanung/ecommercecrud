<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Struk Pesanan — {{ $order->number }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap');
        body { font-family: 'Plus Jakarta Sans', sans-serif; }

        @media print {
            .no-print { display: none !important; }
            body { background: white !important; color: black !important; }
            .print-card { border: 1px solid #ddd !important; background: white !important; color: black !important; box-shadow: none !important; }
            .print-text { color: black !important; }
            .print-border { border-color: #ddd !important; }
        }
    </style>
</head>
<body class="bg-zinc-950 text-zinc-100 antialiased min-h-screen flex flex-col justify-between p-4 sm:p-6">

    <div class="max-w-md mx-auto w-full flex items-center justify-between mb-6 no-print">
        <a href="/" class="text-xs font-medium text-zinc-400 hover:text-white transition flex items-center gap-1">
            &larr; Kembali ke Katalog
        </a>
        <button onclick="window.print()" class="bg-zinc-100 hover:bg-zinc-200 text-zinc-950 text-xs font-bold px-4 py-2 rounded-lg transition flex items-center gap-2">
            ??? Cetak Struk
        </button>
    </div>

    <main class="max-w-md mx-auto w-full bg-zinc-900/90 border border-zinc-800 rounded-2xl p-6 sm:p-8 shadow-2xl print-card">
        
        <div class="text-center border-b border-zinc-800/80 pb-6 print-border">
            <div class="w-10 h-10 bg-zinc-100 text-zinc-950 font-bold rounded-xl flex items-center justify-center text-sm mx-auto mb-3 no-print">
                S
            </div>
            <h1 class="text-lg font-bold tracking-wider text-zinc-100 uppercase print-text">STORE FRONT</h1>
            <p class="text-[11px] text-zinc-500 mt-0.5 print-text">Bukti Transaksi Resmi</p>
        </div>

        <div class="py-6 border-b border-zinc-800/80 space-y-3 text-xs print-border">
            <div class="flex justify-between">
                <span class="text-zinc-500 print-text">No. Transaksi</span>
                <span class="font-mono font-bold text-zinc-200 print-text">{{ $order->number }}</span>
            </div>
            <div class="flex justify-between">
                <span class="text-zinc-500 print-text">Tanggal</span>
                <span class="text-zinc-300 print-text">{{ $order->created_at->format('d M Y, H:i') }} WIB</span>
            </div>
            <div class="flex justify-between">
                <span class="text-zinc-500 print-text">Status Pembayaran</span>
                <span class="bg-amber-950/60 border border-amber-800/60 text-amber-300 text-[10px] font-semibold px-2 py-0.5 rounded print-text">
                    {{ strtoupper($order->payment_status) }}
                </span>
            </div>
        </div>

        <div class="py-6 border-b border-zinc-800/80 space-y-3 text-xs print-border">
            <h2 class="text-[11px] font-bold text-zinc-400 uppercase tracking-wider print-text mb-2">Informasi Pembeli</h2>
            <div class="flex justify-between">
                <span class="text-zinc-500 print-text">Nama</span>
                <span class="font-medium text-zinc-200 print-text">{{ $order->customer_name }}</span>
            </div>
            <div class="flex justify-between">
                <span class="text-zinc-500 print-text">No. WhatsApp</span>
                <span class="font-medium text-zinc-200 print-text">{{ $order->customer_phone }}</span>
            </div>
            <div class="flex justify-between gap-4">
                <span class="text-zinc-500 flex-shrink-0 print-text">Alamat</span>
                <span class="font-medium text-zinc-200 text-right print-text">{{ $order->customer_address }}</span>
            </div>
        </div>

        <div class="pt-6 space-y-3 text-xs">
            <div class="flex justify-between">
                <span class="text-zinc-500 print-text">Metode Pembayaran</span>
                <span class="font-semibold text-zinc-200 print-text">{{ $order->payment_method }}</span>
            </div>
            <div class="flex justify-between items-center pt-3 border-t border-zinc-800/80 print-border">
                <span class="text-sm font-bold text-zinc-300 print-text">Total Bayar</span>
                <span class="text-base font-bold text-zinc-100 print-text">
                    Rp {{ number_format($order->total_price, 0, ',', '.') }}
                </span>
            </div>
        </div>

        <div class="mt-8 text-center text-[10px] text-zinc-600 print-text border-t border-dashed border-zinc-800/80 pt-4 print-border">
            <p>Terima kasih telah berbelanja di Store Front.</p>
            <p class="mt-0.5">Simpan struk ini sebagai bukti transaksi sah.</p>
        </div>

    </main>

    <footer class="text-center text-xs text-zinc-600 mt-6 no-print">
        &copy; 2026 STORE FRONT.
    </footer>

</body>
</html>
