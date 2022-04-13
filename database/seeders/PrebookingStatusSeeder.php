<?php

namespace Database\Seeders;

use App\Models\PreBookingSummaryStatus;
use Illuminate\Database\Seeder;

class PrebookingStatusSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $data = [
            'pending',
            'approved',
            'canceled',
            'rejected'
        ];
        foreach($data as $val){
            $media = PreBookingSummaryStatus::create([
                'name' => $val,
            ]);
        }
    }
}
