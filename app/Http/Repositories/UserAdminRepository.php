<?php

namespace App\Http\Repositories;

use App\Models\Admin;
use App\Models\User;

class
UserAdminRepository
{

    public function __construct()
    {

    }

    public function getAllUsers()
    {
        return User::with('roles')
            ->paginate(10);
    }



    public function findById($id)
    {
        return User::with(['roles', 'permissions'])->find($id);
    }


    public function create(array $data)
    {
        return User::create($data);
    }

    public function update(User $user, array $data)
    {
        return $user->update($data);
    }

    public function delete(User $user)
    {
        return $user->delete();
    }










}
