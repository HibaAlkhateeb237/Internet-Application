<?php

namespace App\Http\Services;

use App\Http\Repositories\AdminRepository;
use App\Http\Responses\ApiResponse;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class AdminService
{
    protected $admins;

    public function __construct(AdminRepository $repository)
    {
        $this->admins = $repository;
    }

    public function register(array $data)
    {
        $admin = $this->admins->create([
            'government_agency_id'=> $data['government_agency_id'],
            'name'     => $data['name'],
            'email'    => $data['email'],
            'password' => bcrypt($data['password']),

        ]);

        //$admin->assignRole('employee');



        //$admin->assignRole('agency-employee');

        $admin->assignRole('employee');


        $token = $admin->createToken('MyApp', ['admin'])->accessToken;
        return compact('admin', 'token');
    }

    public function login(string $email, string $password)
    {
        $errors = [];
        $admin = $this->admins->findByEmail($email);

        if (!$admin) $errors['email'] = 'Email not found';
        if ($admin && !Hash::check($password, $admin->password)) $errors['password'] = 'Incorrect password';

        if (!empty($errors)) return ['errors' => $errors];

        $token = $admin->createToken('MyApp', ['admin'])->accessToken;
        return compact('admin', 'token');
    }




    //-------------------------------------------------------------------------------------------


    public function assignRole($request, $id)
    {
        $user = $this->admins->findById($id);

        if (!$user) {
            return ApiResponse::error("User not found", [], 404);
        }

        if (!Role::where('name', $request->role)->exists()) {
            return ApiResponse::error("Role does not exist", [], 404);
        }

        if ($user->hasRole($request->role)) {
            return ApiResponse::error("User already has this role", [], 400);
        }

        $user->assignRole($request->role);

        return ApiResponse::success("Role assigned successfully");
    }

    //------------------------------

    public function removeRole($request, $id)
    {
        $user = $this->admins->findById($id);

        if (!$user) {
            return ApiResponse::error("User not found", [], 404);
        }

        if (empty($request->role)) {
            return ApiResponse::error("Role is required", [], 422);
        }

        if (!$user->hasRole($request->role)) {
            return ApiResponse::error("User does not have this role", [], 400);
        }

        $user->removeRole($request->role);

        return ApiResponse::success("Role removed successfully");
    }










    public function listEmployees()
    {
        return ApiResponse::success("Users fetched successfully", $this->admins->getAllEmployees());
    }



    public function deleteEmployee($id)
    {


        if ($id==1) {
            return ApiResponse::error("Can not delete super admin account", [], 401);
        }

        else {

            $admin = $this->admins->findById($id);

            if (!$admin) {
                return ApiResponse::error("User not found", [], 404);
            }

            $this->admins->delete($admin);

            return ApiResponse::success("User deleted successfully");

        }
    }

//updateEmployee

    public function updateEmployee($request, $id)
    {


        if ($id==1) {
            return ApiResponse::error("Can not update super admin account", [], 401);
        }

        else {

            $admin = $this->admins->findById($id);

            if (!$admin) {
                return ApiResponse::error("User not found", [], 404);
            }

            $this->admins->update($admin, $request->only(['name', 'email']));

            return ApiResponse::success("User updated successfully", $admin);


        }

    }



    public function getEmployee($id)
    {
        if ($id==1) {
            return ApiResponse::error("Can not fetch super admin account", [], 401);
        }

       $admin= $this->admins->findById($id);

        if (!$admin) {
            return ApiResponse::error("User not found", [], 404);
        }

        return ApiResponse::success("User fetched successfully", $admin);
    }















}
