<?php

namespace App\Http\Controllers;

use App\Http\Requests\RoleRequest;
use App\Http\Requests\UpdateRolePermissionsRequest;
use App\Http\Requests\UpdateRoleRequest;
use App\Http\Responses\ApiResponse;
use App\Http\Services\RoleService;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;

class RoleController extends Controller
{

    private $service;

    public function __construct(RoleService $service) {
        $this->service = $service;
    }


    public function index()
    {

        return ApiResponse::success('Roles fetched successfully', $this->service->getAllRoles());
    }



    public function show($id)
    {
        $role = $this->service->getRoleById($id);
        if (!$role) return ApiResponse::error('Role not found', null, 404);
        return ApiResponse::success('Role fetched successfully', $role);
    }


    public function store(RoleRequest $request)
    {
        $role = $this->service->createRole($request->validated());
        return ApiResponse::success('Role created successfully', $role);
    }




    public function destroy($id)
    {
        $role = Role::find($id);
        // dd($role->users());
        if (!$role) return ApiResponse::error('Role not found', null, 404);
        if ($role->name === 'super_admin') return ApiResponse::error('Admin role cannot be deleted', null, 403);
        if ($role->users()->count() > 0)
            return ApiResponse::error('Role is assigned to users', null, 403);

        $this->service->deleteRole($role);
        return ApiResponse::success('Role deleted successfully');
    }



    public function indexPermission()
    {
        return ApiResponse::success('Permissions fetched', $this->service->listPermissions());
    }




    public function update(UpdateRoleRequest $request, $id)
    {

        return ApiResponse::success('Permissions fetched', $this->service->updateRole($id, $request->validated()));
    }




    public function updatePermissions(UpdateRolePermissionsRequest $request, $id)
    {

        $role = Role::find($id);
        if (!$role) return ApiResponse::error('Role not found', null, 404);
        if ($role->name === 'super_admin') return ApiResponse::error('Admin role cannot be modified.', null, 403);


        $role = $this->service->updateRolePermissions($role, $request->validated());
        return ApiResponse::success('Role updated successfully', $role);



    }







}
