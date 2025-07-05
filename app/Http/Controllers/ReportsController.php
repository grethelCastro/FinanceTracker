<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use App\Models\Category;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class ReportsController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $now = Carbon::now();
        
        // Datos para gráficos (últimos 6 meses)
        $monthlySummary = [];
        for ($i = 5; $i >= 0; $i--) {
            $date = $now->copy()->subMonths($i);
            $monthName = $date->format('M Y');
            
            $monthlySummary[$monthName] = [
                'income' => Transaction::where('user_id', $user->id)
                    ->whereHas('category', fn($q) => $q->where('type', 'income'))
                    ->whereMonth('date', $date->month)
                    ->whereYear('date', $date->year)
                    ->sum('amount'),
                'expenses' => Transaction::where('user_id', $user->id)
                    ->whereHas('category', fn($q) => $q->where('type', 'expense'))
                    ->whereMonth('date', $date->month)
                    ->whereYear('date', $date->year)
                    ->sum('amount'),
            ];
        }
        
        // Datos para gráfico por categoría
        $categoriesData = Category::where('user_id', $user->id)
            ->where('type', 'expense')
            ->withSum([
                'transactions' => function($query) use ($now) {
                    $query->whereYear('date', $now->year)
                          ->whereMonth('date', $now->month);
                }
            ], 'amount')
            ->having('transactions_sum_amount', '>', 0)
            ->get();
        
        $categoryLabels = $categoriesData->pluck('name')->toArray();
        $categoryAmounts = $categoriesData->pluck('transactions_sum_amount')->toArray();
        
        return view('reportes', compact(
            'monthlySummary',
            'categoryLabels',
            'categoryAmounts'
        ));
    }
}