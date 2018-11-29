<?php

namespace App\Admin\Controllers;

use App\Http\Controllers\Controller;
use Encore\Admin\Layout\Content;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class FinanceController extends Controller
{
    public function index(Content $content)
    {
    	return $content->header('后台')
		            ->description('财务管理')
		            ->body($this->body());
    }

    protected function body()
    {
    	$carbon = Carbon::now();
        $startDate = $carbon->startOfMonth()->timestamp;
        $endDate = $carbon->endOfMonth()->timestamp;
        $retArr = $this->getTotal($startDate, $endDate);
    	return view('admin.finance-index', [
    		'currYearMonth' => $carbon->format('Y-m'),
    		'lastYearMonth' => $carbon->subYear()->endOfYear()->format('Y-m'),
    		'allTotal' => $retArr['allTotal'],
    		'goodsTotal' => $retArr['goodsTotal'],
    		'roomTotal' => $retArr['roomTotal'],
    		'buyTotal' => $retArr['buyTotal'],
    		'chargeTotal' => $retArr['chargeTotal'],
    	]);

    }

    public function charges(Content $content)
    {
    	return $content->header('后台')
		            ->description('会员充值明细')
		            ->body($this->bodyCharges());
    }

    public function buys(Content $content)
    {
    	return $content->header('后台')
		            ->description('微信购买明细')
		            ->body($this->bodyBuys());
	}

	public function sales(Content $content)
    {
    	return $content->header('后台')
		            ->description('农产品销售明细')
		            ->body($this->bodySales());
	}

	public function express(Content $content)
    {
    	return $content->header('后台')
		            ->description('运费设置')
		            ->body($this->bodyExpress());
	}

	protected function bodyExpress()
	{
         $list = DB::table('regions')
		         	->where('parent_id', 1)
		         	->where('level', 1)
		         	->orderBy('id')
		         	->get()->all();
		return view('admin.finance-express', [
			'list' => $list
		]);
	}

	public function store()
	{
		$id = request()->input('id');
		$express = request()->input('express');
		$heavy = request()->input('heavy');
		$oexpress = request()->input('oexpress');
		$oheavy = request()->input('oheavy');
		foreach ($id as $k => $v) {
			DB::table('regions')->where('id', $v)->update([
				'express' => $express[$k],
				'heavy' => $heavy[$k],
				'oexpress' => $oexpress[$k],
				'oheavy' => $oheavy[$k],
			]);
		}
		return redirect('/admin/finances/express');
	}

	protected function bodySales()
    {
    	$carbon = Carbon::now();
    	return view('admin.finance-sales', [
    		'currYearMonth' => $carbon->format('Y-m'),
    		'lastYearMonth' => $carbon->subYear()->endOfYear()->format('Y-m')
    	]);
	}

	public function getSales()
    {
    	$startDate = request()->input('startDate');
    	$endDate = request()->input('endDate');
    	$goodsId = request()->input('goodsId');
    	$oinfo = DB::table('orders as o')
    				->join('order_goods as og', 'o.id', '=', 'og.orders_id')
    				->select('o.id', 'o.order_sn', 'og.goods_name', 'og.card_balance', 'og.quantity', 'og.goods_price', 'o.book_days', 'o.order_time', 'o.pay_time')
                    ->whereBetween('o.order_time', [strtotime($startDate), strtotime($endDate)])
                    ->whereIn('o.order_status', [1,2,3,4])
                    ->whereIn('o.order_type', [0,1])
                    ->when($goodsId, function($query)use($goodsId){
                    	return $query->where('o.id', $goodsId);
                    })
                    ->orderBy('o.id', 'desc')
                    ->get()->map(function($item, $key){
                    	$item->pay_time = $item->pay_time ? date('Y-m-d H:i:s', $item->pay_time) : '';
                    	$item->order_time = $item->order_time ? date('Y-m-d H:i:s', $item->order_time) : '';
                    	return $item;
                    })->all();
    	return response()->json($oinfo);
    }

	protected function bodyBuys()
    {
    	$carbon = Carbon::now();
    	return view('admin.finance-buys', [
    		'currYearMonth' => $carbon->format('Y-m'),
    		'lastYearMonth' => $carbon->subYear()->endOfYear()->format('Y-m')
    	]);
	}

    protected function bodyCharges()
    {
    	$carbon = Carbon::now();
    	return view('admin.finance-charges', [
    		'currYearMonth' => $carbon->format('Y-m'),
    		'lastYearMonth' => $carbon->subYear()->endOfYear()->format('Y-m')
    	]);
	}

    protected function getTotal($startDate, $endDate)
    {
        $retArr = [
        	'allTotal' => 0,
        	'goodsTotal' => 0,
        	'roomTotal' => 0,
        	'buyTotal' => 0,
        	'chargeTotal' => 0,
        ];
        $oinfo = DB::table('orders')
                    ->whereBetween('order_time', [$startDate, $endDate])
                    ->whereIn('order_status', [1,2,3,4])
                    ->get()->all();
    	foreach ($oinfo as $k => $v) {
        	$retArr['allTotal'] += $v->goods_amount - $v->card_amount;
        	if($v->order_type != 2)
             {
                 if($v->order_type == 0)
                 {
                     $retArr['goodsTotal'] += $v->goods_amount;
                 }
                 else if($v->order_type == 1)
                 {
                     $retArr['roomTotal'] += $v->goods_amount;
                 }
             }
             if($v->order_type == 0 || $v->order_type == 1)
             {
                 $retArr['buyTotal'] += $v->goods_amount - $v->card_amount;
             }
             else if($v->order_type == 2)
             {
                 $retArr['chargeTotal'] += $v->goods_amount;
             }
        }
        return $retArr;
    }

    public function getSearch()
    {
    	$startDate = request()->input('startDate');
    	$endDate = request()->input('endDate');
    	$carbon = Carbon::create(date('Y', strtotime($endDate)), date('m', strtotime($endDate)));
    	$diffMonths = $carbon->diffInMonths($startDate);
    	$i = 0;
    	$everMonth = [$endDate];
    	while ( $i < $diffMonths ) {
    		$subMonth = $carbon->subMonth()->timestamp;
    		$yearMonth = date('Y', $subMonth) . '-' . date('m', $subMonth);
    		array_push($everMonth, $yearMonth);
    		$i++;

    	}
    	$retArr = [];
    	foreach ($everMonth as $k => $v) {
    		$carbon = Carbon::create(date('Y', strtotime($v)), date('m', strtotime($v)));
    		$startDate = $carbon->startOfMonth()->timestamp;
        	$endDate = $carbon->endOfMonth()->timestamp;
        	$retArr[$v] = $this->getTotal($startDate, $endDate);
    	}
    	return response()->json($retArr);
    }

    public function getCharges()
    {
    	$startDate = request()->input('startDate');
    	$endDate = request()->input('endDate');
    	$oinfo = DB::table('orders as o')
    				->join('order_goods as og', 'o.id', '=', 'og.orders_id')
    				->select('o.id', 'o.order_sn', 'og.goods_name', 'o.nickname', 'o.pay_amount', 'o.order_time', 'o.pay_time')
                    ->whereBetween('o.order_time', [strtotime($startDate), strtotime($endDate)])
                    ->whereIn('o.order_status', [1,2,3,4])
                    ->where('o.order_type', 2)
                    ->orderBy('o.id', 'desc')
                    ->get()->map(function($item, $key){
                    	$item->pay_time = $item->pay_time ? date('Y-m-d H:i:s', $item->pay_time) : '';
                    	$item->order_time = $item->order_time ? date('Y-m-d H:i:s', $item->order_time) : '';
                    	return $item;
                    })->all();
    	return response()->json($oinfo);
    }

    public function getBuys()
    {
    	$startDate = request()->input('startDate');
    	$endDate = request()->input('endDate');
    	$oinfo = DB::table('orders as o')
    				->join('order_goods as og', 'o.id', '=', 'og.orders_id')
    				->select('o.id', 'o.order_sn', 'og.goods_name', 'o.nickname', 'o.pay_amount', 'o.order_time', 'o.pay_time')
                    ->whereBetween('o.order_time', [strtotime($startDate), strtotime($endDate)])
                    ->whereIn('o.order_status', [1,2,3,4])
                    ->whereIn('o.order_type', [0,1])
                    ->whereColumn('o.goods_amount', '>=', 'o.card_amount')
                    ->orderBy('o.id', 'desc')
                    ->get()->map(function($item, $key){
                    	$item->pay_time = $item->pay_time ? date('Y-m-d H:i:s', $item->pay_time) : '';
                    	$item->order_time = $item->order_time ? date('Y-m-d H:i:s', $item->order_time) : '';
                    	return $item;
                    })->all();
    	return response()->json($oinfo);
    }

}