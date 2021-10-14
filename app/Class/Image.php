<?php

namespace App\Class;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Models\Order;

class Image{

    public static function store($request)
    {
        // アップされた画像をファイルに保存
        foreach($request->file('images') as $file){
            $file_extension = $file->getClientOriginalExtension();
            $file_name = $file->getClientOriginalName();
            $image_path = $file->storeAs('public/images', time() . $file_name);
            $file_names[] = $file_name;
            $image_paths[] = $image_path;
        }
        $imageAndPath = array_combine($file_names, $image_paths);
        return $imageAndPath;
    }

    public static function delete(Request $request, $id)
    {
        // 画像をファイルから削除
        $file = Order::findOrFail($id);
        Storage::delete($file->path);
    }

}