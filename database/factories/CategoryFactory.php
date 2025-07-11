<?php

namespace Database\Factories;

use App\Models\Category;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class CategoryFactory extends Factory
{
    protected $model = Category::class;

    public function definition(): array
    {
        $type = $this->faker->randomElement(['expense', 'income']);
        
        return [
            'name' => $type === 'income' 
                ? $this->faker->randomElement(['Salario', 'Ventas', 'Inversiones', 'Bonos'])
                : $this->faker->randomElement(['Comida', 'Transporte', 'Entretenimiento', 'Servicios']),
            'type' => $type,
            'user_id' => User::factory(),
        ];
    }

    public function expense(): static
    {
        return $this->state(fn (array $attributes) => [
            'type' => 'expense',
            'name' => $this->faker->randomElement(['Comida', 'Transporte', 'Entretenimiento', 'Servicios']),
        ]);
    }

    public function income(): static
    {
        return $this->state(fn (array $attributes) => [
            'type' => 'income',
            'name' => $this->faker->randomElement(['Salario', 'Ventas', 'Inversiones', 'Bonos']),
        ]);
    }

    public function forUser($user): static
    {
        // Añadir verificación para manejar tanto objetos como IDs
        return $this->state(fn (array $attributes) => [
            'user_id' => is_object($user) ? $user->id : $user,
        ]);
    }
}