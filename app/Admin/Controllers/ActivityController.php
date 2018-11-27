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

class ActivityController extends Controller
{
    use HasResourceActions;

    public function index(Content $content)
    {
        return $content
            ->header('后台')
            ->description('套餐管理')
            ->body($this->grid());
    }

    protected function grid()
    {
        $grid = new Grid(new Good);
        $grid->model()->where(['is_delete' => 1, 'cate_id' => 8])->orderBy('goods_sort');
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

        $grid->actions(function ($actions) {
            $actions->disableDelete();
            $actions->disableEdit();
            $actions->disableView();
            $isSelling = $actions->row->in_selling == 1 ? '下架' : '上架';
            $actions->append('<a href="/admin/activities/'.$actions->row->id.'/edit" style="margin-right:5px;">修改</a>');
            $actions->append('<a href="/admin/activities/'.$actions->row->id.'/isselling/'.$actions->row->in_selling.'" style="margin-right:5px;">'.$isSelling.'</a>');
            $actions->append('<a href="/admin/activities/delete/'.$actions->row->id.'" style="margin-right:5px;">删除</a>');
        });

        $grid->tools(function ($tools) {
            $tools->batch(function ($batch) {
                $batch->disableDelete();
            });
        });
        $grid->disableExport();
        $grid->disableFilter();

        return $grid;
    }

    public function create(Content $content)
    {
        return $content
            ->header('后台')
            ->description('套餐管理')
            ->body($this->form());
    }

    public function edit($id, Content $content)
    {
        return $content
            ->header('后台')
            ->description('套餐管理')
            ->body($this->form()->edit($id));
    }

    protected function form()
    {
        $form = new Form(new Good);
        $urlArr = explode('/', request()->url());
        $cardGoods = [];
        if($urlArr[count($urlArr)-1] == 'edit')
        {
            $form->setTitle('修改');
            $id = $urlArr[count($urlArr)-2];
            $form->hidden('id')->value($id);
            $cateId = DB::table('goods')
                        ->where('id', $id)
                        ->value('cate_id');
        if($cateId == 8 ) {
            $cardGoods = DB::table('cards_goods')
                            ->where('cid', $id)
                            ->get();
        }
        }else if($urlArr[count($urlArr)-1] == 'create'){
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
        $form->text('goods_stock', '商品库存');
        $form->text('market_price', '市场价');
        $form->text('group_price', '促销价');
        $form->text('goods_sn', '商品货号');
        $form->text('unit', '单位');
        $form->radio('is_recommend', '是否推荐')->options(['0' => '否', '1'=> '是']);
        $form->radio('is_new', '是否新品')->options(['0' => '否', '1'=> '是']);
        $form->text('goods_sort', '排序权重');
        $form->image('image_url', '商品展示图');
        $form->multipleImage('goods_imgs', '商品轮播图');
        $form->editor('goods_desc', '商品详情');
        $form->hidden('cate_id')->value(8);
        
        $cates = DB::table('goods_cates')
                    ->orderBy('sort', 'desc')
                    ->orderBy('id', 'desc')
                    ->get();

        $ncpGoods = DB::table('goods')
                        ->where('cate_id', 1)
                        ->orderBy('goods_sort')
                        ->get()->all();
        $ncpArr = [];
        foreach($ncpGoods as $k => $v)
        {
            $ncpArr[$v->id] = $v->goods_name;
        }
        $form->html(function()use($ncpArr, $cardGoods){
            return view('admin.ncp-info',[
                'ncp' => $ncpArr,
                'cardGoods' => $cardGoods
            ]);
        }, '农产品信息');

        $form->saving(function (Form $form) {
            if($form->id)
            {
                DB::table('cards_goods')->where('cid', $form->id)->delete(); 
                $addArr = [];
                if($form->gid){
                    foreach ($form->gid as $k => $v) {
                        $addArr[] = ['cid'=>$form->id, 'gid'=>$v, 'gname'=>$form->gname[$k], 'rate'=>$form->grate[$k]];
                    }
                }
            }else{
                $addArr = [];
                if($form->gid)
                {
                    foreach ($form->gid as $k => $v) {
                        $addArr[] = ['cid'=>0, 'gid'=>$v, 'gname'=>$form->gname[$k], 'rate'=>$form->grate[$k]];
                    }
                }
            }
            if($addArr)
            {
                DB::table('cards_goods')->insert($addArr);
            }

        });

        $form->saved(function (Form $form) {
            DB::table('cards_goods')
                ->where('cid', 0)
                ->update(['cid'=>$form->model()->id]);
        });

        return $form;
    }

    /**
    * 删除
    */
    public function delete($id) 
    {
        Good::del($id);
        return redirect('/admin/activities');
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
        Good::upd($id, $data);
        return redirect('/admin/activities');
    }

}
