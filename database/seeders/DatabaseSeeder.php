<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        // User::factory()->create([
        //     'name' => 'Admin',
        //     'email' => 'admin@admin.com',
        //     'password' => Hash::make('123456'),
        //     'user_type' => User::USER_TYPE_SUPER_ADMIN,
        //     'is_active' => User::USER_IS_ACTIVE,
        // ]);
        $this->call(RolePermissionSeeder::class);
        $this->call(LanguageSeeder::class);
    }
}
