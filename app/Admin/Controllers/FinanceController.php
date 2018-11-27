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
    		'currYearMonth' => $carbon->year . '-' . $carbon->month,
    		'lastYearMonth' => $carbon->year-1 . '-' . '12',
    		'allTotal' => $retArr['allTotal'],
    		'goodsTotal' => $retArr['goodsTotal'],
    		'roomTotal' => $retArr['roomTotal'],
    		'buyTotal' => $retArr['buyTotal'],
    		'chargeTotal' => $retArr['chargeTotal'],
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

}