<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Transaction;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    public function index()
    {
        return view('reports.index');
    }

    public function stock()
    {
        $products = Product::with('category')->orderBy('name')->paginate(30);
        return view('reports.stock', compact('products'));
    }

    public function transactions(Request $request)
    {
        $transactions = Transaction::with(['product', 'user'])
            ->when($request->type,      fn($q, $v) => $q->where('type', $v))
            ->when($request->date_from, fn($q, $v) => $q->whereDate('transaction_date', '>=', $v))
            ->when($request->date_to,   fn($q, $v) => $q->whereDate('transaction_date', '<=', $v))
            ->latest('transaction_date')
            ->paginate(20);

        return view('reports.transactions', compact('transactions'));
    }

    public function activities()
    {
        $activities = Transaction::with(['product', 'user'])
            ->latest('transaction_date')
            ->paginate(30);
        return view('reports.activities', compact('activities'));
    }
}