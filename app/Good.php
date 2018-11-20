<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Good extends Model
{
    //
    public function getGoodsImgsAttribute($goodsImgs)
	{
	    return json_decode($goodsImgs, true);
	}

	public static function del($id)
	{
        self::where('id', $id)->update(['is_delete'=>2]);
	}

	public static function upd($id, $data)
	{
        self::where('id', $id)->update($data);
	}
}
