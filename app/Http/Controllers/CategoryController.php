<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\CategoryService;
use Illuminate\Http\JsonResponse;

class CategoryController extends Controller
{
    protected $category;
    public function __construct(CategoryService $category)
    {
        $this->category = $category;
    }
    
    public function index(): JsonResponse
    {
        return $this->category->all();
    }

    public function store(Request $request): JsonResponse
    {
        return $this->category->create($request);
    }

    public function show($category): JsonResponse
    {
        return $this->category->getCategory($category);
    }

    public function update(Request $request, $category): JsonResponse
    {
        return $this->category->update($request, $category);
    }

    public function destroy($category): JsonResponse
    {
        return $this->category->delete($category);
    }
}
