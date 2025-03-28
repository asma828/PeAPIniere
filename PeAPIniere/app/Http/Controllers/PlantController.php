<?php

namespace App\Http\Controllers;

use App\Models\Plant;
use App\Repositories\Interfaces\PlantDAOInterface;
use Illuminate\Http\Request;
use Exception;

/**
 * @OA\Tag(
 *     name="Plants",
 *     description="Gestion des plantes"
 * )
 */
class PlantController extends Controller
{
    protected $plantDAO;

    public function __construct(PlantDAOInterface $plantDAO)
    {
        $this->plantDAO = $plantDAO;
    }

    /**
     * Récupérer la liste des plantes.
     * 
     * @OA\Get(
     *     path="/api/plants",
     *     summary="Obtenir toutes les plantes",
     *     tags={"Plants"},
     *     @OA\Response(
     *         response=200,
     *         description="Liste des plantes récupérée avec succès"
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Erreur serveur"
     *     )
     * )
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
     * Ajouter une nouvelle plante.
     * 
     * @OA\Post(
     *     path="/api/plants",
     *     summary="Créer une plante",
     *     tags={"Plants"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"name","slug","price","category_id"},
     *             @OA\Property(property="name", type="string", example="Rose"),
     *             @OA\Property(property="slug", type="string", example="rose"),
     *             @OA\Property(property="description", type="string", example="Belle plante"),
     *             @OA\Property(property="price", type="number", format="float", example=19.99),
     *             @OA\Property(property="category_id", type="integer", example=1)
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Plante ajoutée avec succès"
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Erreur serveur"
     *     )
     * )
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
     * Obtenir une plante par slug.
     * 
     * @OA\Get(
     *     path="/api/plants/{slug}",
     *     summary="Obtenir une plante par son slug",
     *     tags={"Plants"},
     *     @OA\Parameter(
     *         name="slug",
     *         in="path",
     *         required=true,
     *         description="Slug de la plante",
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Plante trouvée"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Plante introuvable"
     *     )
     * )
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
     * Mettre à jour une plante.
     * 
     * @OA\Put(
     *     path="/api/plants/{id}",
     *     summary="Mettre à jour une plante",
     *     tags={"Plants"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID de la plante",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         @OA\JsonContent(
     *             @OA\Property(property="name", type="string", example="Tulipe"),
     *             @OA\Property(property="slug", type="string", example="tulipe"),
     *             @OA\Property(property="description", type="string", example="Plante colorée"),
     *             @OA\Property(property="price", type="number", format="float", example=14.99),
     *             @OA\Property(property="category_id", type="integer", example=2)
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Plante mise à jour avec succès"
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Erreur serveur"
     *     )
     * )
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
     * Supprimer une plante.
     * 
     * @OA\Delete(
     *     path="/api/plants/{id}",
     *     summary="Supprimer une plante",
     *     tags={"Plants"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID de la plante",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Plante supprimée avec succès"
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Erreur serveur"
     *     )
     * )
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
