<?php

namespace App\Http\Repositories;

use App\Models\Admin;

class AdminRepository
{
    public function create(array $data)
    {
        return Admin::create($data);
    }

    public function findByEmail(string $email)
    {
        return Admin::where('email', $email)->first();
    }


    public function findById($id)
    {
        return Admin::with(['roles', 'permissions'])->find($id);

    }



    public function getAllEmployees()
    {
        return Admin::with('roles')
            ->where('id', '!=', 1)
            ->paginate(10);
    }



    public function delete(Admin $admin)
    {
        return $admin->delete();
    }


    public function update(Admin $admin, array $data)
    {
        return $admin->update($data);
    }






}
