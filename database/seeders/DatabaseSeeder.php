<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Account;
use App\Models\Category;
use App\Models\Transaction;

class DatabaseSeeder extends Seeder
{
   public function run(): void
{
    // 1. Crear usuario principal como objeto
    $user = User::factory()->engelPalacios()->create();

    // 2. Crear cuentas pasando el objeto user completo
    $accounts = Account::factory()
        ->count(3)
        ->forUser($user) // Pasa el objeto user, no solo el ID
        ->create();

    // 3. Crear categorías igualmente
    $categories = Category::factory()
        ->count(5)
        ->forUser($user) // Objeto user completo
        ->create();

    // 4. Crear transacciones
    Transaction::factory()
        ->count(30)
        ->forUser($user) // Objeto user completo
        ->expense()
        ->create();

    Transaction::factory()
        ->count(20)
        ->forUser($user) // Objeto user completo
        ->income()
        ->create();
}
}