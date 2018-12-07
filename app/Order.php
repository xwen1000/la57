<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    //

	public function members()
    {
        return $this->belongsTo(Member::class, 'buyer_id', 'id');
    }

    public static function getInfo($id)
    {

        $oinfo = self::where([
        	'buyer_id' => $id, 
        	'order_status' => 4
        ])->orderBy('order_time', 'desc')->first();
        return $oinfo ? $oinfo['order_time'] : '';
    }
}
