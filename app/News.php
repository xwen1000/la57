<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class News extends Model
{
    //
    protected $table = 'news';

    public static function upd($id, $data)
	{
        self::where('id', $id)->update($data);
	}
}
