<?php

namespace App\Services;

use App\Models\Role;
use App\Models\User;
use Illuminate\Http\JsonResponse;
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
        $role = $this->userRepo->getOrCreateRole($request->role_name);
        
        $user = $this->userRepo->create([
            'name' => $request->name,
            'email' => $request->email,
            'role_id' => $role->id,
            'password' => Hash::make($request->password)
        ]);

        $token = $user->createToken('access_token')->accessToken;

        return response()->json(['user' => $user, 'token' => $token], 201);
    }

    /**
     * fonction qui connecte un user en verifiant ces infos
     * et lui genere un token de connexion
     */
    public function login($request): JsonResponse
    {
        
        if (!Auth::attempt($request->only('email', 'password'))) {

            return response()->json(['error' => 'identifiant invalide'], 401);
        }

        $user = $this->userRepo->findByEmail($request->email);

        $token = $user->createToken('access_token')->accessToken;

        return response()->json(['message'=>'connexion reussie','user' => $user,'token' => $token], 200);
    }

    /**
     * function de deconnexion
     * il ferme le token actuel de user
     */
    public function logout($request)
    {
        $user = Auth::user();

        if($user) {
            $request->user()->token()->revoke();
            return response()->json(['message'=>'deconnexion reussie'], 200);
        }

         return response()->json(['error' => 'Aucun utilisateur connecté'], 401);
    }
}
