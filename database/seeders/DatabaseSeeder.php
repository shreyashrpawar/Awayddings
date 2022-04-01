<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // \App\Models\User::factory(10)->create();
        $this->call([
            RoleSeeder::class,
            UserSeeder::class,
            PropertyChargableTypeSeeder::class,
            MediaCategorySeeder::class,
            MediaSubCategorySeeder::class,
            AmenitiesSeeder::class,
            RoomInclusionSeeder::class,
            LocationSeeder::class,
            PermissionSeeder::class,
            RolePermissionSeeder::class
        ]);
    }
}
