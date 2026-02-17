<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\Tag;
use Illuminate\Database\Seeder;

class TagSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $tags = [
            // Tags Globales de Ingresos
            ['name' => 'Salario', 'color' => 'success', 'user_id' => null],
            ['name' => 'Freelance', 'color' => 'info', 'user_id' => null],
            ['name' => 'Inversiones', 'color' => 'success', 'user_id' => null],

            // Tags Globales de Egresos
            ['name' => 'Vivienda', 'color' => 'warning', 'user_id' => null],
            ['name' => 'Comida', 'color' => 'danger', 'user_id' => null],
            ['name' => 'Transporte', 'color' => 'info', 'user_id' => null],
            ['name' => 'Servicios', 'color' => 'warning', 'user_id' => null],
            ['name' => 'Ocio', 'color' => 'gray', 'user_id' => null],
            ['name' => 'Salud', 'color' => 'danger', 'user_id' => null],
        ];

        foreach ($tags as $tag) {
            Tag::firstOrCreate(
                ['name' => $tag['name'], 'user_id' => $tag['user_id']],
                $tag
            );
        }
    }
}
