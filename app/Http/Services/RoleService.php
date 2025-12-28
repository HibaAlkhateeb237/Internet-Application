<?php

namespace App\Http\Services;

use App\Http\Repositories\RoleRepository;
use App\Http\Responses\ApiResponse;
use Spatie\Permission\Models\Role;

class RoleService
{

    private $repo;




    public function __construct(RoleRepository $repo)
    {
        $this->repo = $repo;
    }



    public function getAllRoles()
    {
        return $this->repo->all();
    }


    public function getRoleById($id)
    {
        return $this->repo->find($id);
    }


    public function createRole(array $data)
    {
        return $this->repo->create($data);
    }



    public function deleteRole($role)
    {
        $this->repo->deleteRole($role);
    }

    public function listPermissions()
    {
        return $this->repo->getAllPermissions();
    }


    public function updateRole($id, array $data)
    {
        return $this->repo->update($id, $data);
    }



    public function updateRolePermissions($role, array $data)
    {
        return $this->repo->updateRole($role, $data);
    }







}
