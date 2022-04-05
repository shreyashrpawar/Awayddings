<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RolePermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $data = [
                    [
                        'role' => 'admin',
                        'permission' => 'property create'
                    ],
                    [
                        'role' => 'admin',
                        'permission' => 'property show'
                    ],
                    [
                        'role' => 'admin',
                        'permission' => 'property update'
                    ],
                    [
                        'role' => 'admin',
                        'permission' => 'property rate'
                    ],
                    [
                        'role' => 'vendor',
                        'permission' => 'property create'
                    ],
                    [
                        'role' => 'vendor',
                        'permission' => 'property show'
                    ],
                    [
                        'role' => 'vendor',
                        'permission' => 'property update'
                    ],
                    [
                        'role' => 'vendor',
                        'permission' => 'property rate'
                    ],
                    [
                        'role' => 'admin',
                        'permission' => 'property_vendor create'
                    ],
                    [
                        'role' => 'admin',
                        'permission' => 'property_vendor show'
                    ],
                    [
                        'role' => 'admin',
                        'permission' => 'property_vendor update'
                    ],
                    [
                        'role' => 'admin',
                        'permission' => 'property_vendor associate'
                    ],
                    [
                        'role' => 'admin',
                        'permission' => 'user create'
                    ],
                    [
                        'role' => 'admin',
                        'permission' =>  'user show',
                    ],
                    [
                        'role' => 'admin',
                        'permission' =>  'user update',
                    ],
                    [
                        'role' => 'admin',
                        'permission' =>  'location create',
                    ],
                    [
                        'role' => 'admin',
                        'permission' =>'location show',
                    ],
                    [
                        'role' => 'admin',
                        'permission' =>  'location update',
                    ],
                    [
                        'role' => 'admin',
                        'permission' =>  'amenities create',
                    ],
                    [
                        'role' => 'admin',
                        'permission' =>   'amenities show',
                    ],
                    [
                        'role' => 'admin',
                        'permission' =>   'amenities update',
                    ],
                    [
                        'role' => 'admin',
                        'permission' =>  'room_inclusion create',
                    ],
                    [
                        'role' => 'admin',
                        'permission' => 'room_inclusion show',
                    ],
                    [
                        'role' => 'admin',
                        'permission' =>   'room_inclusion update',
                    ],
                    [
                        'role' => 'admin',
                        'permission' =>  'property status',
                    ]
        ];
        foreach($data as $val){
            $role       = Role::where('name',$val['role'])->first();
            $role->givePermissionTo($val['permission']);
        }
    }
}
