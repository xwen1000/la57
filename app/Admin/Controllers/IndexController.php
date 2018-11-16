<?php

namespace App\Admin\Controllers;

use App\Http\Controllers\Controller;
use Encore\Admin\Controllers\Dashboard;
use Encore\Admin\Layout\Column;
use Encore\Admin\Layout\Content;
use Encore\Admin\Layout\Row;
use Encore\Admin\Widgets\Box;


class IndexController extends Controller
{
    public function index(Content $content)
    {
        return $content
            ->header('XWEN的后台')
            ->description('主页')
            ->row(Dashboard::index());
    }
}
