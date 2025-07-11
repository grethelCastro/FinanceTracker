<?php

namespace Database\Factories;

use App\Models\Account;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class AccountFactory extends Factory
{
    protected $model = Account::class;

    public function definition(): array
    {
        return [
            'name' => $this->faker->word . ' Account',
            'balance' => $this->faker->randomFloat(2, 1000, 10000),
            'initial_balance' => $this->faker->randomFloat(2, 1000, 5000),
            'type' => $this->faker->randomElement(['cash', 'bank', 'credit']),
            'user_id' => User::factory(),
        ];
    }

    public function forUser($user): static
    {
        return $this->state(fn (array $attributes) => [
            'user_id' => $user->id,
        ]);
    }
}