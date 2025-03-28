<?php

namespace App\Http\Controllers;

use App\Models\Plant;
use DB;
use Illuminate\Http\Request;

/**
 * @OA\Tag(
 *     name="Statistics",
 *     description="Statistiques sur les ventes de plantes"
 * )
 */
class StatsController extends Controller
{
    /**
     * Obtenir les statistiques des ventes.
     * 
     * @OA\Get(
     *     path="/api/stats",
     *     summary="Obtenir les statistiques des ventes",
     *     tags={"Statistics"},
     *     @OA\Response(
     *         response=200,
     *         description="Statistiques des ventes récupérées avec succès",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="total_ventes", type="integer", example=1234),
     *             @OA\Property(
     *                 property="plantes_populaires",
     *                 type="array",
     *                 @OA\Items(
     *                     type="object",
     *                     @OA\Property(property="name", type="string", example="Rose"),
     *                     @OA\Property(property="total_commandes", type="integer", example=150)
     *                 )
     *             ),
     *             @OA\Property(
     *                 property="ventsParCategorie",
     *                 type="array",
     *                 @OA\Items(
     *                     type="object",
     *                     @OA\Property(property="name", type="string", example="Fleurs"),
     *                     @OA\Property(property="total_vendu", type="integer", example=500)
     *                 )
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Erreur serveur"
     *     )
     * )
     */
    public function index()
    {
        $totalVentes = DB::table('orders')->count();

        $plantesPopulaires = DB::table('order_items')
            ->join('plants', 'order_items.plant_id', '=', 'plants.id')
            ->select('plants.name', DB::raw('COUNT(DISTINCT order_items.order_id) as total_commandes'))
            ->groupBy('plants.name')
            ->orderByDesc('total_commandes')
            ->limit(5)
            ->get();

        $RepartionParCategorie = DB::table('order_items')
            ->join('plants', 'order_items.plant_id', '=', 'plants.id')
            ->join('categories', 'plants.category_id', '=', 'categories.id')
            ->select('categories.name', DB::raw('SUM(order_items.quantity) as total_vendu'))
            ->groupBy('categories.name')
            ->get();

        return response()->json([
            'total_ventes' => $totalVentes,
            'plantes_populaires' => $plantesPopulaires,
            'ventsParCategorie' => $RepartionParCategorie
        ]);
    }
}
