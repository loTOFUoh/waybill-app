<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Создаем администратора
        \App\Models\User::factory()->create([
            'name' => 'Admin User',
            'email' => 'admin@system.local',
            'password' => bcrypt('password'), // пароль по умолчанию
            'role' => 'admin',
        ]);

        // Создаем тестового водителя
        \App\Models\Driver::create([
            'full_name' => 'Иванов Иван Иванович',
            'license_number' => '99 АВ 123456',
            'is_active' => true,
        ]);

        // Создаем тестовую машину
        \App\Models\Vehicle::create([
            'model' => 'КАМАЗ 5490',
            'plate_number' => 'А123АА 123',
            'fuel_type' => 'Дизель',
            'base_consumption' => 29.5, // 29.5 литров на 100 км
        ]);
    }
}
