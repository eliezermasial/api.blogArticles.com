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
