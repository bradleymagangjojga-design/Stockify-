<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use App\Models\StockOpname;
use App\Models\Supplier;
use App\Models\Transaction;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        $user  = auth()->user();
        $today = now()->toDateString();

        // ============================================================
        // COMMON: Chart data (Last 7 Days)
        // ============================================================
        $labels = $incoming = $outgoing = [];
        for ($i = 6; $i >= 0; $i--) {
            $date       = now()->subDays($i)->toDateString();
            $labels[]   = now()->subDays($i)->format('d M');
            
            $incoming[] = (int) Transaction::where('type', 'in')
                ->whereDate('transaction_date', $date)->sum('quantity');
                
            $outgoing[] = (int) Transaction::where('type', 'out')
                ->whereDate('transaction_date', $date)->sum('quantity');
        }

        // ============================================================
        // COMMON DATA
        // ============================================================
        $data = [
            'chartLabels'      => $labels,
            'chartIncoming'    => $incoming,
            'chartOutgoing'    => $outgoing,
            'inToday'          => (int) Transaction::where('type', 'in')->whereDate('transaction_date', $today)->sum('quantity'),
            'outToday'         => (int) Transaction::where('type', 'out')->whereDate('transaction_date', $today)->sum('quantity'),
            'lowStock'         => Product::lowStock()->with('category')->limit(6)->get(),
            'recentActivities' => Transaction::with(['product', 'user'])->latest('transaction_date')->latest('id')->limit(8)->get(),
        ];

        // ============================================================
        // ROLE-SPECIFIC DATA
        // ============================================================
        if ($user->isAdmin()) {
            $data = array_merge($data, [
                'roleView'          => 'admin',
                'totalProducts'     => Product::count(),
                'totalSuppliers'    => Supplier::count(),
                'totalUsers'        => User::count(),
                'totalCategories'   => Category::count(),
                'totalStockValue'   => Product::sum(DB::raw('stock * purchase_price')),
                'lowStockCount'     => Product::lowStock()->count(),
                'totalTransactions' => Transaction::count(),
                'topProducts'       => Product::with('category')->orderByDesc('stock')->limit(5)->get(),
                'activeUsersToday'  => Transaction::whereDate('transaction_date', $today)->distinct('user_id')->count('user_id'),
            ]);
        } elseif ($user->isManager()) {
            $data = array_merge($data, [
                'roleView'         => 'manager',
                'totalProducts'    => Product::count(),
                'totalStockValue'  => Product::sum(DB::raw('stock * purchase_price')),
                'lowStockCount'    => Product::lowStock()->count(),
                'pendingIncoming'  => Transaction::where('type', 'in')->whereDate('transaction_date', $today)->count(),
                'pendingOutgoing'  => Transaction::where('type', 'out')->whereDate('transaction_date', $today)->count(),
                'topProducts'      => Product::with('category')->orderByDesc('stock')->limit(5)->get(),
                'recentOpnames'    => StockOpname::with(['product', 'user'])->latest('opname_date')->limit(5)->get(),
            ]);
        } else {
            // Staff
            $data = array_merge($data, [
                'roleView'         => 'staff',
                'myTransactions'   => Transaction::where('user_id', $user->id)
                    ->whereDate('transaction_date', $today)->count(),
                'myIncoming'       => (int) Transaction::where('user_id', $user->id)
                    ->where('type', 'in')->whereDate('transaction_date', $today)->sum('quantity'),
                'myOutgoing'       => (int) Transaction::where('user_id', $user->id)
                    ->where('type', 'out')->whereDate('transaction_date', $today)->sum('quantity'),
                'myRecentActivity' => Transaction::with(['product', 'user'])
                    ->where('user_id', $user->id)
                    ->latest('transaction_date')->latest('id')->limit(5)->get(),
                'pendingTasks'     => [
                    ['icon' => '📥', 'label' => 'Barang masuk perlu diperiksa', 'count' => rand(2, 6), 'color' => 'blue'],
                    ['icon' => '📤', 'label' => 'Barang keluar perlu disiapkan', 'count' => rand(1, 4), 'color' => 'orange'],
                    ['icon' => '📋', 'label' => 'Stock opname hari ini', 'count' => rand(0, 2), 'color' => 'purple'],
                ],
            ]);
        }

        return view('dashboard.index', $data);
    }
}