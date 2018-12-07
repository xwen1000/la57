<?php

namespace App\Admin\Controllers;

use App\Http\Controllers\Controller;
use Encore\Admin\Layout\Content;
use Encore\Admin\Grid;
use Encore\Admin\Form;
use Encore\Admin\Controllers\HasResourceActions;
use App\Good;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class RoomController extends Controller 
{
    use HasResourceActions;

	public function index(Content $content)
	{
		return $content
            ->header('后台')
            ->description('客房管理')
            ->body($this->grid());
	}

    public function sale($id, Content $content)
    {
        return $content
            ->header('后台')
            ->description('客房管理')
            ->body($this->saleBody($id));
    }

    protected function saleBody($id)
    {
        $gInfo = DB::table('goods')
                        ->where('id', $id)
                        ->first();
        if($gInfo->cate_id == 8 ) {

            $gInfo->card_good_list = DB::table('card_goods')
                                        ->where('cid', $goodInfo->id)
                                        ->get()->all();
        }

        if($months = request()->input('months'))
        {
            $now = $months;
            $start = $now.'-01';
            $end =  date("Y-m-d", strtotime("+1 months", strtotime($start)));
        }else{
            $now = date('Y-m');
            $start = $now.'-01';
            $end =  date("Y-m-d", strtotime("+1 months", strtotime($start)));
        }
        $nextM =  date("Y-m", strtotime("+1 months", strtotime($now)));
        $lastM =  date("Y-m", strtotime("-1 months", strtotime($now)));
        $olist = DB::table('order_goods')
                    ->where('goods_id', $id)
                    ->whereBetween('goods_date', [$start, $end])
                    ->get()->all();
        $oarr = [];
        foreach ($olist as $k => $v) {
            $oarr[$v->goods_date] = 0;
            $oarr[$v->goods_date] += $v->quantity;
        }
        $resArr = [];
        while ($start < $end) {
            $nowNums = intval(empty($oarr[$start])?0:$oarr[$start]);
            $resArr[] = [
                'time'=>date("d",strtotime($start)).'号',
                'quantity'=>$gInfo->goods_stock-$nowNums,
                'now_nums'=>$nowNums
            ];
            $start = date("Y-m-d",strtotime("+1 day", strtotime($start)));
        }
        return view('admin.room-sale', [
            'resArr' => $resArr,
            'now' => $now,
            'nextM' => $nextM,
            'lastM' => $lastM,
            'id' => $id
        ]);
    }

	protected function grid()
    {
        $grid = new Grid(new Good);
        $grid->model()->where('is_delete', 1)->where('cate_id', 2);
        $grid->actions(function ($actions) {
            $actions->disableDelete();
            $actions->disableEdit();
            $actions->disableView();
            $actions->append('<a href="/admin/room/'.$actions->row->id.'/edit" style="margin-right:5px;">修改</a>');
            $actions->append('<a href="/admin/room/sale/'.$actions->row->id.'" style="margin-right:5px;">销售</a>');
            $actions->append('<a href="/admin/room/'.$actions->row->id.'/isselling/'.$actions->row->in_selling.'" style="margin-right:5px;">下架</a>');
            $actions->append('<a href="/admin/room/delete/'.$actions->row->id.'" style="margin-right:5px;">删除</a>');
        });
        $grid->id('ID');
        $grid->image_url('商品图片')->image(100,100);
        $grid->goods_name('商品名称');
        $grid->market_price('市场价格');
        $grid->group_price('会员价格');
        $grid->updated_at('更新时间');
        $grid->in_selling('商家状态')->display(function($isSelling){
            return $isSelling == 1 ? '上架' : '下架';
        });
        $grid->tools(function ($tools) {
            $tools->batch(function ($batch) {
                $batch->disableDelete();
            });
        });
        $grid->disableExport();
        $grid->filter(function($filter){
            $filter->disableIdFilter();
            $filter->like('goods_name', '产品名称');
            $filter->equal('in_selling', '状态')->select([0=>'下架', 1=>'上架']);
            $filter->equal('state', '推荐')->select([1=>'新品', 2=>'推荐']);
        });
        return $grid;
    }

    public function create(Content $content)
    {
        return $content
            ->header('后台')
            ->description('客房管理')
            ->body($this->form());
    }

    public function edit($id, Content $content)
    {
        return $content
            ->header('后台')
            ->description('客房管理')
            ->body($this->form()->edit($id));
    }

    protected function form()
    {
        $form = new Form(new Good);
        if(empty(request()->all()))
        {
            $form->setTitle('修改');
        }else{
            $form->setTitle('创建');
        }

        $form->tools(function (Form\Tools $tools) {
            $tools->disableDelete();
            $tools->disableView();
        });

        $form->footer(function ($footer) {
            $footer->disableReset();
            $footer->disableViewCheck();
            $footer->disableEditingCheck();
            $footer->disableCreatingCheck();
        });

        $form->text('goods_name', '房型');
        $form->text('goods_stock', '房间数量');
        $form->text('market_price', '市场价');
        $form->text('group_price', '促销价');
        $form->text('room_cw', '床位');
        $form->text('room_mj', '面积');
        $form->text('room_rs', '人数');
        $form->radio('is_recommend', '是否推荐')->options(['0' => '否', '1'=> '是']);
        $form->radio('is_new', '是否新品')->options(['0' => '否', '1'=> '是']);
        $form->text('goods_sort', '排序权重');
        $form->image('image_url', '房间展示图');
        $form->multipleImage('goods_imgs', '房间轮播图');
        $form->editor('goods_desc', '房间详情');
        $form->textarea('desc', '房间简介')->rows(10);

        return $form;
    }

    public function delete($id) 
    {
    	Good::del($id);
        return redirect('/admin/room');
    }

    public function updateS($id, $isSelling)
    {
        $data = ['in_selling'=>1]; //下架
        if($isSelling == 1)
        {
            $data['in_selling'] = 0; //上架
        }
        Good::upd($id, $data);
        return redirect('/admin/room');
    }

}