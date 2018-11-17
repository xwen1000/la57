<?php

namespace App\Admin\Controllers;

use App\Baseset;
use App\Http\Controllers\Controller;
use Encore\Admin\Controllers\HasResourceActions;
use Encore\Admin\Form;
use Encore\Admin\Layout\Content;
use Encore\Admin\Facades\Admin;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class BasesetController extends Controller
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
            ->description('基础设置')
            ->body($this->form());
    }


    /**
     * Make a form builder.
     *
     * @return Form
     */
    protected function form()
    {
       return Admin::form(Baseset::class, function(Form $form){
            $data = DB::table('basesets')->first();
            $form->setTitle('设置');
            $form->tools(function (Form\Tools $tools) {
                $tools->disableList();
            });
            $form->text('web_name', '网站名称')->value($data->web_name);
            $form->text('key_word', '网站关键词')->value($data->key_word);
            $form->textarea('web_description', '网站描述')->value($data->web_description);
            $form->textarea('web_right', '版权信息')->value($data->web_right);
            $form->image('web_logo', '网站LOGO')->value($data->web_logo);
            $form->textarea('hot_search', '热门搜索词')->value($data->hot_search);
            $form->footer(function ($footer) {
                $footer->disableReset();
                $footer->disableViewCheck();
                $footer->disableEditingCheck();
                $footer->disableCreatingCheck();
            });
            $form->setAction('/admin/baseset/update/'.$data->id);
       });
    }

    public function update($id, Request $request)
    {
        // dd(env('APP_URL'));
        $data = [];
        if($request->hasFile('web_logo') && $request->file('web_logo')->isValid())
        {
            $webLogo = $request->file('web_logo');
            $data['web_logo'] = $webLogo->storeAs('baseset', 'logo.'.$webLogo->extension(), 'admin');
        }
        if($request->input('web_name')) $data['web_name'] = $request->input('web_name');
        if($request->input('key_word')) $data['key_word'] = $request->input('key_word');
        if($request->input('web_description')) $data['web_description'] = $request->input('web_description');
        if($request->input('web_right')) $data['web_right'] = $request->input('web_right');
        if($request->input('hot_search')) $data['hot_search'] = $request->input('hot_search');
        DB::table('basesets')->where('id', $id)->update($data);
        return redirect('/admin/baseset');
    }
}
