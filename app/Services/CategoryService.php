<?php

namespace App\Services;

use Exception;
use App\Models\Category;
use App\Traits\ApiResponse;
use Illuminate\Support\Str;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use App\Repositories\CategoryRepository;


class CategoryService
{
    use ApiResponse;
    
    protected $categoryRepo;
    /**
    * Create a new class instance.
    */
    public function __construct(CategoryRepository $categoryRepo)
    {
        $this->categoryRepo = $categoryRepo;
    }

    /**
    * Récupère la liste complète des catégories.
    *
    * @return JsonResponse La réponse JSON contenant toutes les catégories.
    */
    public function all(): JsonResponse
    {
        try {

            $categories = $this->categoryRepo->get();

            return $this->success($categories, "Categories retrieved successfully.");

        } catch (Exception $e) {

            return $this->serverError("Error retrieving categories: " . $e->getMessage());
        }
    }

    /**
    * Crée une nouvelle catégorie avec les informations fournies par l'utilisateur connecté.
    * @param mixed $request Les données de la requête (name, description, etc.).
    * @return JsonResponse La réponse JSON contenant le message de succès et la catégorie créée.
    */
    public function create ($request): JsonResponse
    {
        $user = auth::user();

        try {

            if($user->role->name !== 'admin') {
                return $this->unauthorized("You do not have permission to create a category.");
            }

            $slug = Str::slug($request['name']);

            $category = $this->categoryRepo->create([
                'name' => $request['name'],
                'slug' => $slug,
                'description' => $request['description'] ?? null,
                'is_active' => true,
                'user_id' => $user->id
            ]);

            return $this->success($category, "Category created successfully.");

        } catch (Exception $e) {

            return $this->serverError("Error creating category: " . $e->getMessage());
        }
    }

}
