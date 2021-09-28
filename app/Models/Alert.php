<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Alert extends Model
{
    use HasFactory;

    protected $fillable = [
        'construction_id',
    ];

    public function constructions()
    {
        return $this->belongsTo('App\Models\Construction', 'construction_id', 'id');
    }

    public function getAlerts()
    {
        $alerts = Alert::orderBy('id', 'asc')->paginate(5);
        return $alerts;
    }
}
