<?php

namespace App\Http\Repositories;



use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RoleRepository
{

    public function __construct()
    {

    }


    public function all()
    {
        return Role::all();
    }


    public function find($id)
    {
        return Role::with('permissions')->findOrFail($id);
    }




    public function create(array $data): Role
    {
        $role = Role::create([
            'name'       => $data['name'],
            'guard_name' => 'admin-api',
        ]);

        if (!empty($data['permissions'])) {
            $role->syncPermissions($data['permissions']);
        }

        return $role->load('permissions');
    }



    public function deleteRole($role)
    {
        $role->delete();
    }



    public function getAllPermissions()
    {
        return Permission::all();
    }


    public function update($id, array $data)
    {
        $role = Role::findOrFail($id);
        $role->update($data);
        return $role;
    }



    public function updateRole($role, $data)
    {
        if (!empty($data['name'])) {
            $role->name = $data['name'];
            $role->save();
        }
        if (!empty($data['permissions'])) {
            $role->syncPermissions($data['permissions']);
        }
        return $role->load('permissions');
    }




}
