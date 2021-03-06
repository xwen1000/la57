<?php

namespace App\Admin\Controllers;

use App\NpcCate;
use App\Http\Controllers\Controller;
use Encore\Admin\Controllers\HasResourceActions;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Layout\Content;
use Encore\Admin\Show;
use Illuminate\Support\Facades\DB;

class NpcCateController extends Controller
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
            ->description('点餐分类')
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
            ->description('点餐分类')
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
            ->description('点餐分类')
            ->body($this->form());
    }

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new NpcCate);

        $grid->model()->where('cid', 17)->orderBy('sort', 'asc');

        $grid->actions(function ($actions) {
            $actions->disableView();
            $actions->disableDelete();
            $actions->append('<a href="/admin/npccates/delete/'.$actions->row->id.'" style="margin-right:5px;"><i class="fa fa-trash"></i></a>');
        });

        $grid->tools(function ($tools) {
            $tools->batch(function ($batch) {
                $batch->disableDelete();
            });
        });

        $grid->disableExport();
        $grid->disableFilter();



        $grid->sort('排序权重');
        $grid->id('ID');
        $grid->cate_name('分类名称');

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
        $show = new Show(NpcCate::findOrFail($id));

        $show->id('Id');
        $show->cid('Cid');
        $show->cate_name('Cate name');
        $show->sort('Sort');
        $show->time('Time');
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
        $form = new Form(new NpcCate);

        $form->text('cate_name', '分类名称');
        $form->text('sort', '排序');
        $form->hidden('cid')->value(17);
        $form->hidden('time')->value(time());

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

        return $form;
    }

    public function delete($id)
    {
        
            $res = DB::table('goods')->where('ncp_cate_id', $id)->first();
            if($res)
            {
                admin_toastr('该分类下存在商品，不可删除!', 'error');
            }else{
                NpcCate::del($id);
            }
        return redirect('/admin/npccates');

    }
}
