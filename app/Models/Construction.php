<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Kyslik\ColumnSortable\Sortable; 

class Construction extends Model
{
    use HasFactory;
    use Sortable;

    protected $fillable = [
        'contract_date', 
        'construction_date', 
        'customer_name',
        'construction_name',
        'arrive_status',
        'alert_config',
    ];

    public $sortable = ['contract_date', 'construction_date', 'customer_name', 'construction_name'];

    public function alerts()
    {
        return $this->hasMany('App\Models\Alert');
    }
}
