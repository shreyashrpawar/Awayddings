<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $user = User::create([
            'name' => 'ADMIN',
            'email' => 'admin@gmail.com',
            'phone' => '1234567890',
            'password' => bcrypt('1234567890'),
            'status' => 1,
        ]);
        $user->assignRole('admin');


    }
}
