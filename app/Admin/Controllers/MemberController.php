<?php

namespace App\Admin\Controllers;

use App\Member;
use App\Http\Controllers\Controller;
use Encore\Admin\Controllers\HasResourceActions;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Layout\Content;
use Encore\Admin\Show;
use Illuminate\Support\Facades\DB;

class MemberController extends Controller
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
            ->description('会员管理')
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
            ->description('会员管理')
            ->body($this->detail($id));
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
            ->header('Edit')
            ->description('description')
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
        $grid = new Grid(new Member);

        $grid->tools(function ($tools) {
            $tools->batch(function ($batch) {
                $batch->disableDelete();
            });
        });
        $grid->disableCreateButton();
        $grid->disableExport();

        $grid->id('ID');
        $grid->headimgurl('用户头像')->image(100, 100);
        $grid->nickname('会员名称');
        $grid->phone('手机号');

        $grid->column('mcb','折扣卡余额')->display(function (){
            $cinfo = \App\MemberCard::getInfo($this->id);
            $mcb = 0;
            foreach($cinfo as $k => $v)
            {
                $mcb += $v->balance;
            }
            return $mcb;
        });

        $grid->column('mcd','月卡剩余天数')->display(function (){
            $cinfo = \App\MemberCard::getInfo($this->id);
            $mcd = 0;
            foreach($cinfo as $k => $v)
            {
                if(strtotime($v['end_date']) >= time())
                {
                    $mcd += intval(floor((strtotime($v['end_date'])-time())/86400)) + 1;
                }
            }
            return $mcd;
        });
        $grid->column('ot','最近下单时间')->display(function (){
            return \App\Order::getInfo($this->id);
        });
        $grid->actions(function ($actions) {
            // $actions->disableDelete();
            $actions->disableEdit();
            $actions->disableView();
            $actions->append('<a href="/admin/members/addMemberCard/'.$actions->row->id.'" style="margin:0 5px;">添加会员卡</a>');
            $actions->append('<a href="/admin/members/'.$actions->row->id.'" style="margin-right:5px;">查看</a>');
            // $actions->append('<a href="/admin/members/delete/'.$actions->row->id.'" style="margin-right:5px;">删除</a>');
        });
        $grid->time('注册时间')->display(function(){
            return date('Y-m-d H:i:s', $this->time);
        });

        // $grid->open_id('Open id');
        // $grid->wx_open_id('Wx open id');
        // $grid->access_token('Access token');
        // $grid->expires('Expires');
        // $grid->refresh_token('Refresh token');
        // $grid->unionid('Unionid');
        // $grid->subscribe('Subscribe');
        // $grid->sex('Sex');
        // $grid->disablle('Disablle');
        // $grid->time('Time');
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
        $show = new Show(Member::findOrFail($id));

        $show->panel()->tools(function ($tools) {
                $tools->disableEdit();
                $tools->disableDelete();
            });

        $show->id('Id');
        $show->open_id('会员ID');
        $show->wx_open_id('open_id');
        $show->headimgurl('会员头像')->image(100, 100);
        $show->nickname('会员昵称');
        $show->phone('手机号');
        $show->sex('性别')->using(['2' => '女', '1' => '男']);
        $show->time('注册时间')->as(function($time){
            return date('Y-m-d H:i:s', $time);
        });
        // $show->access_token('Access token');
        // $show->expires('Expires');
        // $show->refresh_token('Refresh token');
        // // $show->unionid('Unionid');
        // $show->subscribe('Subscribe');
        // $show->disablle('Disablle');
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
        $form = new Form(new Member);

        $form->text('open_id', 'Open id');
        $form->text('wx_open_id', 'Wx open id');
        $form->text('access_token', 'Access token');
        $form->number('expires', 'Expires');
        $form->text('refresh_token', 'Refresh token');
        $form->text('unionid', 'Unionid');
        $form->text('nickname', 'Nickname');
        $form->switch('subscribe', 'Subscribe');
        $form->switch('sex', 'Sex');
        $form->text('headimgurl', 'Headimgurl');
        $form->switch('disablle', 'Disablle');
        $form->number('time', 'Time');
        $form->mobile('phone', 'Phone');

        return $form;
    }

    public function addMemberCard($id)
    {
        $content = new Content();
        $content->header('后台');
        $content->description('添加会员卡');
        $content->body($this->addMemberCardForm($id));
        return $content;

    }

    public function addMemberCardForm($id)
    {
        $form = new Form(new \App\MemberCard);
        $form->tools(function (Form\Tools $tools) {
            $tools->disableList();
        });
        $form->footer(function ($footer) {
            $footer->disableReset();
            $footer->disableViewCheck();
            $footer->disableEditingCheck();
            $footer->disableCreatingCheck();
        });
        $form->hidden('mid')->value($id);
        $goods = DB::table('goods')->where([
                    "is_delete" => 1,
                    "cate_id" => 3 //会员卡
                ])->select('id', 'goods_name')->get()->all();
        $options = [];
        foreach($goods as $k => $v)
        {
            $options[$v->id] = $v->goods_name;
        }
        $form->select('cid', '会员卡名称')->options($options)->loads(['balance', 'discount'], ['/api/balance', '/api/discount']);
        $form->select('balance', '金额');
        $form->select('discount', '折扣率');
        $form->setAction('/admin/members/saveMemberCard');
        return $form;

    }

    public function saveMemberCard()
    {
        $data = [];
        $data['cid'] = request()->input('cid');
        $data['balance'] = request()->input('balance');
        $data['discount'] = request()->input('discount');
        $data['mid'] = request()->input('mid');
        $goodsName = DB::table('goods')->where('id', $data['cid'])->value('goods_name');
        $data['cname'] = $goodsName;
        $date = date('Y-m-d');
        $date_end = date('Y-m-d', strtotime('next year'));
        $data['start_date'] = $date;
        $data['end_date'] = $date_end;
        $data['all_balance'] = $data['balance'];
        $data['ctype'] = 4;
        $data['status'] = 1;
        DB::table('member_cards')->insertGetId($data);
        return redirect('/admin/members');
    }

}
