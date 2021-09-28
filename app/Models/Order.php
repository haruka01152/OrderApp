<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'construction_id',
        'image',
        'memo',
        'arrive_status',
    ];

    public function getOrders($id)
    {
        $order = Order::where('construction_id', $id)->get();
        return $order;
    }
}
