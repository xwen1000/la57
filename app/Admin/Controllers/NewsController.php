<?php

namespace App\Admin\Controllers;

use App\News;
use App\Http\Controllers\Controller;
use Encore\Admin\Controllers\HasResourceActions;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Layout\Content;
use Encore\Admin\Show;
use Illuminate\Support\Facades\DB;

class NewsController extends Controller
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
            ->description('资讯管理')
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
            ->description('资讯管理')
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
            ->description('资讯管理')
            ->body($this->form());
    }

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new News);

        $grid->actions(function ($actions) {
            $actions->disableDelete();
            $actions->disableEdit();
            $actions->disableView();
            $actions->append('<a href="/admin/news/'.$actions->row->id.'/edit" style="margin-right:5px;">修改</a>');
            $status = $actions->row->status == 1 ? '下架' : '上架';
            $actions->append('<a href="/admin/news/'.$actions->row->id.'/status/'.$actions->row->status.'" style="margin-right:5px;">'.$status.'</a>');
        });

        $grid->tools(function ($tools) {
            $tools->batch(function ($batch) {
                $batch->disableDelete();
            });
        });
        $grid->disableExport();

        $grid->id('ID');
        $grid->image('资讯头图')->image(100, 100);
        $grid->title('资讯标题');
        $grid->cate_id('资讯类型')->display(function($cateId){
            return DB::table('news_cates')->where('id', $cateId)->value('cate_name');
        });

        $grid->filter(function($filter){

            $filter->disableIdFilter();    
            $filter->equal('status', '状态')->select([0=>'下架', 1=>'上架']);
        });

        // $grid->author('Author');
        // $grid->description('Description');
        // $grid->content('Content');
        // $grid->sort('Sort');
        // $grid->status('Status');
        // $grid->pv('Pv');
        // $grid->created_at('Created at');
        // $grid->updated_at('Updated at');

        return $grid;
    }

    public function updateS($id, $staus)
    {
        $data = ['status'=>1]; //发布
        if($staus == 1)
        {
            $data['status'] = 0; //下架
        }
        News::upd($id, $data);
        return redirect('/admin/news');
    }

    /**
     * Make a show builder.
     *
     * @param mixed $id
     * @return Show
     */
    protected function detail($id)
    {
        $show = new Show(News::findOrFail($id));

        $show->id('Id');
        $show->title('Title');
        $show->cate_id('Cate id');
        $show->author('Author');
        $show->image('Image');
        $show->description('Description');
        $show->content('Content');
        $show->sort('Sort');
        $show->status('Status');
        $show->pv('Pv');
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
        $form = new Form(new News);

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

        $form->text('title', '资讯标题');
        $catesArr = \App\NewsCate::getCates();
        $form->select('cate_id', '资讯类型')->options($catesArr);
        $form->text('sort', '排序权重');
        $form->image('image', '资讯头图');
        $form->editor('content', '资讯内容');
        // $form->text('author', 'Author');
        // $form->image('image', 'Image');
        // $form->text('description', 'Description');
        // $form->textarea('content', 'Content');
        // $form->switch('status', 'Status');
        // $form->number('pv', 'Pv');

        return $form;
    }
}
