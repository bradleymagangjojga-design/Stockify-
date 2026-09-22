<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Supplier;
use App\Models\Transaction;
use App\Models\User;

class DashboardController extends Controller
{
    /**
     * Show the dashboard with statistics and charts.
     */
    public function index()
    {
        $today = now()->toDateString();

        // Chart data — last 7 days
        $labels   = [];
        $incoming = [];
        $outgoing = [];

        for ($i = 6; $i >= 0; $i--) {
            $date       = now()->subDays($i)->toDateString();
            $labels[]   = now()->subDays($i)->format('d M');
            $incoming[] = (int) Transaction::where('type', 'in')
                ->whereDate('transaction_date', $date)
                ->sum('quantity');
            $outgoing[] = (int) Transaction::where('type', 'out')
                ->whereDate('transaction_date', $date)
                ->sum('quantity');
        }

        return view('dashboard.index', [
            'totalProducts'    => Product::count(),
            'totalSuppliers'   => Supplier::count(),
            'totalUsers'       => User::count(),
            'lowStock'         => Product::lowStock()
                                    ->with('category')
                                    ->limit(5)
                                    ->get(),
            'inToday'          => (int) Transaction::where('type', 'in')
                                    ->whereDate('transaction_date', $today)
                                    ->sum('quantity'),
            'outToday'         => (int) Transaction::where('type', 'out')
                                    ->whereDate('transaction_date', $today)
                                    ->sum('quantity'),
            'recentActivities' => Transaction::with(['product', 'user'])
                                    ->latest('transaction_date')
                                    ->limit(5)
                                    ->get(),
            'chartLabels'      => $labels,
            'chartIncoming'    => $incoming,
            'chartOutgoing'    => $outgoing,
        ]);
    }
}