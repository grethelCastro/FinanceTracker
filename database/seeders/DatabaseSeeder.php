<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Category;
use App\Models\Account;
use App\Models\Transaction;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run()
    {
        // Usuario demo
        $user = User::create([
            'name' => 'Usuario Demo',
            'email' => 'demo@financetracker.com',
            'password' => Hash::make('password'),
            'currency' => 'NIO',
            'date_format' => 'd/m/Y'
        ]);

        // Categorías
        $categories = [
            ['name' => 'Salario', 'type' => 'income'],
            ['name' => 'Freelance', 'type' => 'income'],
            ['name' => 'Alimentos', 'type' => 'expense'],
            ['name' => 'Transporte', 'type' => 'expense'],
            ['name' => 'Entretenimiento', 'type' => 'expense'],
            ['name' => 'Servicios', 'type' => 'expense'],
        ];

        foreach ($categories as $category) {
            Category::create([...$category, 'user_id' => $user->id]);
        }

        // Cuentas
        $accounts = [
            ['name' => 'Efectivo', 'type' => 'cash', 'balance' => 5000],
            ['name' => 'Banco BAC', 'type' => 'bank', 'balance' => 15000],
            ['name' => 'Tarjeta de Crédito', 'type' => 'credit', 'balance' => -2000],
        ];

        foreach ($accounts as $account) {
            Account::create([
                'name' => $account['name'],
                'type' => $account['type'],
                'balance' => $account['balance'],
                'initial_balance' => $account['balance'],
                'user_id' => $user->id
            ]);
        }

        // Transacciones de ejemplo
        $transactions = [
            ['type' => 'income', 'amount' => 25000, 'description' => 'Salario mensual', 'date' => now(), 'category_id' => 1, 'account_id' => 2],
            ['type' => 'expense', 'amount' => 1500.50, 'description' => 'Supermercado', 'date' => now(), 'category_id' => 3, 'account_id' => 1],
            ['type' => 'expense', 'amount' => 800, 'description' => 'Transporte', 'date' => now(), 'category_id' => 4, 'account_id' => 1],
        ];

        foreach ($transactions as $transaction) {
            Transaction::create([...$transaction, 'user_id' => $user->id]);
        }
    }
}