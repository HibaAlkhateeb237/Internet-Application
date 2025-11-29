<?php

namespace App\Http\Controllers;

use App\Models\Complaint;
use Illuminate\Http\Request;

class AdminComplaintController extends Controller
{
    public function index()
    {
        $complaints = Complaint::with(['user','agency','images','history.admin'])
            ->orderBy('created_at','desc')
            ->paginate(30);

        return response()->json($complaints);
    }

    public function show($id)
    {
        return Complaint::with(['user','agency','images','history.admin'])
            ->findOrFail($id);
    }
}

