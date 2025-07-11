<?php

namespace Database\Factories;

use App\Models\Transaction;
use App\Models\User;
use App\Models\Account;
use App\Models\Category;
use Illuminate\Database\Eloquent\Factories\Factory;

class TransactionFactory extends Factory
{
    protected $model = Transaction::class;

    public function definition(): array
    {
        $type = $this->faker->randomElement(['income', 'expense']);
        
        return [
            'type' => $type,
            'amount' => $this->faker->randomFloat(2, 10, 1000),
            'date' => $this->faker->dateTimeBetween('-6 months', 'now'),
            'description' => $this->faker->sentence,
            'user_id' => User::factory(),
            'account_id' => Account::factory(),
            'category_id' => function (array $attributes) use ($type) {
                return Category::factory()
                    ->create(['type' => $type, 'user_id' => $attributes['user_id'] ?? User::factory()])
                    ->id;
            },
        ];
    }

public function forUser($user): static
{
    return $this->state(fn (array $attributes) => [
        'user_id' => is_object($user) ? $user->id : $user,
        'account_id' => Account::factory()->forUser($user),
        'category_id' => Category::factory()->forUser($user),
    ]);
}

    public function expense(): static
    {
        return $this->state(fn (array $attributes) => [
            'type' => 'expense',
            'category_id' => Category::factory()
                ->expense()
                ->forUser($attributes['user_id'] ?? User::factory())
                ->create()
                ->id,
        ]);
    }

    public function income(): static
    {
        return $this->state(fn (array $attributes) => [
            'type' => 'income',
            'category_id' => Category::factory()
                ->income()
                ->forUser($attributes['user_id'] ?? User::factory())
                ->create()
                ->id,
        ]);
    }
}