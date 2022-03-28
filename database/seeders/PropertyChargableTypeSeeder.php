<?php

namespace Database\Seeders;

use App\Models\HotelChargableType;
use App\Models\User;
use Illuminate\Database\Seeder;

class PropertyChargableTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $data = [
            'Double Occupancy Room',
            'Triple Occupancy Room',
            'Beach Lawn',
            'Garden Hall',
            'Banquet Hall'
            ];
        foreach($data as $val){
            HotelChargableType::create([
                'name' => $val,
                'status' => 1,
            ]);
        }

    }
}
