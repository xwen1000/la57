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
}
