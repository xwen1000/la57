<?php

namespace App\Admin\Controllers;

use App\Banner;
use App\Http\Controllers\Controller;
use Encore\Admin\Controllers\HasResourceActions;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Layout\Content;
use Encore\Admin\Show;

class BannerController extends Controller
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
            ->description('广告管理')
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
            ->description('广告管理')
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
            ->description('广告管理')
            ->body($this->form());
    }

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new Banner);

        $grid->id('ID');
        $grid->banner_name('广告名称');
        $grid->banner_type('广告类型')->display(function($bannerType){
            return $bannerType == 1 ? '分类' : '产品';
        });
        $grid->start_time('结束时间')->display(function($startTime){
            return date('Y-m-d H:i:s', $startTime);
        });
        $grid->end_time('上架状态')->display(function($endTime){
            return date('Y-m-d H:i:s', $endTime);
        });

        $grid->actions(function ($actions) {
            $actions->disableView();
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

    /**
     * Make a show builder.
     *
     * @param mixed $id
     * @return Show
     */
    protected function detail($id)
    {
        $show = new Show(Banner::findOrFail($id));

        $show->id('Id');
        $show->banner_name('Banner name');
        $show->image_url('Image url');
        $show->target_url('Target url');
        $show->start_time('Start time');
        $show->end_time('End time');
        $show->banner_type('Banner type');
        $show->cate_id('Cate id');
        $show->banner_sort('Banner sort');
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
        $form = new Form(new Banner);

        $form->text('banner_name', '广告名称');
        $form->select('banner_type', '广告类别')->options([1=>'分类', 0=>'产品']);
        $form->image('image_url', '广告图片');
        $form->text('target_url', '地址');
        $form->text('banner_sort', '排序');

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
}
