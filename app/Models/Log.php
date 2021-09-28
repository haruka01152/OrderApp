<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class Log extends Model
{
    use HasFactory;

    protected $fillable = ['message'];

    public static function createLog()
    {
        Log::create([
            'message' => Auth::user()->name . 'さんが案件を作成しました。',
        ]);
    }
}
