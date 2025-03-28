<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Repositories\Interfaces\CategoryDAOInterface;
use Illuminate\Http\Request;
use Exception;
use OpenApi\Annotations as OA;

class CategoryController extends Controller
{
    protected $categoryDAO;

    public function __construct(CategoryDAOInterface $categoryDAO)
    {
        $this->categoryDAO = $categoryDAO;
    }

    /**
     * @OA\Get(
     *     path="/api/categories",
     *     summary="Get all categories",
     *     tags={"Categories"},
     *     @OA\Response(
     *         response=200,
     *         description="Successful retrieval",
     *         @OA\JsonContent(type="array", @OA\Items(ref="#/components/schemas/Category"))
     *     ),
     *     @OA\Response(response=500, description="Failed to retrieve categories")
     * )
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
     * @OA\Post(
     *     path="/api/categories",
     *     summary="Create a new category",
     *     tags={"Categories"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"name"},
     *             @OA\Property(property="name", type="string", example="Technology")
     *         )
     *     ),
     *     @OA\Response(response=201, description="Category created successfully"),
     *     @OA\Response(response=500, description="Failed to create category")
     * )
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
     * @OA\Get(
     *     path="/api/categories/{id}",
     *     summary="Get a category by ID",
     *     tags={"Categories"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="Category ID",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(response=200, description="Category retrieved successfully"),
     *     @OA\Response(response=500, description="Failed to retrieve category")
     * )
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
     * @OA\Put(
     *     path="/api/categories/{id}",
     *     summary="Update a category",
     *     tags={"Categories"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="Category ID",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"name"},
     *             @OA\Property(property="name", type="string", example="Updated Category Name")
     *         )
     *     ),
     *     @OA\Response(response=200, description="Category updated successfully"),
     *     @OA\Response(response=500, description="Failed to update category")
     * )
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
     * @OA\Delete(
     *     path="/api/categories/{id}",
     *     summary="Delete a category",
     *     tags={"Categories"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="Category ID",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(response=200, description="Category deleted successfully"),
     *     @OA\Response(response=500, description="Failed to delete category")
     * )
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
