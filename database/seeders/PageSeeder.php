<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\Page;

class PageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $pages = [
            ['name' => 'dashboard', 'display_order' => 1],
            ['name' => 'property', 'display_order' => 2],
            ['name' => 'property-vendors', 'display_order' => 3],
            ['name' => 'Venue/Resort-Booking-Pre-Bookings', 'display_order' => 4],
            ['name' => 'Venue/Resort-Booking-Bookings', 'display_order' => 5],
            ['name' => 'Wedding-Planning-Bookings-Pre-Bookings', 'display_order' => 6],
            ['name' => 'Wedding-Planning-Bookings-Bookings', 'display_order' => 7],
            ['name' => 'Registered-Users', 'display_order' => 8],
            ['name' => 'Event-Management-Events', 'display_order' => 9],
            ['name' => 'Event-Management-Artists', 'display_order' => 10],
            ['name' => 'Event-Management-Artist-Person', 'display_order' => 11],
            ['name' => 'Event-Management-Decorations', 'display_order' => 12],
            ['name' => 'Event-Management-Facility', 'display_order' => 13],
            ['name' => 'Ads-Leads', 'display_order' => 14],
            ['name' => 'Lost-Leads', 'display_order' => 15],
            ['name' => 'Settings-Locations', 'display_order' => 16],
            ['name' => 'Settings-Amenities', 'display_order' => 17],
            ['name' => 'Settings-Room-Inclusion', 'display_order' => 18],
           
        ];
        foreach ($pages as $page) {
            Page::create($page);
        }
    }
}
