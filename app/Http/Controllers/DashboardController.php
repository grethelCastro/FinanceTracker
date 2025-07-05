<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use App\Models\Account;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $now = Carbon::now();
        
        // Balance actual (suma de todas las cuentas)
        $currentBalance = Account::where('user_id', $user->id)->sum('balance');
        
        // Ingresos del mes actual
        $monthlyIncome = Transaction::where('user_id', $user->id)
            ->whereHas('category', fn($q) => $q->where('type', 'income'))
            ->whereMonth('date', $now->month)
            ->whereYear('date', $now->year)
            ->sum('amount');
        
        // Gastos del mes actual
        $monthlyExpenses = Transaction::where('user_id', $user->id)
            ->whereHas('category', fn($q) => $q->where('type', 'expense'))
            ->whereMonth('date', $now->month)
            ->whereYear('date', $now->year)
            ->sum('amount');
        
        // Transacciones recientes (últimas 5)
        $recentTransactions = Transaction::with(['category', 'account'])
            ->where('user_id', $user->id)
            ->latest()
            ->take(5)
            ->get();
            
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
        
        return view('dashboard', compact(
            'currentBalance',
            'monthlyIncome',
            'monthlyExpenses',
            'recentTransactions',
            'monthlySummary'
        ));
    }
}