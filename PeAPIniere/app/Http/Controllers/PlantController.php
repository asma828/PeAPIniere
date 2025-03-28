<?php

namespace App\Http\Controllers;

use App\Models\Plant;
use App\Repositories\Interfaces\PlantDAOInterface;
use Illuminate\Http\Request;
use Exception;

class PlantController extends Controller
{
    protected $plantDAO;

    public function __construct(PlantDAOInterface $plantDAO)
    {
        $this->plantDAO = $plantDAO;
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            return response()->json($this->plantDAO->getAllPlants());
        } catch (Exception $e) {
            return response()->json(['error' => 'Échec de la récupération des plantes', 'message' => $e->getMessage()], 500);
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            $data = $request->validate([
                'name' => 'required|string|max:255',
                'slug' => 'required|string|unique:plants',
                'description' => 'nullable|string',
                'price' => 'required|numeric',
                'category_id' => 'required|exists:categories,id',
            ]);

            return response()->json($this->plantDAO->createPlant($data), 201);
        } catch (Exception $e) {
            return response()->json(['error' => 'Échec de l’ajout de la plante', 'message' => $e->getMessage()], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show($slug)
    {
        try {
            return response()->json($this->plantDAO->getPlantBySlug($slug));
        } catch (Exception $e) {
            return response()->json(['error' => 'Plante introuvable', 'message' => $e->getMessage()], 404);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Plant $plant)
    {
        try {
            $data = $request->validate([
                'name' => 'sometimes|string|max:255',
                'slug' => 'sometimes|string|unique:plants,slug,' . $plant->id,
                'description' => 'nullable|string',
                'price' => 'sometimes|numeric',
                'category_id' => 'sometimes|exists:categories,id',
            ]);

            return response()->json($this->plantDAO->updatePlant($plant, $data));
        } catch (Exception $e) {
            return response()->json(['error' => 'Échec de la mise à jour de la plante', 'message' => $e->getMessage()], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Plant $plant)
    {
        try {
            return response()->json($this->plantDAO->deletePlant($plant));
        } catch (Exception $e) {
            return response()->json(['error' => 'Échec de la suppression de la plante', 'message' => $e->getMessage()], 500);
        }
    }
}
