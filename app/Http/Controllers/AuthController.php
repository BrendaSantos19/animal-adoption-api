<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Adopter;
use App\Models\Staff;
use App\Http\Responses\ApiResponse;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    // Criar conta de Adotante
    public function register(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|unique:adopters,email',
            'password' => 'required|string|min:6',
            'phone' => 'nullable|string|max:9'
        ]);

        $adopter = Adopter::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
            'phone' => $data['phone'] ?? null
        ]);

        $token = $adopter->createToken('adopter_token')->plainTextToken;

        return ApiResponse::success(['adopter' => $adopter, 'token' => $token], null, 201);
    }

    // Login Unificado (Tenta detetar se é Staff ou Adotante)
    public function login(Request $request)
    {
        $data = $request->validate([
            'email' => 'required|email',
            'password' => 'required'
        ]);

        // 1. Tentar encontrar no Staff
        $user = Staff::where('email', $data['email'])->first();
        $role = 'staff';

        // 2. Se não for staff, tentar encontrar nos Adotantes
        if (!$user) {
            $user = Adopter::where('email', $data['email'])->first();
            $role = 'adopter';
        }

        // 3. Validar credenciais
        if (!$user || !Hash::check($data['password'], $user->password)) {
            return ApiResponse::error(['message' => 'Credenciais inválidas'], 401);
        }

        // 4. Gerar Token usando a habilidade polimórfica do Sanctum
        $token = $user->createToken($role . '_token')->plainTextToken;

        return ApiResponse::success([
            'user' => $user,
            'role' => $role,
            'token' => $token
        ]);
    }

    public function me(Request $request)
    {
        return ApiResponse::success($request->user());
    }

    public function updateProfile(Request $request)
    {
        $user = $request->user(); // O Laravel já sabe quem é o utilizador pelo Guard

        // 1. Identificar o tipo de utilizador pelo nome da classe
        $isAdopter = $user instanceof \App\Models\Adopter;

        $rules = [
            'name'     => 'sometimes|string|max:255',
            'email'    => 'sometimes|email|unique:' . ($isAdopter ? 'adopters' : 'staff') . ',email,' . $user->id,
            'password' => 'sometimes|string|min:8',
        ];

        if ($isAdopter) {
            $rules['phone'] = 'sometimes|string|max:20';
        }

        $data = $request->validate($rules);

        if ($request->has('password')) {
            $data['password'] = Hash::make($request->password);
        }

        $user->update($data);

        return ApiResponse::success($user, 'Perfil atualizado com sucesso.');
    }

    public function logout(Request $request)
    {
        // Apaga o token atual que revogou a sessão
        $request->user()->currentAccessToken()->delete();
        return ApiResponse::success(['message' => 'Logout efetuado com sucesso']);
    }

    public function refresh(Request $request)
    {
        $user = $request->user();
        $user->currentAccessToken()->delete();
        
        // Identificação pelo tipo de classe
        $role = ($user instanceof \App\Models\Staff) ? 'staff' : 'adopter';
        $newToken = $user->createToken($role . '_token')->plainTextToken;

        return ApiResponse::success(['token' => $newToken]);
    }
}