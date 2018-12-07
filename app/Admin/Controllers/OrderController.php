<?php

namespace App\Admin\Controllers;

use App\Order;
use App\Http\Controllers\Controller;
use Encore\Admin\Controllers\HasResourceActions;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Layout\Content;
use Encore\Admin\Show;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{
    use HasResourceActions;

    protected static $statusArr = [
                    0 => '待支付',
                    1 => '待确认',
                    2 => '待发货',
                    3 => '配送中',
                    4 => '已签收',
                    5 => '已取消',
                    6 => '未发货退款处理中',
                    7 => '未发货退款成功',
                    8 => '已发货退款处理中',
                    9 => '已发货退款成功',
                ];

    protected static $cateArr = [0=>'农产品', 1=>'客房', 2=>'伊甸卡'];

    /**
     * Index interface.
     *
     * @param Content $content
     * @return Content
     */
    public function index(Content $content)
    {
        return $content
            ->header('后台')
            ->description('订单管理')
            ->body($this->grid());
    }

    public function realStock(Content $content)
    {
        return $content
            ->header('后台')
            ->description('订单管理')
            ->body($this->realStockGrid());
    }

    protected function realStockGrid()
    {
        $grid = new Grid(new \App\Good);
        $grid->model()->where(['in_selling' => 1, 'cate_id' => 1])->orderBy('goods_stock');
        $grid->id('ID');
        $grid->image_url('商品图片')->image(100,100);
        $grid->goods_name('商品名称');
        $grid->market_price('市场价格');
        $grid->group_price('会员价格');
        $grid->updated_at('更新时间');
        $grid->in_selling('商家状态')->display(function($isSelling){
            return $isSelling == 1 ? '上架' : '下架';
        });
        $grid->actions(function ($actions) {
            $actions->disableView();
            $actions->disableDelete();
            $actions->disableEdit();
            $isSelling = $actions->row->in_selling == 1 ? '下架' : '上架';
            $actions->append('<a href="/admin/orders/'.$actions->row->id.'/edit" style="margin-right:5px;">修改</a>');
            $actions->append('<a href="/admin/orders/'.$actions->row->id.'/isselling/'.$actions->row->in_selling.'" style="margin-right:5px;">'.$isSelling.'</a>');
        });
        $grid->tools(function ($tools) {
            $tools->batch(function ($batch) {
                $batch->disableDelete();
            });
        });
        $grid->disableCreateButton();
        $grid->disableExport();
        $grid->disableFilter();

        return $grid;
    }

    public function updateS($id, $isSelling)
    {
        $data = ['in_selling'=>1]; //下架
        if($isSelling == 1)
        {
            $data['in_selling'] = 0; //上架
        }
        \App\Good::upd($id, $data);
        return redirect('/admin/orders/stock/real');
    }

    /**
     * Show interface.
     *
     * @param mixed $id
     * @param Content $content
     * @return Content
     */
    public function show($id, Content $content)
    {
        return $content
            ->header('后台')
            ->description('订单管理')
            ->row($this->detail($id));
    }

    /**
     * Edit interface.
     *
     * @param mixed $id
     * @param Content $content
     * @return Content
     */
    public function edit($id, Content $content)
    {
        return $content
            ->header('后台')
            ->description('订单管理')
            ->body($this->form()->edit($id));
    }

    /**
     * Create interface.
     *
     * @param Content $content
     * @return Content
     */
    public function create(Content $content)
    {
        return $content
            ->header('Create')
            ->description('description')
            ->body($this->form());
    }

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new Order);

        $grid->id('ID');
        $grid->order_sn('订单编号');
        $grid->buyer_id('购买用户')->display(function($buyerId){
            $nickname = DB::table('members')->where('id', $buyerId)->value('nickname');
            return $nickname;
        });
        $grid->order_amount('订单金额');
        $grid->card_amount('卡付金额');
        $grid->pay_amount('实付金额');
        $grid->order_type('购买类型')->display(function($orderType){
            
            return self::$cateArr[$orderType];
        });
        $grid->order_time('下单时间')->display(function($orderTime){
            return date('Y-m-d H:i:s', $orderTime);
        });
        $grid->pay_time('支付时间')->display(function($payTime){
            if($payTime){
                return date('Y-m-d H:i:s', $payTime);
            }else {
                return '无';
            }
        });
        $grid->order_status('订单状态')->display(function($orderStatus){
            return self::$statusArr[$orderStatus];
        });
        $grid->actions(function ($actions) {
            $actions->disableDelete();
            $actions->disableEdit();
        });
        $grid->disableCreateButton();
        $grid->disableExport();
        $grid->tools(function ($tools) {
            $tools->batch(function ($batch) {
                $batch->disableDelete();
            });
        });

        $grid->filter(function($filter){

            $filter->disableIdFilter();
            $filter->equal('order_sn', '订单号');
            $filter->where(function ($query) {
                $query->whereHas('members', function ($query) {
                    $query->where('nickname', 'like', "%{$this->input}%");
                });

            }, '买家');
            $filter->equal('order_status', '订单状态')->select(self::$statusArr);
            $filter->between('order_time', '下单时间')->date();
    });


        return $grid;
    }

    protected function getInfo($id)
    {
        $retArr = [];
        $orderInfo = DB::table('orders')->where('id', $id)->first();
        $payInfo = DB::table('paylogs')->where('id', $orderInfo->pay_id)->first();
        $orderInfo->order_goods = unserialize($orderInfo->order_goods);
        $orderInfo->OrderGoods = DB::table('order_goods')->where('orders_id', $id)->get()->all();
        if($orderInfo->order_type == 1)//预定房间
         {
             $tinfo = $orderInfo->OrderGoods[0];
             $tinfo->goods_name =  $tinfo->goods_name.' '.$orderInfo->start_date.'~'.$orderInfo->end_date.' '.$orderInfo->book_days.'天';
             $orderInfo->tinfo = $tinfo;
         }
         $memberInfo = DB::table('members')->where('id', $orderInfo->buyer_id)->first();
         $groupInfo = null;
         if($orderInfo->group_order_id > 0 && $orderInfo->pay_time > 0){
            $groupInfo = DB::table('groups')->where('id', $orderInfo->group_order_id)->first();
         }
         $nextOrder = DB::table('orders')->where('id', 'gt', $orderInfo->id)->first();
         $preOrder = DB::table('orders')->where('id', 'lt', $orderInfo->id)->first();
         $logInfo = DB::table('order_logs')->where('order_id', $id)->get()->all();
         $orderInfo->order_time = date('Y-m-d H:i:s', $orderInfo->order_time);
         if($payInfo->pay_done_time)
         {
            $payInfo->pay_done_time = date('Y-m-d H:i:s', $payInfo->pay_done_time);
         }else{
            $payInfo->pay_done_time = '';
         }
         if($orderInfo->shipping_time)
         {
            $orderInfo->shipping_time = date('Y-m-d H:i:s', $orderInfo->shipping_time);
         }else{
            $orderInfo->shipping_time = '';
         }
         return [
            'orderInfo' => $orderInfo,
            'payInfo' => $payInfo,
            'memberInfo' => $memberInfo,
            'logInfo' => $logInfo,
            'groupInfo' => $groupInfo,
            'nextOrder' => $nextOrder,
            'preOrder' => $preOrder
         ];
    }

    public function printInfo($id, Content $content)
    {
        return $content
            ->header('后台')
            ->description('订单管理')
            ->row($this->getPrintInfo($id));
       
    }

    public function member($id, Content $content)
    {
        return $content
            ->header('后台')
            ->description('订单管理')
            ->row($this->getMemberInfo($id));
       
    }

    protected function getMemberInfo($id)
    {
        $memberInfo = DB::table('members')->where('id', $id)->first();
        $memberInfo->time = date('Y-m-d H:i:s', $memberInfo->time);
        $cinfo = DB::table('member_cards')
                    ->where(['mid'=>$id, 'ctype'=>3, 'status'=>1])
                    ->get()->all();
        $memberInfo->member_cardbalance = 0;
        foreach($cinfo as $k=>$v)
        {
            $memberInfo->member_cardbalance += $v->balance;
        }
        $dinfo = DB::table('member_cards')
                    ->where(['mid'=>$id, 'ctype'=>4, 'status'=>1])
                    ->get()->all();
        foreach($dinfo as $k=>$v)
        {
            if(strtotime($v->end_date) >= time())
            {
                $memberInfo->member_carddays = intval(floor((strtotime($v->end_date)-time())/86400)) + 1;
            }
            $memberInfo->member_cardbalance += $v->balance;
        }
        $memberInfo->address_list = DB::table('address')
                                        ->where('member_id', $id)
                                        ->get()->all();
        $memberInfo->order_list = DB::table('orders')
                                    ->where('buyer_id', $id)
                                    ->whereIn('order_status', [0,1,2,3,4])
                                    ->get()->map(function($item, $key){
                                         $order_name = '';
                                         $garr = DB::table('order_goods')
                                                    ->where('orders_id', $item->id)
                                                    ->get()->all();
                                         if(!empty($garr) && count($garr)>1 && $item->order_type == 0)
                                         {
                                             $order_name = $garr[0]->goods_name . '等';
                                         }
                                         else if(!empty($garr))
                                         {
                                             $order_name = $garr[0]->goods_name;
                                         }
                                        $item->order_name = $order_name;
                                        
                                        $item->order_status = self::$statusArr[$item->order_status];
                                        $item->order_time = date('Y-m-d H:i:s', $item->order_time);
                                        
                                        return $item;
                                    })->all();
        return view('admin.member', [
            'memberInfo' => $memberInfo
        ]);
    }

    protected function getPrintInfo($id)
    {
         $retArr = $this->getInfo($id);
         return view('admin.print-info', [
            'orderInfo' => $retArr['orderInfo'],
            'payInfo' => $retArr['payInfo'],
            'memberInfo' => $retArr['memberInfo'],
         ]);
    }

    /**
     * Make a show builder.
     *
     * @param mixed $id
     * @return Show
     */
    protected function detail($id)
    {
        // $show = new Show(Order::findOrFail($id));

        $retArr = $this->getInfo($id);
         // dd($orderInfo, $payInfo, $memberInfo, $logInfo, $groupInfo, $nextOrder, $preOrder);
        return view('admin.order', [
            'orderInfo' => $retArr['orderInfo'],
            'payInfo' => $retArr['payInfo'],
            'memberInfo' => $retArr['memberInfo'],
            'logInfo' => $retArr['logInfo'],
            'groupInfo' => $retArr['groupInfo'],
            'nextOrder' => $retArr['nextOrder'],
            'preOrder' => $retArr['preOrder']
        ]);


        return $show;
    }

    /**
     * Make a form builder.
     *
     * @return Form
     */
    protected function form()
    {
        $form = new Form(new \App\Good);

        $form->text('goods_name', '商品名称');
        $cateArr = \App\GoodsCate::getCates();
        $form->select('cate_id', '商品种类')->options($cateArr);
        $npcCatesArr = \App\NpcCate::getNpcCates();
        $form->select('ncp_cate_id', '产品分类')->options($npcCatesArr);
        $form->text('goods_stock', '商品库存');
        $form->text('market_price', '市场价');
        $form->text('group_price', '促销价');
        $form->text('goods_sn', '商品货号');
        $form->text('heavy', '重量/斤');
        $form->text('manjian', '满件包邮/斤');
        $form->text('mane', '满件包邮/元');
        $form->radio('is_recommend', '是否推荐')->options(['0' => '否', '1'=> '是']);
        $form->radio('is_new', '是否新品')->options(['0' => '否', '1'=> '是']);
        $form->text('goods_sort', '排序权重');
        $form->image('image_url', '商品展示图');
        $form->multipleImage('goods_imgs', '商品轮播图');
        $form->editor('goods_desc', '商品详情');
        $form->textarea('desc', '商品简介')->rows(10);

        $form->tools(function (Form\Tools $tools) {
            $tools->disableDelete();
            $tools->disableView();
            $tools->disableList();
        });

        $form->footer(function ($footer) {
            $footer->disableReset();
            $footer->disableViewCheck();
            $footer->disableEditingCheck();
            $footer->disableCreatingCheck();
        });

        $form->saved(function (Form $form) {

            return redirect('/admin/orders/stock/real');

        });

        return $form;
    }
}
