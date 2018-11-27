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
            $cateArr = [0=>'农产品', 1=>'客房', 2=>'伊甸卡'];
            return $cateArr[$orderType];
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
            $statusArr = [
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
            return $statusArr[$orderStatus];
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
        // $grid->goods_id('Goods id');
        // $grid->group_order_id('Group order id');
        // $grid->group_header('Group header');
        // $grid->pay_id('Pay id');
        // $grid->pay_sn('Pay sn');
        // $grid->group_buy('Group buy');
        // $grid->province_id('Province id');
        // $grid->province_name('Province name');
        // $grid->city_id('City id');
        // $grid->city_name('City name');
        // $grid->district_id('District id');
        // $grid->district_name('District name');
        // $grid->mobile('Mobile');
        // $grid->receive_name('Receive name');
        // $grid->nickname('Nickname');
        // $grid->order_goods('Order goods');
        // $grid->goods_amount('Goods amount');
        // $grid->shipping_address('Shipping address');
        // $grid->shipping_amount('Shipping amount');
        // $grid->shipping_time('Shipping time');
        // $grid->shipping_name('Shipping name');
        // $grid->shipping_code('Shipping code');
        // $grid->tracking_number('Tracking number');
        // $grid->received_time('Received time');
        // $grid->start_date('Start date');
        // $grid->end_date('End date');
        // $grid->book_name('Book name');
        // $grid->book_phone('Book phone');
        // $grid->book_days('Book days');
        // $grid->logistics('Logistics');
        // $grid->tables('Tables');
        // $grid->sf_img('Sf img');
        // $grid->is_dian('Is dian');
        // $grid->express_fee('Express fee');
        // $grid->pay_type('Pay type');
        // $grid->created_at('Created at');
        // $grid->updated_at('Updated at');

        return $grid;
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
         if($orderInfo->group_order_id > 0 && $orderInfo->pay_time > 0){
            $groupInfo = DB::table('groups')->where('id', $orderInfo->group_order_id)->first();
         }
         $nextOrder = DB::table('orders')->where('id', 'gt', $orderInfo->id)->first();
         $preOrder = DB::table('orders')->where('id', 'lt', $orderInfo->id)->first();
         $logInfo = DB::table('order_logs')->where('order_id', $id)->get();
        return view('admin.order', [
            'orderInfo' => $orderInfo,
            'payInfo' => $payInfo,
            'memberInfo' => $memberInfo,
            'logInfo' => $logInfo,
            'groupInfo' => $groupInfo,
            'nextOrder' => $nextOrder,
            'preOrder' => $preOrder
        ]);

        // $show->id('Id');
        // $show->buyer_id('Buyer id');
        // $show->order_sn('Order sn');
        // $show->goods_id('Goods id');
        // $show->group_order_id('Group order id');
        // $show->group_header('Group header');
        // $show->pay_id('Pay id');
        // $show->pay_sn('Pay sn');
        // $show->group_buy('Group buy');
        // $show->province_id('Province id');
        // $show->province_name('Province name');
        // $show->city_id('City id');
        // $show->city_name('City name');
        // $show->district_id('District id');
        // $show->district_name('District name');
        // $show->mobile('Mobile');
        // $show->receive_name('Receive name');
        // $show->nickname('Nickname');
        // $show->order_goods('Order goods');
        // $show->goods_amount('Goods amount');
        // $show->order_amount('Order amount');
        // $show->card_amount('Card amount');
        // $show->pay_amount('Pay amount');
        // $show->shipping_address('Shipping address');
        // $show->shipping_amount('Shipping amount');
        // $show->shipping_time('Shipping time');
        // $show->shipping_name('Shipping name');
        // $show->shipping_code('Shipping code');
        // $show->tracking_number('Tracking number');
        // $show->order_status('Order status');
        // $show->order_time('Order time');
        // $show->pay_time('Pay time');
        // $show->received_time('Received time');
        // $show->start_date('Start date');
        // $show->end_date('End date');
        // $show->book_name('Book name');
        // $show->book_phone('Book phone');
        // $show->order_type('Order type');
        // $show->book_days('Book days');
        // $show->logistics('Logistics');
        // $show->tables('Tables');
        // $show->sf_img('Sf img');
        // $show->is_dian('Is dian');
        // $show->express_fee('Express fee');
        // $show->pay_type('Pay type');
        // $show->created_at('Created at');
        // $show->updated_at('Updated at');

        return $show;
    }

    /**
     * Make a form builder.
     *
     * @return Form
     */
    protected function form()
    {
        $form = new Form(new Order);

        $form->number('buyer_id', 'Buyer id');
        $form->text('order_sn', 'Order sn');
        $form->number('goods_id', 'Goods id');
        $form->number('group_order_id', 'Group order id');
        $form->switch('group_header', 'Group header');
        $form->number('pay_id', 'Pay id');
        $form->text('pay_sn', 'Pay sn');
        $form->switch('group_buy', 'Group buy');
        $form->number('province_id', 'Province id');
        $form->text('province_name', 'Province name');
        $form->number('city_id', 'City id');
        $form->text('city_name', 'City name');
        $form->number('district_id', 'District id');
        $form->text('district_name', 'District name');
        $form->mobile('mobile', 'Mobile');
        $form->text('receive_name', 'Receive name');
        $form->text('nickname', 'Nickname');
        $form->textarea('order_goods', 'Order goods');
        $form->decimal('goods_amount', 'Goods amount');
        $form->decimal('order_amount', 'Order amount');
        $form->decimal('card_amount', 'Card amount');
        $form->decimal('pay_amount', 'Pay amount');
        $form->text('shipping_address', 'Shipping address');
        $form->decimal('shipping_amount', 'Shipping amount');
        $form->number('shipping_time', 'Shipping time');
        $form->text('shipping_name', 'Shipping name');
        $form->text('shipping_code', 'Shipping code');
        $form->text('tracking_number', 'Tracking number');
        $form->switch('order_status', 'Order status');
        $form->number('order_time', 'Order time');
        $form->number('pay_time', 'Pay time');
        $form->number('received_time', 'Received time');
        $form->date('start_date', 'Start date')->default(date('Y-m-d'));
        $form->date('end_date', 'End date')->default(date('Y-m-d'));
        $form->text('book_name', 'Book name');
        $form->text('book_phone', 'Book phone');
        $form->switch('order_type', 'Order type');
        $form->number('book_days', 'Book days');
        $form->text('logistics', 'Logistics');
        $form->number('tables', 'Tables');
        $form->textarea('sf_img', 'Sf img');
        $form->number('is_dian', 'Is dian');
        $form->number('express_fee', 'Express fee');
        $form->number('pay_type', 'Pay type');

        return $form;
    }
}
