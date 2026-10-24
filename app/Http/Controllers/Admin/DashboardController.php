<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Order;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        // ============ STATISTIK UTAMA ============
        $totalProducts = Product::count();
        $totalOrders = Order::count();
        $totalUsers = User::where('role', 'user')->count();
        $totalRevenue = Order::where('payment_status', 'paid')->sum('total_price');
        
        $pendingOrders = Order::where('status', 'pending')->count();
        $processingOrders = Order::where('status', 'processing')->count();
        $completedOrders = Order::where('status', 'completed')->count();
        $cancelledOrders = Order::where('status', 'cancelled')->count();

        // ============ GRAFIK PENJUALAN 7 HARI ============
        $salesData = [];
        $salesLabels = [];
        for ($i = 6; $i >= 0; $i--) {
            $date = Carbon::now()->subDays($i)->format('Y-m-d');
            $salesLabels[] = Carbon::now()->subDays($i)->format('d M');
            
            $total = Order::whereDate('created_at', $date)
                        ->where('payment_status', 'paid')
                        ->sum('total_price');
            $salesData[] = (int) $total;
        }

        // ============ PESANAN TERBARU ============
        $recentOrders = Order::with('user')
                            ->latest()
                            ->take(5)
                            ->get();

        // ============ PRODUK STOK RENDAH (<= 5) ============
        $lowStockProducts = Product::where('stock', '<=', 5)
                                    ->orderBy('stock', 'asc')
                                    ->take(5)
                                    ->get();

        // ============ PRODUK TERLARIS ============
        $bestSellers = DB::table('order_items')
                        ->join('products', 'order_items.product_id', '=', 'products.id')
                        ->select('products.name', 'products.image', DB::raw('SUM(order_items.quantity) as total_sold'))
                        ->groupBy('products.id', 'products.name', 'products.image')
                        ->orderBy('total_sold', 'desc')
                        ->take(5)
                        ->get();

        return view('admin.dashboard', compact(
            'totalProducts',
            'totalOrders',
            'totalUsers',
            'totalRevenue',
            'pendingOrders',
            'processingOrders',
            'completedOrders',
            'cancelledOrders',
            'salesData',
            'salesLabels',
            'recentOrders',
            'lowStockProducts',
            'bestSellers'
        ));
    }
}