<?php

namespace Database\Factories;

use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Factories\Factory;

class UserFactory extends Factory
{
    protected static ?string $password;

    public function definition(): array
    {
        return [
            'name' => $this->faker->name(),
            'email' => $this->faker->unique()->safeEmail(),
            'password' => static::$password ??= Hash::make('password'),
            'remember_token' => Str::random(10),
        ];
    }

    public function engelPalacios(): static
    {
        return $this->state(fn (array $attributes) => [
            'name' => 'Engel Palacios',
            'email' => 'ejpalacios29@gmail.com',
            'password' => Hash::make('HalaMadrid14'),
        ]);
    }
}