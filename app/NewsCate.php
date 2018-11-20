<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class NewsCate extends Model
{
    //
    public static function getCates()
    {
    	$catesArr = self::select('id', 'cate_name')
		    			->get()
		    			->flatmap(function($item, $key){
	    					$catesArr[$item->id] = $item->cate_name;
	    					return $catesArr;
		    			})->all();
		 return $catesArr;
    }
}
