<?php

namespace Database\Seeders;

use App\Models\MediaCategory;
use App\Models\MediaSubCategory;
use Illuminate\Database\Seeder;

class MediaSubCategorySeeder extends Seeder
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
        $images = [
            'Entrance',
            'Lobby',
            'Double Room',
            'Triple Room',
            'Restaurant',
            'Swimming Pool Side',
            'Banquet hall',
            'Garden Lawns',
            'Beach or Other Lawn',
            'Top View',
            'Decor Picture Of Wedding',
        ];

        $video = [
            'Wedding Video'
        ];

        $pdf = [
            'Menu Matrix',
            'Buffet Menus',
            'Snacks Menu',
            'Live Counters Menu',
            'Alcohol Menu'
        ];

        foreach($data as $val){
            $media = MediaCategory::where('name',$val)->first();
            if($val == 'Images'){
               foreach( $images as $val){
                   MediaSubCategory::create([
                       'media_category_id' =>  $media->id,
                       'name' => $val,
                       'description' => $val,
                       'status' => 1
                   ]);
               }
            }
            if($val == 'Video'){
                foreach( $video as $val){
                    MediaSubCategory::create([
                        'media_category_id' =>  $media->id,
                        'name' => $val,
                        'description' => $val,
                        'status' => 1
                    ]);
                }
            }
            if($val == 'PDF'){
                foreach( $pdf as $val){
                    MediaSubCategory::create([
                        'media_category_id' =>  $media->id,
                        'name' => $val,
                        'description' => $val,
                        'status' => 1
                    ]);
                }
            }

        }
    }
}
