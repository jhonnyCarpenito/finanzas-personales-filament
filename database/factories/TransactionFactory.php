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

    private static array $incomeConcepts = [
        'Nómina mensual', 'Pago por proyecto freelance', 'Dividendos inversiones',
        'Reembolso gastos', 'Venta de artículo', 'Bono por desempeño',
        'Alquiler recibido', 'Intereses cuenta ahorro', 'Trabajo extra',
        'Consulta freelance', 'Venta marketplace', 'Devolución impuestos',
    ];

    private static array $expenseConcepts = [
        'Supermercado semanal', 'Combustible coche', 'Factura luz',
        'Factura agua', 'Internet y móvil', 'Netflix y streaming',
        'Restaurante fin de semana', 'Farmacia', 'Gimnasio',
        'Compras online', 'Reparación electrodoméstico', 'Regalo cumpleaños',
        'Dentista', 'Ocio cine', 'Transporte público', 'Seguro hogar',
    ];

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
        $concept = $type === 'income'
            ? fake()->randomElement(self::$incomeConcepts).(fake()->boolean(40) ? ' '.fake()->monthName() : '')
            : fake()->randomElement(self::$expenseConcepts).(fake()->boolean(30) ? ' '.fake()->company() : '');

        return [
            'user_id' => User::factory(),
            'type' => $type,
            'amount' => $amount,
            'concept' => $concept,
            'date' => fake()->dateTimeBetween('-1 year', 'now'),
        ];
    }

    public function income(): static
    {
        return $this->state(fn (array $attributes) => [
            'type' => 'income',
            'amount' => fake()->randomFloat(2, 100, 5000),
            'concept' => fake()->randomElement(self::$incomeConcepts).(fake()->boolean(40) ? ' '.fake()->monthName() : ''),
        ]);
    }

    public function expense(): static
    {
        return $this->state(fn (array $attributes) => [
            'type' => 'expense',
            'amount' => fake()->randomFloat(2, 10, 800),
            'concept' => fake()->randomElement(self::$expenseConcepts).(fake()->boolean(30) ? ' '.fake()->company() : ''),
        ]);
    }
}
