<?php

namespace App\Admin\Controllers;

use App\Good;
use App\Http\Controllers\Controller;
use Encore\Admin\Controllers\HasResourceActions;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Layout\Content;
use Encore\Admin\Show;
use Illuminate\Support\Facades\DB;
use App\Admin\Extensions\Tools\Inselling;

class GoodController extends Controller
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
            ->description('产品管理')
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
            ->header('Detail')
            ->description('description')
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
            ->header('后台')
            ->description('商品管理')
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
            ->header('后台')
            ->description('商品管理')
            ->body($this->form());
    }

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new Good);
        $grid->model()->where('is_delete', 1);
        $grid->actions(function ($actions) {
            $actions->disableDelete();
            $actions->disableEdit();
            $actions->disableView();
            $isSelling = $actions->row->in_selling == 1 ? '下架' : '上架';
            $actions->append('<a href="/admin/goods/'.$actions->row->id.'/edit" style="margin-right:5px;">修改</a>');
            $actions->append('<a href="/admin/goods/'.$actions->row->id.'/isselling/'.$actions->row->in_selling.'" style="margin-right:5px;">'.$isSelling.'</a>');
            $actions->append('<a href="/admin/goods/delete/'.$actions->row->id.'" style="margin-right:5px;">删除</a>');
        });

        $grid->id('ID');
        $grid->image_url('商品图片')->image(100,100);
        $grid->goods_name('商品名称');
        $grid->market_price('市场价格');
        $grid->group_price('会员价格');
        // $grid->alone_price('Alone price');
        $grid->goods_stock('商品库存');
        $grid->updated_at('更新时间');
        $grid->in_selling('商家状态')->display(function($isSelling){
            return $isSelling == 1 ? '上架' : '下架';
        });
        $grid->tools(function ($tools) {
            $tools->batch(function ($batch) {
                $batch->disableDelete();
            });
        });
        $grid->disableCreateButton();
        $grid->disableExport();
        $grid->tools(function ($tools) {
            $tools->batch(function ($batch) {
                $batch->add('批量上架', new Inselling(0));
                $batch->add('批量下架', new Inselling(1));
            });
        });
        $grid->filter(function($filter){

            $filter->disableIdFilter();    
            // $filter->expand();
            $filter->like('goods_name', '产品名称');
            $cateArr = \App\GoodsCate::getCates();
            $filter->equal('cate_id', '产品种类')->select($cateArr);
            $npcCatesArr = \App\NpcCate::getNpcCates();
            $filter->equal('ncp_cate_id', '产品分类')->select($npcCatesArr);
            $filter->equal('in_selling', '状态')->select([0=>'下架', 1=>'上架']);
            $filter->equal('state', '推荐')->select([1=>'新品', 2=>'推荐']);
        });

        // $grid->cate_id('Cate id');
        // $grid->ncp_cate_id('Ncp cate id');
        // $grid->goods_desc('Goods desc');
        // $grid->goods_imgs('Goods imgs');
        // $grid->group_number('Group number');
        // $grid->sell_count('Sell count');
        // $grid->limit_buy('Limit buy');
        // $grid->goods_sort('Goods sort');
        // $grid->sell_type('Sell type');
        // $grid->time('Time');
        // $grid->card_balance('Card balance');
        // $grid->is_delete('Is delete');
        // $grid->unit('Unit');
        // $grid->goods_sn('Goods sn');
        // $grid->is_recommend('Is recommend');
        // $grid->is_new('Is new');
        // $grid->desc('Desc');
        // $grid->heavy('Heavy');
        // $grid->mane('Mane');
        // $grid->manjian('Manjian');
        // $grid->room_cw('Room cw');
        // $grid->room_mj('Room mj');
        // $grid->room_rs('Room rs');
        // $grid->created_at('Created at');

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
        $show = new Show(Good::findOrFail($id));

        $show->id('Id');
        $show->cate_id('Cate id');
        $show->ncp_cate_id('Ncp cate id');
        $show->goods_name('Goods name');
        $show->image_url('Image url');
        $show->goods_desc('Goods desc');
        $show->goods_imgs('Goods imgs');
        $show->market_price('Market price');
        $show->group_price('Group price');
        $show->alone_price('Alone price');
        $show->group_number('Group number');
        $show->sell_count('Sell count');
        $show->limit_buy('Limit buy');
        $show->goods_stock('Goods stock');
        $show->in_selling('In selling');
        $show->goods_sort('Goods sort');
        $show->sell_type('Sell type');
        $show->time('Time');
        $show->card_balance('Card balance');
        $show->is_delete('Is delete');
        $show->unit('Unit');
        $show->goods_sn('Goods sn');
        $show->is_recommend('Is recommend');
        $show->is_new('Is new');
        $show->desc('Desc');
        $show->heavy('Heavy');
        $show->mane('Mane');
        $show->manjian('Manjian');
        $show->room_cw('Room cw');
        $show->room_mj('Room mj');
        $show->room_rs('Room rs');
        $show->created_at('Created at');
        $show->updated_at('Updated at');

        return $show;
    }

    /**
     * Make a form builder.
     *
     * @return Form
     */
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
        // $form->textarea('goods_desc', '商品详情')->rows(10);
        $form->textarea('desc', '商品简介')->rows(10);
        // $form->decimal('alone_price', 'Alone price');
        // $form->number('group_number', 'Group number');
        // $form->number('sell_count', 'Sell count');
        // $form->switch('limit_buy', 'Limit buy');
        // $form->switch('in_selling', 'In selling');
        // $form->switch('sell_type', 'Sell type');
        // $form->number('time', 'Time');
        // $form->number('card_balance', 'Card balance');
        // $form->switch('is_delete', 'Is delete');
        // $form->text('unit', 'Unit');
        // $form->textarea('desc', 'Desc');
        // $form->text('room_cw', 'Room cw');
        // $form->text('room_mj', 'Room mj');
        // $form->text('room_rs', 'Room rs');

        return $form;
    }

    /**
    * 删除
    */
    public function delete($id) 
    {
        DB::table('goods')->where('id', $id)->update(['is_delete'=>2]);
        return redirect('/admin/goods');
    }

    /**
    * 上架或下架
    */
    public function updateS($id, $isSelling)
    {
        $data = ['in_selling'=>1]; //下架
        if($isSelling == 1)
        {
            $data['in_selling'] = 0; //上架
        }
        DB::table('goods')->where('id', $id)->update($data);
        return redirect('/admin/goods');
    }
    /**
    * 批量上架下架
    */
    public function batchSelling()
    {

        $inSelling = request()->input('action');
        DB::table('goods')->update(['in_selling'=>$inSelling]);
    }
}
