<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Transaction;
use App\Models\Account;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\DB; // 👈 Importamos DB

class TransactionsController extends Controller
{
    /**
     * Mostrar todas las transacciones del usuario autenticado.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();

        $transactions = $user->transactions()
            ->with(['category', 'account'])
            ->latest()
            ->paginate(10);

        // ✅ Cambio realizado: Ahora apunta a /resources/views/transacciones.blade.php
        return view('transacciones', compact('transactions'));
    }

    /**
     * Mostrar formulario para crear una nueva transacción.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();

        $categories = $user->categories;
        $accounts = $user->accounts;

        return view('transactions.create', compact('categories', 'accounts'));
    }

    /**
     * Guardar una nueva transacción en la base de datos.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     * @throws ValidationException
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'type' => 'required|in:income,expense',
            'amount' => 'required|numeric|min:0.01',
            'category_id' => 'required|exists:categories,id',
            'account_id' => 'required|exists:accounts,id',
            'date' => 'required|date',
            'description' => 'nullable|string|max:255'
        ]);

        /** @var \App\Models\User $user */
        $user = Auth::user();

        // Crear transacción asociada al usuario
        $transaction = $user->transactions()->create($validated);

        // Actualizar balance de la cuenta
        $account = Account::find($validated['account_id']);
        if ($account) {
            $account->balance += $validated['type'] === 'income'
                ? $validated['amount']
                : -$validated['amount'];
            $account->save();
        }

        return redirect()->route('transacciones')
            ->with('success', 'Transacción creada exitosamente');
    }

    /**
     * Mostrar formulario para editar una transacción existente.
     *
     * @param  \App\Models\Transaction  $transaction
     * @return \Illuminate\View\View
     */
    public function edit(Transaction $transaction)
    {
        // ✅ Reemplazamos authorize() por validación manual si no usas Policies
        if (Auth::id() !== $transaction->user_id) {
            abort(403, 'No tienes permiso para editar esta transacción');
        }

        $categories = $transaction->user->categories;
        $accounts = $transaction->user->accounts;

        return view('transactions.create', compact('transaction', 'categories', 'accounts'));
    }

    /**
     * Actualizar una transacción existente.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Transaction  $transaction
     * @return \Illuminate\Http\RedirectResponse
     * @throws ValidationException
     */
    public function update(Request $request, Transaction $transaction)
    {
        // ✅ Validación simple: aseguramos que sea del usuario
        if (Auth::id() !== $transaction->user_id) {
            abort(403, 'No tienes permiso para actualizar esta transacción');
        }

        $validated = $request->validate([
            'type' => 'required|in:income,expense',
            'amount' => 'required|numeric|min:0.01',
            'category_id' => 'required|exists:categories,id',
            'account_id' => 'required|exists:accounts,id',
            'date' => 'required|date',
            'description' => 'nullable|string|max:255'
        ]);

        $transaction->update($validated);

        // Actualizar balance de la cuenta
        $account = $transaction->account;
        if ($account && $account->user_id === Auth::id()) {
            $account->balance = $account->transactions
                ->sum(fn($t) => $t->type === 'income' ? $t->amount : - $t->amount);

            $account->save();
        }

        return redirect()->route('transacciones')
            ->with('success', 'Transacción actualizada exitosamente');
    }

    /**
     * Eliminar una transacción existente.
     *
     * @param  \App\Models\Transaction  $transaction
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(Transaction $transaction)
    {
        // ✅ Validación básica sin Policy
        if (Auth::id() !== $transaction->user_id) {
            abort(403, 'No tienes permiso para eliminar esta transacción');
        }

        $transaction->delete();

        return back()->with('success', 'Transacción eliminada correctamente');
    }
}