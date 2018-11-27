<?php

namespace App\Admin\Controllers;

use App\Http\Controllers\Controller;
use Encore\Admin\Controllers\Dashboard;
use Encore\Admin\Layout\Column;
use Encore\Admin\Layout\Content;
use Encore\Admin\Layout\Row;
use Encore\Admin\Widgets\Box;

use Encore\Admin\Facades\Admin;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class IndexController extends Controller
{
    public function index(Content $content)
    {
        return $content
            ->header('后台')
            ->description('主页')
            ->row($this->body());
    }
    protected function body()
    {
    	$ginfo = DB::table('goods')->where('in_selling', 1)->select('id', 'goods_name', 'cate_id', 'goods_stock')->get()->toArray();
        $gnum = 0;
        $rnum = 0;
        foreach ($ginfo as $k => $v) {
            if($v->cate_id == 1)
            {
                $gnum++;
            }else if($v->cate_id == 2){
                $rnum++;
            }
        }
        $nnum = DB::table('news')->count();
        $mnum = DB::table('members')->count();
        $carbon = Carbon::now();
        $startMonth = $carbon->startOfMonth()->timestamp;
        $endMonth = $carbon->endOfMonth()->timestamp;
        $oinfo = DB::table('orders')
                    ->whereBetween('order_time', [$startMonth, $endMonth])
                    ->whereIn('order_status', [1,2,3,4])
                    ->get()->toArray();
        $allTotal = 0;
        $goodsTotal = 0;
        $roomTotal = 0;
        $buyTotal = 0;
        $chargeTotal = 0;
        foreach ($oinfo as $k => $v) {
            $allTotal += $v->goods_amount - $v->card_amount;
            if($v->order_type != 2)
            {
                if($v->order_type == 0)
                {
                    $goodsTotal += $v->goods_amount;
                }
                else if($v->order_type == 1)
                {
                    $roomTotal += $v->goods_amount;
                }
            }
            if($v->order_type == 0 || $v->order_type == 1)
            {
                $buyTotal += ($v->goods_amount - $v->card_amount);
            }
            else if($v->order_type == 2)
            {
                $chargeTotal += $v->goods_amount;
            }
        }
        $ninfo = DB::table('news')
                    ->select('id', 'title', 'created_at')
                    ->where('status', 1)
                    ->orderBy('created_at', 'desc')
                    ->limit(9)
                    ->get()->map(function($item, $key){
                        $item->created_at = date('Y-m-d H:i', strtotime($item->created_at));
                        return $item;
                    })->toArray();
        $server = array_get($_SERVER, 'SERVER_SOFTWARE');
        $php = 'PHP/' . PHP_VERSION;
        $res = DB::select('select version()');
        $res = (array)$res[0];
        $database = 'MySql/' . $res['version()'];
        $userInfo = DB::table('admin_users')
                        ->select('username', 'last_login_time', 'last_login_ip')
                        ->find(Admin::user()->id);
        $userInfo->last_login_time = date('Y-m-d H:i', strtotime($userInfo->last_login_time));
        $binfo = DB::table('basesets')->first();
        return view('admin.index',[
            'gnum' => $gnum,
            'rnum' => $rnum,
            'nnum' => $nnum,
            'mnum' => $mnum,
            'allTotal' => $allTotal,
            'goodsTotal' => $goodsTotal,
            'roomTotal' => $roomTotal,
            'buyTotal' => $buyTotal,
            'chargeTotal' => $chargeTotal,
            'ninfo' => $ninfo,
            'server' => $server,
            'php' => $php,
            'database' => $database,
            'userInfo' => $userInfo,
            'binfo' => $binfo
        ]);
    }
}
