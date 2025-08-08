<?php

namespace App\Services;

use App\Traits\ApiResponse;
use Illuminate\Http\JsonResponse;
use App\Repositories\UserRepository;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;


class AuthService
{
    use ApiResponse;

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
        try {
            //vérification si un utilisateur avec cet email existe
            $user= $this->userRepo->findByEmail($request->email);
            
            if ($user) {
                return $this->conflict("Un utilisateur avec cet email existe déjà.");
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

            return $this->created(['user' => $user, 'token' => $token], "Utilisateur créé avec succès.");

        } catch (\Exception $e) {
            
            return $this->serverError("Erreur lors de la creation de l'utilisateur.");
        }

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
        try {

            if (!Auth::attempt($request->only('email', 'password'))) {

                return $this->unauthorized('identifiant invalide');
            }

            $user = $this->userRepo->findByEmail($request->email);

            $token = $user->createToken('access_token')->accessToken;

            return $this->success(['message'=>'connexion reussie','user' => $user,'token' => $token]);

        } catch (\Exception $e) {
            
            return $this->serverError("Erreur lors de la connexion.");
        }
        
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

            return $this->success(null, 'Déconnexion réussie');
        }

        return $this->unauthorized('Aucun utilisateur connecté');
    }
}
