<?php

namespace Database\Seeders;

use App\Classes\StatusEnum;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $adminEmail = 'admin@admin.com';
        User::firstOrCreate(
            ['email' => $adminEmail],
            [
                'name' => 'Admin',
                'email' => $adminEmail,
                'password' => Hash::make('password'),
                'phone_number_1' => '03245424344',
                'phone_number_2' => '03534342333',
                'address' => 'uk, street 241'
            ]
        )->assignRole('admin');

        $dealerEmail = 'dealer@dealer.com';
        User::firstOrCreate(
            ['email' => $dealerEmail],
            [
                'name' => 'Dealer',
                'email' => 'dealer@dealer.com',
                'password' => Hash::make('password'),
                'phone_number_1' => '03442343233',
                'phone_number_2' => '03213434422',
                'address' => 'uk, street 839'
            ]
        )->assignRole('dealer');
    }
}
