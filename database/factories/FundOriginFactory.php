<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\FundOrigin;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\FundOrigin>
 */
class FundOriginFactory extends Factory
{
    protected $model = FundOrigin::class;

    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'name' => fake()->randomElement([
                'Banesco Panamá', 'MetaMask', 'Efectivo', 'Binance',
                'Cuenta de ahorros', 'PayPal', 'Zelle', 'Transferencia',
            ]) . ' ' . fake()->unique()->numberBetween(1, 9999),
            'amount' => fake()->randomFloat(2, 100, 10000),
            'color' => fake()->randomElement(['success', 'info', 'warning', 'danger', 'gray']),
            'order' => fake()->numberBetween(1, 10),
        ];
    }
}
