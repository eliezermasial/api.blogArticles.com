<?php

namespace App\Traits;

use Illuminate\Http\JsonResponse;

trait ApiResponse
{
    /**
    * Réponse 200 : succès avec données
    */
    protected function success($data = null, string $message = 'Succès', int $code = 200): JsonResponse
    {
        return response()->json([
            'status' => 'success',
            'message' => $message,
            'data' => $data,
        ], $code);
    }

    /**
    * Réponse 201 : ressource créée
    */
    protected function created($data = null, string $message = 'Création réussie'): JsonResponse
    {
        return $this->success($data, $message, 201);
    }

    /**
    * Réponse 200 : confirmation de suppression avec message personnalisé.
    * Utilisé quand une réponse JSON explicite est nécessaire après la suppression.
    */
    protected function deleted(string $message = 'Suppression réussie'): JsonResponse
    {
        return $this->success(null, $message, 200);
    }

    /**
    * Réponse 204 : aucun contenu (ex: suppression)
    */
    protected function noContent(): JsonResponse
    {
        return response()->json(null, 204);
    }

    /**
    * Réponse 400 : requête invalide
    */
    protected function badRequest(string $message = 'Requête invalide'): JsonResponse
    {
        return $this->error($message, 400);
    }

    /**
    * Réponse 401 : non authentifié
    */
    protected function unauthorized(string $message = 'Non authentifié'): JsonResponse
    {
        return $this->error($message, 401);
    }

    /**
    * Réponse 403 : accès refusé
    */
    protected function forbidden(string $message = 'Accès refusé'): JsonResponse
    {
        return $this->error($message, 403);
    }

    /**
    * Réponse 404 : non trouvé
    */
    protected function notFound(string $message = 'Ressource non trouvée'): JsonResponse
    {
        return $this->error($message, 404);
    }

    /**
    * Réponse 409 : conflit
    */
    protected function conflict(string $message = 'Conflit détecté'): JsonResponse
    {
        return $this->error($message, 409);
    }

    /**
    * Réponse 422 : données invalides
    */
    protected function unprocessable(array $errors, string $message = 'Données invalides'): JsonResponse
    {
        return response()->json([
            'status' => 'error',
            'message' => $message,
            'errors' => $errors,
        ], 422);
    }

    /**
    * Réponse 500 : erreur serveur
    */
    protected function serverError(string $message = 'Erreur interne'): JsonResponse
    {
        return $this->error($message, 500);
    }

    /**
    * Méthode générique pour les erreurs
    */
    protected function error(string $message, int $code): JsonResponse
    {
        return response()->json([
            'status' => 'error',
            'message' => $message,
        ], $code);
    }
}