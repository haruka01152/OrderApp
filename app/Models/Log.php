<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Log extends Model
{
    use HasFactory;

    protected $fillable = ['message', 'link', 'linkTitle', 'construction_id'];

    public static function getLogs($id)
    {
        $logs = Log::where('construction_id', $id)->orderBy('created_at', 'desc')->paginate(5);
        return $logs;
    }

    public static function createLog($user, $id, $url)
    {
        if (strpos($url, 'add')) {
            $message = 'さんが案件を作成しました。';
        } elseif (strpos($url, 'edit')) {
            $message = 'さんが案件を更新しました。';
        } elseif (strpos($url, 'deleteOrder')) {
            $message = 'さんが案件の注文書を削除しました。';
        } elseif (strpos($url, 'delete')) {
            $message = 'さんが案件を削除しました。';
        } elseif (strpos($url, 'restore')) {
            $message = 'さんが案件を復元しました。';
        }

        $link = env('app_url') . 'edit/' . $id;

        Log::create([
            'message' => $user . $message,
            'construction_id' => $id,
            'link' => $link,
            'linkTitle' => Construction::getConstruction($id)->construction_name,
        ]);
    }
}
