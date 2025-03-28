<?php
namespace App\Repositories\Interfaces;

use App\Models\Order;

interface OrderRepositoryInterface
{
    public function passerCommande(array $data);
    public function annulerCommande(int $orderId);
    public function findById(int $id);

}
