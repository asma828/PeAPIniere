<?php
namespace App\Repositories\Interfaces;

use App\Models\Category;

interface CategoryDAOInterface
{
    public function getAllCategories();
    public function getCategoryById(int $id);
    public function createCategory(array $data);
    public function updateCategory(Category $category, array $data);
    public function deleteCategory(Category $category);
}
