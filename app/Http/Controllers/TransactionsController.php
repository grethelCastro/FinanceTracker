<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use App\Models\Account;
use App\Models\Category;
use App\Models\User; // Asegúrate de tener esta línea
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TransactionsController extends Controller
{
    /**
     * Mostrar todas las transacciones del usuario autenticado.
     */
    public function index()
    {
        /** @var User $user */
        $user = Auth::user();

        $transactions = $user->transactions()
            ->with(['category', 'account'])
            ->latest()
            ->paginate(10);

        return view('transactions.index', compact('transactions'));
    }

    /**
     * Mostrar formulario para crear una nueva transacción.
     */
    public function create()
    {
        /** @var User $user */
        $user = Auth::user();

        $categories = $user->categories;
        $accounts = $user->accounts;

        return view('transactions.create', compact('categories', 'accounts'));
    }

    /**
     * Guardar una nueva transacción en la base de datos.
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

        /** @var User $user */
        $user = Auth::user();

        $transaction = $user->transactions()->create($validated);

        // Actualizar balance de la cuenta
        $account = Account::find($validated['account_id']);
        if ($account) {
            $account->balance += $validated['type'] === 'income'
                ? $validated['amount']
                : -$validated['amount'];
            $account->save();
        }

        return redirect()->route('transactions.index')
            ->with('success', 'Transacción creada exitosamente');
    }

    // Aquí puedes seguir implementando los métodos: show(), edit(), update(), destroy()
}