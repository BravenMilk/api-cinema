<?php

namespace App\Services;

use App\Repositories\UserRepository;
use Illuminate\Support\Facades\Hash;

class AuthService extends BaseService
{
    protected $userRepo;

    public function __construct(UserRepository $userRepo)
    {
        $this->userRepo = $userRepo;
    }

    public function login(array $credentials)
    {
        $user = $this->userRepo->findByEmail($credentials['email']);

        if (!$user || !Hash::check($credentials['password'], $user->password)) {
            return $this->response(false, 'Email atau password salah', null, 401);
        }

        $token = $user->createToken('auth_token')->plainTextToken;

        return $this->response(true, 'Login berhasil', [
            'user' => $user,
            'token' => $token,
            'role' => $user->role->name
        ]);
    }

    public function logout($user)
    {
        $user->currentAccessToken()->delete();

        return $this->response(true, 'Logout berhasil, token telah dihapus', null, 200);
    }

    public function register(array $data)
    {
        $role = \App\Models\Role::where('name', 'customer')->first();

        if (!$role) {
             $role = \App\Models\Role::create(['name' => 'customer']);
        }

        $user = $this->userRepo->create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
            'role_id' => $role->id,
            'phone' => $data['phone'] ?? null,
        ]);

        $token = $user->createToken('auth_token')->plainTextToken;

        return $this->response(true, 'Registrasi berhasil', [
            'user' => $user,
            'token' => $token,
            'role' => 'customer'
        ], 201);
    }

    public function updateProfile($user, array $data)
    {
        $userData = [
            'name' => $data['name'],
            'email' => $data['email'],
            'phone' => $data['phone'] ?? $user->phone,
        ];

        if (!empty($data['password'])) {
            $userData['password'] = Hash::make($data['password']);
        }

        $user->update($userData);

        return $this->response(true, 'Profil berhasil diperbarui', $user);
    }
}
