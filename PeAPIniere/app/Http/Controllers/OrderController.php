<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Repositories\Interfaces\OrderRepositoryInterface;
use Illuminate\Http\Request;
use Exception;

class OrderController extends Controller
{
    private $orderRepository;

    public function __construct(OrderRepositoryInterface $orderRepository)
    {
        $this->orderRepository = $orderRepository;
    }

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
