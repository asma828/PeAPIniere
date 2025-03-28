<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderItem extends Model
{
    use HasFactory;
    protected $fillable = ['order_id', 'plant_id', 'quantity'];

    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    public function plant()
    {
        return $this->belongsTo(Plant::class,'plant_id');
    }
}
