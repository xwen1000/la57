<?php

namespace App\Admin\Controllers;

use App\Code;
use App\Http\Controllers\Controller;
use Encore\Admin\Controllers\HasResourceActions;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Layout\Content;
use Encore\Admin\Show;
use Illuminate\Support\Facades\DB;
use App\Admin\Extensions\Tools\Inselling;
use Illuminate\Support\Facades\Storage;
use App\Admin\Extensions\Down;

class TablesController extends Controller
{
    use HasResourceActions;

    public function index(Content $content)
    {
        return $content
            ->header('后台')
            ->description('餐桌管理')
            ->body($this->grid());
    }

    public function create(Content $content)
    {
        return $content
            ->header('后台')
            ->description('餐桌管理')
            ->body($this->form());
    }

    protected function form()
    {
        $form = new Form(new Code);
        $form->footer(function ($footer) {
            $footer->disableReset();
            $footer->disableViewCheck();
            $footer->disableEditingCheck();
            $footer->disableCreatingCheck();
        });
    	$classArr = [1 => '大厅', 2 => '包房', 3 => '卡座'];
        $form->select('class', '房间类型')->options($classArr);
        $form->text('table', '餐桌号');
        $code = 'http://la57.com/uploads/code/code_1540452378425.jpg';
        $form->hidden('code')->value($code);
        return $form;
    }

    protected function grid()
    {
        $grid = new Grid(new Code);
        $grid->model()->orderBy('id', 'desc');

        $grid->actions(function ($actions) {
            $actions->disableEdit();
            $actions->disableView();
            // /admin/tables/down/'.$actions->row->id.'
            $actions->append(new Down($actions->row->id));
        });

        $grid->tools(function ($tools) {
            $tools->batch(function ($batch) {
                $batch->disableDelete();
            });
        });

        $grid->disableExport();
        $grid->disableFilter();

        $grid->code('商品图片')->image(100, 100);
        $grid->table('桌号');
        $grid->class('类别')->display(function($class){
        	$classArr = [1 => '大厅', 2 => '包房', 3 => '卡座'];
        	return $classArr[$class];
        });
        return $grid;
    }

    public function down($id)
    {
    	$code = DB::table('codes')->where('id', $id)->value('code');
    	$path = public_path('uploads').'/'.substr($code, strpos($code, 'code'));
    	return response()->download($path);
    }

}