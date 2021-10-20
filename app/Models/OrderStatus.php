<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderStatus extends Model
{
    use HasFactory;

    protected $table = 'order_status';

    public function constructions()
    {
        return $this->belongsTo('App\Models\Construction', 'id', 'order_status');
    }
}
