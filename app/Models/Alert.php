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

    public static function createData($construction_id)
    {
        $construction = Construction::getConstruction($construction_id);
        Alert::create([
            'construction_id' => $construction_id,
        ]);
    }

    public static function createAlert()
    {
        $constructions = Construction::getAllConstructions();
        
        foreach($constructions as $construction){
            if(strtotime("+{$construction->alert_config} day") >= strtotime($construction->construction_date)){
                $alert = Alert::where('construction_id', $construction->id)->first();
                if($alert == null){
                    self::createData($construction->id);
                }
            }
        }
    }

}
