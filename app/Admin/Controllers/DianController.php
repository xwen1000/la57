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

class DianController extends Controller
{
    use HasResourceActions;

    public function index(Content $content)
    {
        return $content
            ->header('后台')
            ->description('点餐管理')
            ->body($this->grid());
    }

    protected function grid()
    {
        $grid = new Grid(new Good);
        $grid->model()->where(['is_delete' => 1, 'cate_id' => 1]);
        $grid->actions(function ($actions) {
            $actions->disableDelete();
            $actions->disableEdit();
            $actions->disableView();
            $isSelling = $actions->row->in_selling == 1 ? '下架' : '上架';
            $actions->append('<a href="/admin/dian/'.$actions->row->id.'/edit" style="margin-right:5px;">修改</a>');
            $actions->append('<a href="/admin/dian/'.$actions->row->id.'/isselling/'.$actions->row->in_selling.'" style="margin-right:5px;">'.$isSelling.'</a>');
            $actions->append('<a href="/admin/dian/delete/'.$actions->row->id.'" style="margin-right:5px;">删除</a>');
        });

        $grid->id('ID');
        $grid->image_url('商品图片')->image(100,100);
        $grid->goods_name('商品名称');
        $grid->market_price('市场价格');
        $grid->group_price('会员价格');
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
        $grid->disableExport();
        
        $grid->filter(function($filter){

            $filter->disableIdFilter();
            $filter->like('goods_name', '产品名称');
            $npcCatesArr = \App\NpcCate::getNpcCates();
            $filter->equal('ncp_cate_id', '产品分类')->select($npcCatesArr);
            $filter->equal('in_selling', '状态')->select([0=>'下架', 1=>'上架']);
            $filter->equal('state', '推荐')->select([1=>'新品', 2=>'推荐']);
        });

        return $grid;
    }

    public function create(Content $content)
    {
        return $content
            ->header('后台')
            ->description('点餐管理')
            ->body($this->form());
    }

    protected function form()
    {
        $form = new Form(new Good);
        if(empty(request()->all()))
        {
            $form->setTitle('创建');
        }else{
            $form->setTitle('修改');
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
        $npcCatesArr = \App\NpcCate::getNpcCates();
        $form->select('ncp_cate_id', '产品分类')->options($npcCatesArr);
        $form->text('goods_stock', '商品库存');
        $form->text('market_price', '市场价');
        $form->text('group_price', '促销价');
        $form->radio('is_recommend', '是否推荐')->options(['0' => '否', '1'=> '是']);
        $form->radio('is_new', '是否新品')->options(['0' => '否', '1'=> '是']);
        $form->text('goods_sort', '排序权重');
        $form->image('image_url', '商品展示图');
        $form->multipleImage('goods_imgs', '商品轮播图');
        $form->editor('goods_desc', '商品详情');
        $form->textarea('desc', '商品简介')->rows(10);

        return $form;
    }

    /**
    * 删除
    */
    public function delete($id) 
    {
        Good::del($id);
        return redirect('/admin/dian');
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
        Goods::upd($id, $data);
        return redirect('/admin/dian');
    }

    public function edit($id, Content $content)
    {
        return $content
            ->header('后台')
            ->description('点餐管理')
            ->body($this->form()->edit($id));
    }

}