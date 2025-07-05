<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use App\Models\Account;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class TransactionsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $user = $request->user();
        
        $query = Transaction::with(['category', 'account'])
            ->where('user_id', $user->id)
            ->latest();

        // Filtros
        if ($request->filled('type') && in_array($request->type, ['income', 'expense'])) {
            $query->whereHas('category', fn($q) => $q->where('type', $request->type));
        }

        if ($request->filled('account_id')) {
            $query->where('account_id', $request->account_id);
        }

        if ($request->filled('category_id')) {
            $query->where('category_id', $request->category_id);
        }

        if ($request->filled('start_date') && $request->filled('end_date')) {
            $query->whereBetween('date', [$request->start_date, $request->end_date]);
        }

        $transactions = $query->paginate(15);
        $accounts = Account::where('user_id', $user->id)->get();
        $categories = Category::where('user_id', $user->id)->get();

        return view('transacciones', compact('transactions', 'accounts', 'categories'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {
        $user = $request->user();
        return view('transactions.create', [
            'categories' => Category::where('user_id', $user->id)->get(),
            'accounts' => Account::where('user_id', $user->id)->get()
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $this->validateRequest($request);

        return DB::transaction(function () use ($validated, $request) {
            $user = $request->user();
            
            // Crear transacción
            $transaction = new Transaction($validated);
            $transaction->user_id = $user->id;
            $transaction->save();
            
            // Actualizar balance
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
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Request $request, Transaction $transaction)
    {
        if ($request->user()->id !== $transaction->user_id) {
            abort(403, 'No tienes permiso para editar esta transacción');
        }
        
        return view('transactions.create', [
            'transaction' => $transaction,
            'categories' => Category::where('user_id', $request->user()->id)->get(),
            'accounts' => Account::where('user_id', $request->user()->id)->get(),
            'editMode' => true
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Transaction $transaction)
    {
        if ($request->user()->id !== $transaction->user_id) {
            abort(403, 'No tienes permiso para actualizar esta transacción');
        }

        $validated = $this->validateRequest($request, $transaction);

        return DB::transaction(function () use ($validated, $transaction) {
            $oldAccount = $transaction->account;
            $oldAmount = $transaction->amount;
            $oldType = $transaction->type;

            // Actualizar transacción
            $transaction->update($validated);
            
            // Manejar cambios de cuenta
            if ($oldAccount->id !== $validated['account_id']) {
                // Revertir en cuenta anterior
                $oldAccount->balance -= $oldType === 'income' ? $oldAmount : -$oldAmount;
                $oldAccount->save();
                
                // Aplicar en nueva cuenta
                $newAccount = Account::where('id', $validated['account_id'])
                    ->where('user_id', $transaction->user_id)
                    ->lockForUpdate()
                    ->firstOrFail();
                    
                $newAccount->balance += $validated['type'] === 'income' 
                    ? $validated['amount'] 
                    : -$validated['amount'];
                $newAccount->save();
            } else {
                // Ajustar diferencia en misma cuenta
                $difference = ($validated['type'] === 'income' ? $validated['amount'] : -$validated['amount']) 
                            - ($oldType === 'income' ? $oldAmount : -$oldAmount);
                $oldAccount->balance += $difference;
                $oldAccount->save();
            }

            return redirect()->route('transacciones.index')
                ->with('success', 'Transacción actualizada exitosamente');
        });
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request, Transaction $transaction)
    {
        if ($request->user()->id !== $transaction->user_id) {
            abort(403, 'No tienes permiso para eliminar esta transacción');
        }

        return DB::transaction(function () use ($transaction) {
            $account = $transaction->account;
            $account->balance -= $transaction->type === 'income' 
                ? $transaction->amount 
                : -$transaction->amount;
            $account->save();

            $transaction->delete();

            return back()->with('success', 'Transacción eliminada correctamente');
        });
    }

    /**
     * Validate the transaction request.
     */
    protected function validateRequest(Request $request, ?Transaction $transaction = null): array
    {
        return $request->validate([
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
    }
}