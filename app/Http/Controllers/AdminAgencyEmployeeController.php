<?php

namespace App\Http\Controllers;

use App\Models\Admin;
use Illuminate\Http\Request;

class AdminAgencyEmployeeController extends Controller
{
    //
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'email' => 'required|email|unique:admins',
            'password' => 'required|min:6',
            'government_agency_id' => 'required|exists:government_agencies,id',
        ]);

        $admin = Admin::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt($request->password),
            'government_agency_id' => $request->government_agency_id,
            'role' => 'agency_admin',
        ]);

        return response()->json($admin);
    }

}
