<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class GoodsCate extends Model
{
    //
    public static function getCates()
    {
    	$cates = self::select('id', 'cate_name')
                    ->whereNotIn('id', [2, 3, 8, 17])
                    ->where('is_delete', 1)
                    ->where('state', 1)
                    ->orderBy('sort', 'desc')
                    ->orderBy('id', 'desc')
                    ->get();
        $cateArr = $cates->flatMap(function($item, $key){
                        $cateArr[$item->id] = $item->cate_name;
                        return $cateArr;
                    })->all();
        return $cateArr;
    }
}
