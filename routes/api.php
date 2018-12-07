<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});
Route::get('/balance', function (Request $request) {
    $cid = $request->get('q');
    $data = DB::table('goods')
		    	->where('id', $cid)
		    	->select('group_price', 'market_price')
		    	->first();
	$price = 0;
	if(intval($data->group_price) != 0 && $data->group_price < $data->market_price){
        $price = $data->group_price;
     }else{
        $price = $data->market_price;
     }
	return [['id'=>$price, 'text'=> $price]];
});
Route::get('/discount', function (Request $request) {
    $cid = $request->get('q');
    $alonePrice = DB::table('goods')
		    	->where('id', $cid)
		    	->value('alone_price');
	return [['id' => $alonePrice, 'text' => $alonePrice]];
});

Route::get('/sylists', 'Api\GoodsCateController@syList');
Route::get('/glists', 'Api\GoodsController@gList');
Route::get('/clists', 'Api\GoodsCateController@cList');
Route::get('/gdetails', 'Api\GoodsController@gDetail');
Route::get('/wume', 'Api\WeUserController@me');