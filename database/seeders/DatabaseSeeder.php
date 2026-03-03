<?php

namespace Database\Seeders;

use App\Models\City;
use App\Models\Cinema;
use App\Models\Hall;
use App\Models\Movie;
use App\Models\Role;
use App\Models\SeatType;
use App\Models\Seat;
use App\Models\Showtime;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Seed Roles
        $adminRole = Role::firstOrCreate(['name' => 'admin']);
        $stafRole = Role::firstOrCreate(['name' => 'staf']);
        $customerRole = Role::firstOrCreate(['name' => 'customer']);

        // 2. Seed User Admin
        User::firstOrCreate(
            ['email' => 'admin@gmail.com'],
            [
                'name' => 'Admin Cinema',
                'password' => Hash::make('password'),
                'role_id' => $adminRole->id,
                'phone' => '08123456789'
            ]
        );

        // 3. Seed User Staff
        User::firstOrCreate(
            ['email' => 'staf@gmail.com'],
            [
                'name' => 'Staff Cinema',
                'password' => Hash::make('password'),
                'role_id' => $stafRole->id,
                'phone' => '08123456780'
            ]
        );

        // 4. Seed User Customer
        User::firstOrCreate(
            ['email' => 'budi@gmail.com'],
            [
                'name' => 'Budi Sudarsono',
                'password' => Hash::make('password'),
                'role_id' => $customerRole->id,
                'phone' => '089988776655'
            ]
        );
    }
}
