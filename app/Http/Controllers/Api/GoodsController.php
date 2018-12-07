<?php
namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;

class GoodsController extends Controller
{
    
    const SIZE = 10;

	public function gList()
	{
        
        $page = request()->input('page', 1);

        $offset = ($page - 1) * self::SIZE;

        $sort = request()->input('sort', 1);
        $desc = request()->input('desc', 1);

        $cateId = request()->input('cate_id', 0);
        $ncpCateId = request()->input('ncp_cate_id', 0);
        $searchTitle = request()->input('search_title', '');

        $isRecommend = request()->input('is_recommend', 0);
        $isNew = request()->input('is_new', 0);
        switch ($sort) {
            case '1':
                $order = $desc ? 'sell_count desc':'sell_count asc';
                break;
            case '2':
                $order = $desc ? 'market_price desc':'market_price asc';
                break;
            default:
                $order = $desc ? 'is_recommend desc, goods_id desc':'is_recommend desc, goods_id asc';
                break;
        }
        // DB::enableQueryLog();
        $list = DB::table('goods')
        			->where('is_delete', 1)
        			->where('in_selling', 1)
        			->when($cateId && $cateId == 9999, function ($query) use ($cateId) {
	                    return $query->where('is_new', 1);
	                })
    				->when($cateId, function ($query) use ($cateId) {
	                    return $query->where('cate_id', $cateId);
	                }, function($query){
	                    return $query->whereNotIn('cate_id' , [2, 3]);
	                })
	                ->when($ncpCateId, function ($query) use ($ncpCateId) {
	                    return $query->where('ncp_cate_id', $ncpCateId);
	                })
	                ->when($searchTitle, function ($query) use ($searchTitle) {
	                    return $query->where('goods_name', 'like', '%'.$searchTitle.'%');
	                })
	                ->when($isRecommend, function ($query) use ($isRecommend) {
	                    return $query->where('is_recommend', $isRecommend);
	                })
	                ->when($isNew, function ($query) use ($isNew) {
	                    return $query->where('is_new', $isNew);
	                })
    				->orderByRaw($order)
    				->offset($offset)
    				->limit(self::SIZE)
    				->get();
    	// dd(DB::getQueryLog());
        $ret['goods'] = $list;
        $ret['result'] = 'ok';
        return response()->json($ret);
	}

    public function gDetail()
    {
        $goodsId = request()->input('goods_id', 0);
        $token = request()->input('token', '');
        $goodInfo = DB::table('goods')
                        ->where('id', $goodsId)
                        ->where('is_delete', 1)
                        ->where('in_selling', 1)
                        ->first();
        if(!$goodInfo) 
            return response()->json(['result'=>'fail','error_info'=>'']);
        if($goodInfo->cate_id == 8 ) {
            $goodInfo->card_good_list = DB::table('cards_goods')
                                            ->where('cid', $goodInfo->id)
                                            ->get()->all();
        }
        
        
        $imgs = unserialize($goodInfo->goods_imgs);
        
        foreach($imgs as $v) {
            $gallery[]['img_url'] =  $v;
        }
        $cate_id = $goodInfo->cate_id;
        
        $discountPrice = get_price($goodInfo->market_price, $goodInfo->group_price);
        $res = [
            'goods_id' => $goodInfo->id,
            'cate_id' => $goodInfo->cate_id,
            'ncp_cate_id' => $goodInfo->ncp_cate_id,
            'goods_name' => $goodInfo->goods_name,
            'goods_desc' => $goodInfo->goods_desc,
            'market_price' => $goodInfo->market_price,
            'price' => $discountPrice,
            'time' => $goodInfo->time,
            'card_balance' => $goodInfo->card_balance,
            'unit' => $goodInfo->unit,
            'goods_sn' => $goodInfo->goods_sn,
            'sell_count' => $goodInfo->sell_count,
            'limit_buy' => $goodInfo->limit_buy,
            'goods_stock' => $goodInfo->goods_stock,
            'gallery' => $gallery,
        ];
        return response()->json(['result'=>'ok','goods'=>$res]);
    }

}