<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class NpcCate extends Model
{
    //
    public static function getNpcCates()
    {
    	$npcCates = self::select('id', 'cate_name')
                        ->orderBy('sort')
                        ->get();
        $npcCatesArr = $npcCates->flatMap(function($item, $key){
                            $npcCatesArr[$item->id] = $item->cate_name;
                            return $npcCatesArr;
                        });
        return $npcCatesArr;
    }
}
