<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class MemberCard extends Model
{
    //
    public static function getInfo($id)
    {
    	 $cinfo = self::where([
                        'mid' => $id,
                        // 'ctype' => 3, 
                        'status' => 1 //可用
                    ])->get()->all();
    	 return $cinfo;
    }
}
