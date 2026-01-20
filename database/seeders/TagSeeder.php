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
            // Tags de Ingresos
            ['name' => 'Salario', 'color' => 'success'],
            ['name' => 'Freelance', 'color' => 'info'],
            ['name' => 'Inversiones', 'color' => 'success'],

            // Tags de Egresos
            ['name' => 'Vivienda', 'color' => 'warning'],
            ['name' => 'Comida', 'color' => 'danger'],
            ['name' => 'Transporte', 'color' => 'info'],
            ['name' => 'Servicios', 'color' => 'warning'],
            ['name' => 'Ocio', 'color' => 'gray'],
            ['name' => 'Salud', 'color' => 'danger'],
        ];

        foreach ($tags as $tag) {
            Tag::create($tag);
        }
    }
}
