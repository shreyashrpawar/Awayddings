<?php

namespace App\Http\Controllers;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use App\Models\Page;


use Illuminate\Http\Request;

class PermissionsController extends Controller
{
    public function index(Request $request)
    {
        $permissions = Permission::all();
        $roles = Role::all();
        $rolePermissions = [];
        $pages = Page::orderBy('display_order')->get();
        $selectedPage = $request->input('page', 'dashboard');
       

      
        foreach ($roles as $role) {
            $rolePermissions[$role->id] = $role->permissions->pluck('name')->toArray();
        }
        
        return view('app.permissions.index', compact('permissions'),compact('roles','pages','selectedPage','rolePermissions'));
    }

    public function createRole(Request $request)
    {
        $request->validate([
            'newRole' => 'required|string|unique:roles,name|max:255',
        ]);
        dd('here');
        Role::create(['name' => $request->newRole]);

        return redirect()->route('permissions.index')
            ->with('success', 'Role created successfully.');

        // Redirect back with a success message
        return redirect()->back()->with('success', 'Role created successfully');
    }
    public function updateRole(Request $request)
    {
        $request->validate([
            'existingRole' => 'required|exists:roles,id',
            'editedRoleName' => 'required|string|max:255',
        ]);
        
        $role = Role::findOrFail($request->existingRole);
        $role->name = $request->editedRoleName;
        $role->save();
        return redirect()->back()->with('success', 'Role updated successfully.');
    }

    public function updatePermissions(Request $request)
    {
    $page = $request->input('page');
    $permissions = json_decode($request->input('permissions_updtd'));

        if ($permissions) {
        foreach ($permissions as $roleId => $permission) {
            $role = Role::find($roleId);
            foreach ($permission as $action => $value) {
                $permissionName = "{$page}-{$action}";
                
                if ($value === 'on') {
                $role->givePermissionTo($permissionName);
            } else {
                $role->revokePermissionTo($permissionName);
               
            }
            }
        }
    }
    $request->session()->flash('success', 'Permissions updated successfully.');
    return redirect()->route('permissions.index', compact('page'));
    }

}
