<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Repositories\Interfaces\CategoryDAOInterface;
use Illuminate\Http\Request;
use Exception;

class CategoryController extends Controller
{
    protected $categoryDAO;

    public function __construct(CategoryDAOInterface $categoryDAO)
    {
        $this->categoryDAO = $categoryDAO;
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            return response()->json($this->categoryDAO->getAllCategories());
        } catch (Exception $e) {
            return response()->json(['error' => 'Failed to retrieve categories', 'message' => $e->getMessage()], 500);
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            $data = $request->validate([
                'name' => 'required|string|max:255|unique:categories',
            ]);

            return response()->json($this->categoryDAO->createCategory($data), 201);
        } catch (Exception $e) {
            return response()->json(['error' => 'Failed to create category', 'message' => $e->getMessage()], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        try {
            return response()->json($this->categoryDAO->getCategoryById($id));
        } catch (Exception $e) {
            return response()->json(['error' => 'Failed to retrieve category', 'message' => $e->getMessage()], 500);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Category $category)
    {
        try {
            $data = $request->validate([
                'name' => 'sometimes|string|max:255|unique:categories,name,' . $category->id,
            ]);

            return response()->json($this->categoryDAO->updateCategory($category, $data));
        } catch (Exception $e) {
            return response()->json(['error' => 'Failed to update category', 'message' => $e->getMessage()], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Category $category)
    {
        try {
            return response()->json($this->categoryDAO->deleteCategory($category));
        } catch (Exception $e) {
            return response()->json(['error' => 'Failed to delete category', 'message' => $e->getMessage()], 500);
        }
    }
}
