<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Kyslik\ColumnSortable\Sortable; 
use Illuminate\Http\Request;


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

    public static function findConstructions()
    {
        $nonpage_constructions = Construction::where(function ($query) {

            $status = request('status');
            $find = request('find');    

            $query->where(function ($query) use ($status) {
                if ($status == 3) {
                    $query->where('status', '=', 1)
                        ->orWhere('status', '=', 2);
                } elseif (!$status) {
                    $query->where('status', '=', 1);
                } else {
                    $query->where('status', '=', $status);
                }
            });

            $query->where(function ($query) use ($find) {
                if (strpos($find, ' ') || strpos($find, '　')) {
                    $find = mb_convert_kana($find, 's');
                    $find = preg_split('/[\s]+/', $find, -1, PREG_SPLIT_NO_EMPTY);
                }

                if (is_array($find)) {
                    foreach ($find as $f) {
                        $query->where('customer_name', 'LIKE', "%{$f}%")
                            ->orWhere('construction_name', 'LIKE', "%{$f}%");
                    }
                } else {
                    $query->where('customer_name', 'LIKE', "%{$find}%")
                        ->orWhere('construction_name', 'LIKE', "%{$find}%");
                }
            });
        })->sortable()->orderBy('created_at', 'desc');

        return $nonpage_constructions;

    }

}
