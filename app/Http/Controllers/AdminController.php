<?php

namespace App\Http\Controllers;

use App\Http\Requests\UserAdminAssignRoleRequest;
use App\Http\Services\AdminService;
use Illuminate\Http\Request;

class AdminController extends Controller
{

    private $service;

    public function __construct(AdminService $service) {
        $this->service = $service;
    }


    public function assignRole(UserAdminAssignRoleRequest $request, $id)
    {
        return $this->service->assignRole($request, $id);
    }

    public function removeRole(UserAdminAssignRoleRequest $request, $id)
    {
        return $this->service->removeRole($request, $id);
    }







}
