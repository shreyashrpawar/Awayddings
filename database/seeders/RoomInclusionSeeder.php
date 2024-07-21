<?php

namespace Database\Seeders;

use App\Models\HotelFacility;
use App\Models\RoomInclusion;
use Illuminate\Database\Seeder;

class RoomInclusionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $data = [
            'Breakfast',
            'Lunch',
            'Dinner',
            'Airport Transfer',
            'Taxes',
        ];
        foreach($data as $val){
            $media = RoomInclusion::create([
                'name' => $val,
                'status' => 1,
            ]);
        }
    }
}
