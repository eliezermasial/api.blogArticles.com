<?php

namespace App\Services;

use App\Models\Role;
use App\Models\User;
use App\Repositories\UserRepository;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;


class AuthService
{
    protected $userRepo;
    /**
     * Create a new class instance.
     */
    public function __construct(UserRepository $userRepo)
    {
        $this->userRepo = $userRepo;
    }

    public function register($request)
    {
        //vérification si un utilisateur avec cet email existe
        $user= $this->userRepo->findByEmail($request->email);
        
        if ($user) {
            return response()->json([
                "message" => "email existant"
            ],409);
        }

        // Vérifie si le rôle existe, sinon le crée
        $role = Role::firstOrCreate(
            ['name' => $request->role_name ?? 'user']
        );
        
        $user = $this->userRepo->createUser([
            'name' => $request->name,
            'email' => $request->email,
            'role_id' => $role->id,
            'password' => Hash::make($request->password)
        ]);

        $token = $user->createToken('access_token')->accessToken;

        return response()->json(['user' => $user, 'token' => $token], 201);
    }

}
