<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use App\Models\Page;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // $data = [
        //     'property create',
        //     'property show',
        //     'property update',
        //     'property rate',
        //     'property status',
        //     'property_vendor show',
        //     'property_vendor create',
        //     'property_vendor update',
        //     'property_vendor associate',
        //     'user create',
        //     'user show',
        //     'user update',
        //     'location create',
        //     'location show',
        //     'location update',
        //     'amenities create',
        //     'amenities show',
        //     'amenities update',
        //     'room_inclusion create',
        //     'room_inclusion show',
        //     'room_inclusion update',
        //     'pre-booking show',
        //     'pre-booking update',
        //     'booking show',
        //     'booking update',
        //     'dashboard read',
        // ];
        // foreach($data as $val){
        //     $media = Permission::firstOrNew([
        //         'name' => $val,
        //         'guard_name' => 'web'
        //     ]);

        // if (!$media->exists) {
        //         $media->save();
        //     }
        // }
        $pageNames = Page::distinct()->pluck('name');

        foreach ($pageNames as $pageName) {
            $permissions = [
                $pageName . '-read',
                $pageName . '-create',
                $pageName . '-update',
                $pageName . '-delete',
            ];
    
        $existingPermissions = Permission::whereIn('name', $permissions)->count();
        if ($existingPermissions === 0) {
            foreach ($permissions as $permission) {
                $media = Permission::firstOrNew([
                    'name' => $permission,
                    'guard_name' => 'web'
                ]);

                if (!$media->exists) {
                    $media->save();
                }
            }
        }

    }
}
}
