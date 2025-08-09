<?php
namespace App\Repositories;

use App\Models\Category;

class CategoryRepository extends BaseRepository
{
    /**
    * Create a new class instance.
    */
    public function __construct(Category $category)
    {
        parent::__construct($category);
    }
}