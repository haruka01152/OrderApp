<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class Log extends Model
{
    use HasFactory;

    protected $fillable = ['message', 'construction_id', 'contents'];

    public function constructions()
    {
        return $this->belongsTo('App\Models\Construction', 'construction_id', 'id');
    }

    public static function getLogs($id)
    {
        $logs = Log::where('construction_id', $id)->orderBy('created_at', 'desc')->paginate(5);
        return $logs;
    }

    public static function createData($user, $message, $id, $contents)
    {
        if (is_array($contents)) {
            Log::create([
                'message' => $user . $message,
                'construction_id' => $id,
                'contents' => implode("\n", $contents),
            ]);
        } else {
            Log::create([
                'message' => $user . $message,
                'construction_id' => $id,
                'contents' => $contents,
            ]);
        }
    }

    public static function createAddLog($id, $request)
    {
        $user = Auth::user()->name;
        $message = 'さんが案件を作成しました。';

        if ($request->images) {
            foreach ($request->images as $image) {
                $names[] = $image->getClientOriginalName();
            }
            $images = implode("\n", $names);
        }

        $contents = [
            'order_status' => '【発注状況】' . OrderStatus::where('id', $request->order_status)->first()->name,
            'contract_date' => $request->contract_date ? '【契約日】' . $request->contract_date : '【契約日】',
            'construction_date' => $request->construction_date ? '【工事日】' . $request->construction_date : '【工事日】',
            'customer_name' => '【お客様名】' . $request->customer_name,
            'construction_name' => '【案件名】' . $request->construction_name,
            'orders' => $request->images ? '【注文書】' . "\n" . $images : '【注文書】',
            'alert_config' => $request->notAlert ? '【アラート発信日】発信しない' : '【アラート発信日】' . $request->alert_config,
            'remarks' => $request->remarks ? '【案件・発注備考】' . $request->remarks : '【案件・発注備考】',
        ];

        self::createData($user, $message, $id, $contents);
    }

    public static function createEditLog($id, $request, $dirty, $changeOrder, $newOrders)
    {
        $user = Auth::user()->name;
        $message = 'さんが案件を更新しました。';

        $order_list = Order::where('construction_id', $id)->get();
        if ($order_list) {
            if ($changeOrder) {
                foreach ($order_list as $order) {
                    if ($changeOrder[$order->id] == 1) {
                        if ($order->arrive_status == 1) {
                            $orders[] = $order->image . ' (備考:' . $order->memo . ') 到着状況:✔';
                        } else {
                            $orders[] = $order->image . ' (備考:' . $order->memo . ') 到着状況:×';
                        }
                    }
                }
            } elseif ($newOrders != 0) {
                $uploaded = Order::whereIn('id', $newOrders)->get();
                foreach ($uploaded as $u) {
                    $orders[] = $u->image;
                }
            }
        } else {
            $orders = null;
        }

        if (isset($orders)) {
            if (isset($uploaded)) {
                $order_contents = '【注文書登録】' . "\n" . implode("\n", $orders);
            } else {
                $order_contents = '【注文書】' . "\n" . implode("\n", $orders);
            }
        }

        $contents = [
            'order_status' => '【発注状況】' . OrderStatus::where('id', $request->order_status)->first()->name,
            'contract_date' => $request->contract_date ? '【契約日】' . $request->contract_date : '【契約日】',
            'construction_date' => $request->construction_date ? '【工事日】' . $request->construction_date : '【工事日】',
            'customer_name' => '【お客様名】' . $request->customer_name,
            'construction_name' => '【案件名】' . $request->construction_name,
            'orders' => isset($order_contents) ? $order_contents : null,
            'alert_config' => $request->notAlert ? '【アラート発信日】発信しない' : '【アラート発信日】' . $request->alert_config,
            'remarks' => $request->remarks ? '【案件・発注備考】' . $request->remarks : '【案件・発注備考】',
        ];

        foreach ($contents as $key => $value) {
            if ((!array_key_exists($key, $dirty) && $key != 'orders') || $value == null) {
                unset($contents[$key]);
            }
        }

        self::createData($user, $message, $id, $contents);
    }

    public static function createDeleteLog($id)
    {
        $user = Auth::user()->name;
        $message = 'さんが案件を削除しました。';
        $contents = null;
        self::createData($user, $message, $id, $contents);
    }

    public static function createRestoreLog($id)
    {
        $user = Auth::user()->name;
        $message = 'さんが案件を復元しました。';
        $contents = null;
        self::createData($user, $message, $id, $contents);
    }

    public static function createDeleteOrderLog($construction_id, $order_id)
    {
        $user = Auth::user()->name;
        $message = 'さんが注文書を削除しました。';
        $order = Order::findOrFail($order_id);
        if ($order->arrive_status == 1) {
            $arrive_status =  '✔';
        } elseif ($order->arrive_status == 0) {
            $arrive_status =  '×';
        }
        $contents = '【削除した注文書】' . $order->image . ' (備考:' . $order->memo . ') 到着状況:' . $arrive_status;
        self::createData($user, $message, $construction_id, $contents);
    }
}
