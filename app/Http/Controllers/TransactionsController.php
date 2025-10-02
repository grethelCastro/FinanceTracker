<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use App\Models\Account;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Log;

class TransactionsController extends Controller
{
    public function index(Request $request)
    {
        try {
            $user = $request->user();
            
            $query = Transaction::with([
                    'category' => function($q) use ($user) {
                        $q->where('user_id', $user->id);
                    },
                    'account' => function($q) use ($user) {
                        $q->where('user_id', $user->id);
                    }
                ])
                ->where('user_id', $user->id)
                ->latest();

            // Filtro por tipo (income/expense)
            if ($request->filled('transaction_type') && in_array($request->transaction_type, ['income', 'expense'])) {
                $query->whereHas('category', fn($q) => $q->where('type', $request->transaction_type));
            }

            // Filtro por día específico
            if ($request->filled('day')) {
                $query->whereDay('date', $request->day);
            }

            // Filtro por mes específico
            if ($request->filled('month')) {
                $query->whereMonth('date', $request->month);
            }

            // Filtro por rango de fechas
            if ($request->filled('start_date') && $request->filled('end_date')) {
                $query->whereBetween('date', [$request->start_date, $request->end_date]);
            }

            $transactions = $query->paginate(15)->withQueryString();
            $accounts = Account::where('user_id', $user->id)->get();
            $categories = Category::where('user_id', $user->id)->get();

            return view('transacciones', compact('transactions', 'accounts', 'categories'));

        } catch (\Exception $e) {
            Log::error('Error en TransactionsController@index: ' . $e->getMessage());
            return back()->with('error', 'Error al cargar transacciones');
        }
    }

    public function create(Request $request)
    {
        try {
            $user = $request->user();
            return view('transactions.create', [
                'categories' => Category::where('user_id', $user->id)->get(),
                'accounts' => Account::where('user_id', $user->id)->get()
            ]);
        } catch (\Exception $e) {
            Log::error('Error en TransactionsController@create: ' . $e->getMessage());
            return back()->with('error', 'Error al cargar formulario');
        }
    }

    public function store(Request $request)
{
    try {
        $validated = $request->validate([
            'type' => ['required', Rule::in(['income', 'expense'])],
            'amount' => 'required|numeric|min:0.01|max:9999999.99',
            'category_id' => [
                'required',
                Rule::exists('categories', 'id')->where('user_id', $request->user()->id)
            ],
            'account_id' => [
                'required',
                Rule::exists('accounts', 'id')->where('user_id', $request->user()->id)
            ],
            'date' => 'required|date|before_or_equal:today',
            'description' => 'nullable|string|max:255'
        ]);

        return DB::transaction(function () use ($validated, $request) {
            $user = $request->user();

            // Crear transacción en NIO
            $transaction = new Transaction($validated);
            $transaction->user_id = $user->id;
            $transaction->save();

            // Actualizar saldo de la cuenta en NIO
            $account = Account::where('id', $validated['account_id'])
                ->where('user_id', $user->id)
                ->lockForUpdate()
                ->firstOrFail();

            $account->balance += $validated['type'] === 'income' 
                ? $validated['amount'] 
                : -$validated['amount'];
            $account->save();

            return redirect()->route('transacciones.index')
                ->with('success', 'Transacción creada exitosamente');
        });

    } catch (\Exception $e) {
        Log::error('Error en TransactionsController@store: ' . $e->getMessage());
        return back()->with('error', 'Error al crear transacción')->withInput();
    }
}


public function edit(Request $request, Transaction $transaction)
{
    try {
        Log::info('Edit method called', [
            'transaction_id' => $transaction->id,
            'user_id' => $request->user()->id,
            'transaction_user_id' => $transaction->user_id
        ]);

        // Verificar que la transacción pertenezca al usuario autenticado
        if ($request->user()->id !== $transaction->user_id) {
            \Log::warning('Unauthorized edit attempt', [
                'user_id' => $request->user()->id,
                'transaction_user_id' => $transaction->user_id
            ]);
            abort(403, 'No tienes permiso para editar esta transacción');
        }
        
        \Log::info('Loading edit view', [
            'view_path' => 'transactions.edit',
            'transaction_id' => $transaction->id
        ]);

        return view('transactions.edit', [
            'transaction' => $transaction,
            'categories' => Category::where('user_id', $request->user()->id)->get(),
            'accounts' => Account::where('user_id', $request->user()->id)->get()
        ]);

    } catch (\Exception $e) {
        \Log::error('Error en TransactionsController@edit: ' . $e->getMessage());
        return back()->with('error', 'Error al cargar transacción para editar');
    }
}
    public function update(Request $request, Transaction $transaction)
{
    try {
        if ($request->user()->id !== $transaction->user_id) {
            abort(403, 'No tienes permiso para actualizar esta transacción');
        }

        $validated = $request->validate([
            'type' => ['required', Rule::in(['income', 'expense'])],
            'amount' => 'required|numeric|min:0.01|max:9999999.99',
            'category_id' => [
                'required',
                Rule::exists('categories', 'id')->where('user_id', $request->user()->id)
            ],
            'account_id' => [
                'required',
                Rule::exists('accounts', 'id')->where('user_id', $request->user()->id)
            ],
            'date' => 'required|date|before_or_equal:today',
            'description' => 'nullable|string|max:255'
        ]);

        return DB::transaction(function () use ($validated, $transaction) {
            $oldAccount = $transaction->account;
            $oldAmount = $transaction->amount;
            $oldType = $transaction->type;

            // Actualizar transacción
            $transaction->update($validated);

            // Si cambió la cuenta
            if ($oldAccount->id !== $validated['account_id']) {
                // Revertir saldo en cuenta antigua
                $oldAccount->balance -= ($oldType === 'income' ? $oldAmount : -$oldAmount);
                $oldAccount->save();

                // Sumar saldo en nueva cuenta
                $newAccount = Account::where('id', $validated['account_id'])
                    ->where('user_id', $transaction->user_id)
                    ->lockForUpdate()
                    ->firstOrFail();

                $newAccount->balance += ($validated['type'] === 'income' ? $validated['amount'] : -$validated['amount']);
                $newAccount->save();
            } else {
                // Si no cambió la cuenta, ajustar la diferencia
                $difference = ($validated['type'] === 'income' ? $validated['amount'] : -$validated['amount'])
                            - ($oldType === 'income' ? $oldAmount : -$oldAmount);

                $oldAccount->balance += $difference;
                $oldAccount->save();
            }

            return redirect()->route('transacciones.index')
                ->with('success', 'Transacción actualizada exitosamente');
        });

    } catch (\Exception $e) {
        Log::error('Error en TransactionsController@update: ' . $e->getMessage());
        return back()->with('error', 'Error al actualizar transacción')->withInput();
    }
}


    public function destroy(Request $request, $id)
    {
        try {
            $transaction = Transaction::findOrFail($id);
            
            // Verificar que la transacción pertenezca al usuario autenticado
            if ($request->user()->id !== $transaction->user_id) {
                return back()->with('error', 'No tienes permiso para eliminar esta transacción');
            }

            DB::transaction(function () use ($transaction) {
                $account = $transaction->account;
                
                // Revertir el efecto de la transacción en el balance de la cuenta
                if ($account) {
                    if ($transaction->type === 'income') {
                        // Si era un ingreso, restamos el monto del balance
                        $account->balance -= $transaction->amount;
                    } else {
                        // Si era un gasto, sumamos el monto al balance
                        $account->balance += $transaction->amount;
                    }
                    $account->save();
                }

                $transaction->delete();
            });

            return back()->with('success', 'Transacción eliminada correctamente');

        } catch (\Exception $e) {
            Log::error('Error en TransactionsController@destroy: ' . $e->getMessage());
            return back()->with('error', 'Error al eliminar transacción: ' . $e->getMessage());
        }
    }
    public function show(Request $request, $id)
{
    try {
        $user = $request->user();
        
        $transaction = Transaction::with(['category', 'account'])
            ->where('user_id', $user->id)
            ->where('id', $id)
            ->firstOrFail();

        return view('transactions.show', compact('transaction'));

    } catch (\Exception $e) {
        Log::error('Error en TransactionsController@show: ' . $e->getMessage());
        return back()->with('error', 'Transacción no encontrada');
    }
}
}