<?php

namespace App\Http\Services;

use App\Http\Repositories\AdminRepository;
use Illuminate\Support\Facades\Hash;

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
}
