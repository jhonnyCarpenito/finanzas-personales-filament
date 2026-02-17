<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\Transaction;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Transaction>
 */
class TransactionFactory extends Factory
{
    protected $model = Transaction::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $type = fake()->randomElement(['income', 'expense']);
        $amount = $type === 'income'
            ? fake()->randomFloat(2, 100, 5000)
            : fake()->randomFloat(2, 10, 800);

        return [
            'user_id' => User::factory(),
            'type' => $type,
            'amount' => $amount,
            'concept' => fake()->sentence(4),
            'date' => fake()->dateTimeBetween('-1 year', 'now'),
        ];
    }

    public function income(): static
    {
        return $this->state(fn (array $attributes) => [
            'type' => 'income',
            'amount' => fake()->randomFloat(2, 100, 5000),
        ]);
    }

    public function expense(): static
    {
        return $this->state(fn (array $attributes) => [
            'type' => 'expense',
            'amount' => fake()->randomFloat(2, 10, 800),
        ]);
    }
}
