<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Repositories\Interfaces\OrderRepositoryInterface;
use Illuminate\Http\Request;
use Exception;

/**
 * @OA\Tag(
 *     name="Orders",
 *     description="Gestion des commandes"
 * )
 */
class OrderController extends Controller
{
    private $orderRepository;

    public function __construct(OrderRepositoryInterface $orderRepository)
    {
        $this->orderRepository = $orderRepository;
    }

    /**
     * Passer une commande.
     * 
     * @OA\Post(
     *     path="/api/orders",
     *     summary="Passer une commande",
     *     tags={"Orders"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"items"},
     *             @OA\Property(
     *                 property="items",
     *                 type="array",
     *                 @OA\Items(
     *                     type="object",
     *                     @OA\Property(property="slug", type="string", example="rose"),
     *                     @OA\Property(property="quantity", type="integer", example=2)
     *                 )
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Commande passée avec succès"
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
            $request->validate([
                'items' => 'required|array',
                'items.*.slug' => 'required|exists:plants,slug',
                'items.*.quantity' => 'required|integer|min:1'
            ]);

            $order = $this->orderRepository->passerCommande($request->all());

            return response()->json(['message' => 'Commande passée avec succès', 'order' => $order], 201);
        } catch (Exception $e) {
            return response()->json(['error' => 'Échec de la création de la commande', 'message' => $e->getMessage()], 500);
        }
    }

    /**
     * Annuler une commande.
     * 
     * @OA\Delete(
     *     path="/api/orders/{id}",
     *     summary="Annuler une commande",
     *     tags={"Orders"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID de la commande",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Commande annulée avec succès"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Commande introuvable"
     *     ),
     *     @OA\Response(
     *         response=403,
     *         description="Accès non autorisé"
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Impossible d'annuler la commande"
     *     )
     * )
     */
    public function cancel($id)
    {
        try {
            $order = $this->orderRepository->findById($id);

            if (!$order) {
                return response()->json(['message' => 'Commande introuvable'], 404);
            }

            if ($order->user_id !== auth()->id()) {
                return response()->json(['message' => 'Vous ne pouvez annuler que vos propres commandes'], 403);
            }

            if ($order->status !== 'en attente') {
                return response()->json(['message' => 'Impossible d’annuler une commande en préparation ou livrée'], 400);
            }

            $this->orderRepository->annulerCommande($id);
            return response()->json(['message' => 'Commande annulée avec succès'], 200);
        } catch (Exception $e) {
            return response()->json(['error' => 'Échec de l’annulation de la commande', 'message' => $e->getMessage()], 500);
        }
    }

    /**
     * Afficher les détails d'une commande.
     * 
     * @OA\Get(
     *     path="/api/orders/{orderId}",
     *     summary="Afficher une commande",
     *     tags={"Orders"},
     *     @OA\Parameter(
     *         name="orderId",
     *         in="path",
     *         required=true,
     *         description="ID de la commande",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Détails de la commande"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Commande introuvable ou accès refusé"
     *     )
     * )
     */
    public function show($orderId)
    {
        try {
            $order = Order::where('id', $orderId)
                ->where('user_id', auth()->id())
                ->firstOrFail();

            return response()->json([
                'status' => $order->status
            ]);
        } catch (Exception $e) {
            return response()->json(['error' => 'Commande introuvable ou accès refusé', 'message' => $e->getMessage()], 404);
        }
    }

    /**
     * Mettre à jour le statut de la commande.
     * 
     * @OA\Put(
     *     path="/api/orders/{id}/status",
     *     summary="Mettre à jour le statut de la commande",
     *     tags={"Orders"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID de la commande",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"status"},
     *             @OA\Property(property="status", type="string", enum={"en attente", "en préparation", "livrée"})
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Statut de la commande mis à jour avec succès"
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Erreur serveur"
     *     )
     * )
     */
    public function updateStatus($id, Request $request)
    {
        try {
            $data = $request->validate([
                'status' => 'required|in:en attente,en préparation,livrée'
            ]);

            $order = Order::findOrFail($id);
            $order->update(['status' => $data['status']]);

            return response()->json([
                'message' => 'Statut de la commande mis à jour avec succès.',
                'order' => $order
            ]);
        } catch (Exception $e) {
            return response()->json(['error' => 'Échec de la mise à jour du statut', 'message' => $e->getMessage()], 500);
        }
    }
}
