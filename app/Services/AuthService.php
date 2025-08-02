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

    /**
    * Enregistre un nouvel utilisateur après vérification de l'email.
    * Crée ou récupère le rôle associé, génère un token d'accès.
    *
    * @param mixed $request Données de la requête contenant nom, email, mot de passe, rôle.
    * @return JsonResponse Réponse JSON avec l'utilisateur créé et son token ou message d'erreur si email existant.
    */
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
    * Authentifie un utilisateur via ses identifiants.
    * Génère et retourne un token d'accès en cas de succès.
    *
    * @param mixed $request Données de la requête avec email et mot de passe.
    * @return JsonResponse Réponse JSON avec message, utilisateur et token ou erreur d'identifiants.
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
    * Déconnecte l'utilisateur actuel en révoquant son token d'accès.
    *
    * @param mixed $request Requête HTTP avec utilisateur authentifié.
    * @return JsonResponse Réponse JSON confirmant la déconnexion ou erreur si utilisateur non connecté.
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
