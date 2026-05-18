<?php

namespace Database\Seeders;

use App\Models\Admin;
use Illuminate\Database\Seeder;

class AdminSeeder extends Seeder
{
    public function run(): void
    {
        Admin::updateOrCreate(
            ['email' => 'admin@arsibluebeach.com'],
            [
                'name' => 'Yönetici',
                'password' => env('ADMIN_INITIAL_PASSWORD', 'changeme'),
            ]
        );

        Admin::updateOrCreate(
            ['email' => 'alios@arsibluebeach.com'],
            [
                'name' => 'Alios',
                'password' => 'alios123456',
            ]
        );
    }
}
