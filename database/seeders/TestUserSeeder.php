<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class TestUserSeeder extends Seeder
{
    public const TEST_USER_EMAIL = 'prueba@ejemplo.com';

    /**
     * Crea o actualiza el usuario de prueba (sin permisos de administrador).
     */
    public function run(): void
    {
        User::updateOrCreate(
            ['email' => self::TEST_USER_EMAIL],
            [
                'name' => 'Usuario de prueba',
                'password' => Hash::make('password'),
                'is_admin' => false,
                'email_verified_at' => now(),
            ]
        );
    }
}
