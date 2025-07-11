<?php

namespace Database\Factories;

use App\Models\Budget;
use App\Models\Category;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class BudgetFactory extends Factory
{
    protected $model = Budget::class;

    public function definition(): array
    {
        return [
            'amount' => $this->faker->randomFloat(2, 100, 5000),
            'period' => $this->faker->randomElement(['monthly', 'weekly', 'yearly']),
            'category_id' => Category::factory(),
            'user_id' => User::factory(),
        ];
    }

    // Estado para presupuestos mensuales
    public function monthly()
    {
        return $this->state(function (array $attributes) {
            return [
                'period' => 'monthly',
                'amount' => $this->faker->numberBetween(500, 3000)
            ];
        });
    }
}