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
    
    public function index()
    {
        // Logic to get all categories
    }

    public function store(Request $request): JsonResponse
    {
        return $this->category->create($request);
    }

    public function show($category)
    {
        // Logic to show a specific category
    }

    public function update(Request $request, $category)
    {
        // Logic to update a specific category
    }

    public function destroy($category)
    {
        // Logic to delete a specific category
    }
}
