<?php

namespace App\Http\Controllers;

use App\Models\Plant;
use DB;
use Illuminate\Http\Request;

class StatsController extends Controller
{
    public function index(){
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
