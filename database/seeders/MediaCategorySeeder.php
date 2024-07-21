<?php

namespace Database\Seeders;

use App\Models\MediaCategory;
use Illuminate\Database\Seeder;

class MediaCategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $data = [
            'Images',
            'Video',
            'PDF'
        ];
        foreach($data as $val){
            $media = MediaCategory::create([
                'name' => $val,
                'description' => $val,
                'status' => 1,
            ]);
        }
    }
}
