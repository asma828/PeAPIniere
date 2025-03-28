<?php
namespace App\Repositories;

use App\Models\Category;
use App\Repositories\Interfaces\CategoryDAOInterface;

class CategoryDAO implements CategoryDAOInterface
{
    public function getAllCategories()
    {
        return Category::all();
    }

    public function getCategoryById(int $id)
    {
        return Category::findOrFail($id);
    }

    public function createCategory(array $data)
    {
        return Category::create($data);
    }

    public function updateCategory(Category $category, array $data)
    {
        $category->update($data);
        return $category;
    }

    public function deleteCategory(Category $category)
    {
        return $category->delete();
    }
}
