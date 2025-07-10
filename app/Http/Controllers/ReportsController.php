<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use App\Models\Category;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Carbon\Carbon;

class ReportsController extends Controller
{
    protected function generateChartColors($count)
    {
        $baseColors = [
            'rgba(255, 99, 132, 0.7)',
            'rgba(54, 162, 235, 0.7)',
            'rgba(255, 206, 86, 0.7)',
            'rgba(75, 192, 192, 0.7)',
            'rgba(153, 102, 255, 0.7)',
            'rgba(255, 159, 64, 0.7)'
        ];
        
        $colors = [];
        for ($i = 0; $i < $count; $i++) {
            $colors[] = $baseColors[$i % count($baseColors)];
        }
        
        return $colors;
    }

    public function index(Request $request)
    {
        $user = Auth::user();
        $now = Carbon::now();
        
        Carbon::setLocale('es');
        
        // 1. Datos para gráfico mensual
        $monthlyData = [];
        $currentYear = $now->year;
        
        for ($month = 1; $month <= $now->month; $month++) {
            $date = Carbon::create($currentYear, $month, 1);
            $monthKey = $date->format('Y-m');
            $monthName = $date->translatedFormat('M Y');
            
            $monthlyData[$monthKey] = [
                'label' => $monthName,
                'income' => Transaction::where('user_id', $user->id)
                    ->whereHas('category', fn($q) => $q->where('type', 'income'))
                    ->whereMonth('date', $month)
                    ->whereYear('date', $currentYear)
                    ->sum('amount') ?? 0,
                'expenses' => Transaction::where('user_id', $user->id)
                    ->whereHas('category', fn($q) => $q->where('type', 'expense'))
                    ->whereMonth('date', $month)
                    ->whereYear('date', $currentYear)
                    ->sum('amount') ?? 0
            ];
        }
        
        ksort($monthlyData);
        
        // 2. Datos para gráfico circular de gastos (Distribución de Gastos)
    $expenseFilter = $request->input('expense_filter', 'current_month');
    $expenseSelectedMonth = $request->input('expense_month');
    
        if ($expenseFilter === 'month' && empty($expenseSelectedMonth)) {
        $expenseFilter = 'current_month';
    }
    $expenseData = $this->getExpenseData($user, $expenseFilter, $expenseSelectedMonth);
        
        // 3. Datos para gráfico de barras de categorías (Gastos por Categoría)
    $categoryFilter = $request->input('category_filter', 'current_month');
    $categorySelectedMonth = $request->input('category_month');
    
   if ($categoryFilter === 'month' && empty($categorySelectedMonth)) {
        $categoryFilter = 'current_month';
    }
    
    $categoryData = $this->getCategoryData($user, $categoryFilter, $categorySelectedMonth);
        
        // Meses disponibles para los filtros
        $availableMonths = Transaction::where('user_id', $user->id)
            ->selectRaw('DISTINCT DATE_FORMAT(date, "%Y-%m") as month')
            ->orderBy('month', 'desc')
            ->pluck('month')
            ->map(function ($month) {
                $date = Carbon::createFromFormat('Y-m', $month);
                return [
                    'value' => $month,
                    'label' => $date->translatedFormat('F Y')
                ];
            });
        
        return view('reportes', [
            // Datos para gráfico mensual
            'monthlyLabels' => array_column($monthlyData, 'label'),
            'monthlyIncome' => array_column($monthlyData, 'income'),
            'monthlyExpenses' => array_column($monthlyData, 'expenses'),
            
            // Datos para gráfico circular (Distribución de Gastos)
            'expenseChart' => [
                'labels' => $expenseData['labels'],
                'data' => $expenseData['data'],
                'colors' => $this->generateChartColors(count($expenseData['labels'])),
                'isEmpty' => empty($expenseData['data'])
            ],
            
            // Datos para gráfico de barras (Gastos por Categoría)
           'categoryChart' => [
            'income' => $categoryData['income'],
            'expenses' => $categoryData['expenses'],
            'isEmpty' => empty($categoryData['income']['data']) && empty($categoryData['expenses']['data'])
        ],
            
            // Datos para filtros
            'currentMonth' => $now->format('Y-m'),
            'currentMonthFormatted' => $now->translatedFormat('F Y'),
            'availableMonths' => $availableMonths,
            
            // Filtros actuales
            'expenseFilter' => $expenseFilter,
            'expenseSelectedMonth' => $expenseSelectedMonth,
            'categoryFilter' => $categoryFilter,
            'categorySelectedMonth' => $categorySelectedMonth
        ]);
    }
    
 protected function getExpenseData($user, $filter, $selectedMonth)
{
    $transactionQuery = Transaction::where('user_id', $user->id)
        ->whereHas('category', fn($q) => $q->where('type', 'expense'));
    
    $now = now();
    
    if ($filter === 'week') {
        $transactionQuery->whereBetween('date', [
            $now->startOfWeek()->toDateString(),
            $now->endOfWeek()->toDateString()
        ]);
    } elseif ($filter === 'current_month') {
        $transactionQuery->whereMonth('date', $now->month)
            ->whereYear('date', $now->year);
    } elseif ($filter === 'month' && $selectedMonth) {
        $monthDate = Carbon::createFromFormat('Y-m', $selectedMonth);
        $transactionQuery->whereMonth('date', $monthDate->month)
            ->whereYear('date', $monthDate->year);
    }
    
    $categories = Category::where('user_id', $user->id)
        ->where('type', 'expense')
        ->with(['transactions' => function($q) use ($transactionQuery) {
            $q->whereIn('id', $transactionQuery->pluck('id'));
        }])
        ->get()
        ->map(function ($category) {
            $total = $category->transactions->sum('amount');
            return [
                'name' => $category->name,
                'amount' => $total > 0 ? $total : 0
            ];
        })
        ->filter(fn($item) => $item['amount'] > 0);
    
    return [
        'labels' => $categories->pluck('name')->toArray(),
        'data' => $categories->pluck('amount')->toArray()
    ];
}
public function updateCharts(Request $request)
{
    $user = Auth::user();
    
    // Datos para gráfico circular de gastos
    $expenseData = $this->getExpenseData(
        $user,
        $request->input('expense_filter', 'current_month'),
        $request->input('expense_month')
    );
    
    // Datos para gráfico de categorías
    $categoryData = $this->getCategoryData(
        $user,
        $request->input('category_filter', 'current_month'),
        $request->input('category_month')
    );
    
    return response()->json([
        'expenseChart' => [
            'labels' => $expenseData['labels'],
            'data' => $expenseData['data'],
            'colors' => $this->generateChartColors(count($expenseData['labels'])),
            'isEmpty' => empty($expenseData['data'])
        ],
        'categoryChart' => [
            'income' => $categoryData['income'],
            'expenses' => $categoryData['expenses'],
            'isEmpty' => empty($categoryData['income']['data']) && empty($categoryData['expenses']['data'])
        ]
    ]);
}
protected function getCategoryData($user, $filter, $selectedMonth) {
    $now = now();

    // Consulta base para ingresos
    $incomeQuery = Transaction::where('user_id', $user->id)
        ->whereHas('category', fn($q) => $q->where('type', 'income'));

    // Consulta base para gastos
    $expenseQuery = Transaction::where('user_id', $user->id)
        ->whereHas('category', fn($q) => $q->where('type', 'expense'));

    // Función para aplicar filtros
    $applyFilters = function($query) use ($filter, $selectedMonth, $now) {
        if ($filter === 'week') {
            $query->whereBetween('date', [
                $now->startOfWeek()->toDateString(),
                $now->endOfWeek()->toDateString()
            ]);
        } elseif ($filter === 'current_month') {
            $query->whereMonth('date', $now->month)
                ->whereYear('date', $now->year);
        } elseif ($filter === 'month' && $selectedMonth) {
            $monthDate = Carbon::createFromFormat('Y-m', $selectedMonth);
            $query->whereMonth('date', $monthDate->month)
                ->whereYear('date', $monthDate->year);
        }
        return $query;
    };

    // Aplicar filtros a ambas consultas
    $applyFilters($incomeQuery);
    $applyFilters($expenseQuery);

    // Obtener datos de ingresos
    $incomeData = $incomeQuery->with('category')
        ->get()
        ->groupBy('category_id')
        ->map(function ($transactions) {
            return [
                'name' => $transactions->first()->category->name,
                'amount' => $transactions->sum('amount')
            ];
        })
        ->sortByDesc('amount')
        ->take(10);

    // Obtener datos de gastos
    $expenseData = $expenseQuery->with('category')
        ->get()
        ->groupBy('category_id')
        ->map(function ($transactions) {
            return [
                'name' => $transactions->first()->category->name,
                'amount' => $transactions->sum('amount')
            ];
        })
        ->sortByDesc('amount')
        ->take(10);

    return [
        'income' => [
            'labels' => $incomeData->pluck('name')->toArray(),
            'data' => $incomeData->pluck('amount')->toArray(),
            'colors' => $this->generateChartColors($incomeData->count())
        ],
        'expenses' => [
            'labels' => $expenseData->pluck('name')->toArray(),
            'data' => $expenseData->pluck('amount')->toArray(),
            'colors' => $this->generateChartColors($expenseData->count())
        ]
    ];
}

}