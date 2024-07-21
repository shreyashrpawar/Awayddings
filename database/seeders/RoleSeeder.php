<?php

namespace Database\Seeders;

use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $roles = ['admin','user','vendor'];
        foreach($roles as $role)
        {
            DB::table('roles')->insert([
                'name' => $role,
                'guard_name' => 'web'
            ]);
        }

    }
}
