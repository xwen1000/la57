<?php
namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;

class GoodsCateController extends Controller
{
	const SIZE = 10;

	public function syList()
	{
		$ret = ['banner'=>[], 'list'=>[]];

        $banner = DB::table("banners")
    				->select('banner_name', 'image_url', 'banner_type', 'cate_id')
    				->orderBy('banner_sort', 'desc')
    				->orderBy('id', 'desc')
    				->get()->all();
        $ret['banner'] = $banner;
        
        $cates = DB::table("goods_cates")
        				->select('id', 'cate_name', 'image_url')
        				->where('state', 1)
        				->where('is_delete', 1)
        				->orderBy('sort', 'desc')
        				->orderBy('id', 'desc')
        				->get()->all();

        foreach ($cates as $k => $v) {
            $goods = DB::table('goods')
            			->select('id', 'goods_name', 'image_url', 'market_price', 'group_price')
            			->where('cate_id', $v->id)
            			->orderBy('goods_sort', 'desc')
            			->orderBy('id', 'desc')
            			->limit(3)
            			->get()->all();
            if($goods){
                foreach ($goods as $k => $v) {
                   $goods[$k]->price = get_price($v->market_price,$v->group_price);
                }
                $cates[$k]->goods = $goods;
            }else{
                unset($cates[$k]);
            }
        }
        $cates = array_values($cates);
        $ret['list'] = $cates;

        $ret['result'] = 'ok';
        return response()->json($ret);
	}

	public function cList()
	{

        $page = request()->input('page', 1);

        $offset = ($page - 1) * self::SIZE;

        $list = DB::table('goods_cates')
					->where('parent_cate', 0)
					->where('is_delete', 1)
					->orderBy('sort', 'desc')
					->orderBy('id', 'desc')
					->offset($offset)
					->limit(self::SIZE)
					->get()->all();
        if(!$list) {
            $ret['cates'] = [];
        } else {
        	$list[] = ['cate_id'=>9999, 'cate_name'=>'每季新菜', 'parent_cate'=>0, 'sort'=>9999, 'time'=>0 ,'state'=>2];
            
            $ret['cates'] = $list;
        }
        $ret['result'] = 'ok';
        return response()->json($ret);
	}

	public function ncList()
	{
		$map = array();
        $NcpCateDb = D('NcpCate');

		$page = request()->input('page', 1);

        $offset = ($page - 1) * self::SIZE;

        $cid = request()->input('cid', 0);
        $cid == 0 ? $cid = 1 : '';

        $map['cid'] = $cid;
        $list = DB::table('npc_cates')
        			->where('cid', $cid)
        			->orderBy('sort')
        			->offset($offset)
        			->limit(self::SIZE)
        			->get()->all();
        $ret['cates'] = $list;
        $ret['result'] = 'ok';
        return response()->json($ret);
	}
}


