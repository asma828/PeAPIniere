<?php
namespace App\Repositories;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Plant;
use App\Repositories\Interfaces\OrderRepositoryInterface;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class OrderRepository implements OrderRepositoryInterface
{
    public function passerCommande(array $data)
    {
        $user = Auth::user();

        $order = Order::create([
            'user_id' => $user->id,
            'status' => 'en attente'
        ]);


        foreach ($data['items'] as $item) {
            $plant = Plant::where('slug', $item['slug'])->firstOrFail();

            OrderItem::create([
                'order_id' => $order->id,
                'plant_id' => $plant->id,
                'quantity' => $item['quantity'],
            ]);

        }

        $order->save();

        return $order;
    }

    public function annulerCommande(int $orderId)
    {
        $order = Order::findOrFail($orderId);
        if ($order->status === 'en attente') {
            $order->delete();
            return true;
        }
        return false;
    }

    public function findById(int $id)
    {
        return Order::find($id);
    }
    
}
