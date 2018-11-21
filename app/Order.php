<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    //
    public static function getInfo($id)
    {

        $oinfo = self::where([
        	'buyer_id' => $id, 
        	'order_status' => 4
        ])->orderBy('order_time', 'desc')->first();
        return $oinfo ? $oinfo['order_time'] : '';
    }
}
