<?php

namespace Database\Seeders;

use App\Models\HotelFacility;
use Illuminate\Database\Seeder;

class AmenitiesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $data = [
            'AC',
            'Swimming Pool',
            'WIFI',
            'TV',
            'Water Bottles'
        ];
        foreach($data as $val){
            $media = HotelFacility::create([
                'name' => $val,
                'status' => 1,
            ]);
        }
    }
}
