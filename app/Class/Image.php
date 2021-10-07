<?php

namespace App\Class;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;


class Image{

    public static function store($request)
    {
        // アップされた画像をファイルに保存
        foreach($request->file('images') as $file){
            $file_name = $file->getClientOriginalName();
            $image_path = $file->storeAs('public/images', time() . $file_name);
            $file_names[] = $file_name;
            $image_paths[] = $image_path;
        }
        $imageAndPath = array_combine($file_names, $image_paths);
        return $imageAndPath;
    }

    // public function delete(Request $request, $id)
    // {
    //     // 保有リストの画像をファイルから削除
    //     $del = Coin::findOrFail($id);
    //     Storage::delete('public/images/' . $del->icon);
    // }

}