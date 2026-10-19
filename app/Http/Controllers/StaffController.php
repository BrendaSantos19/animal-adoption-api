<?php

namespace App\Http\Controllers;

use App\Models\Staff;
use Illuminate\Http\Request;
use App\Http\Responses\ApiResponse;

class StaffController extends Controller
{
    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:staff,email',
            'password' => 'required|min:6'
        ]);

        $data['password'] = bcrypt($data['password']);

        $staff = Staff::create($data);

        return ApiResponse::success([
            'message' => 'Staff criado com sucesso',
            'staff' => $staff
        ], null, 201);
    }
}
