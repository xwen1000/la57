<?php

use Illuminate\Routing\Router;

Admin::registerAuthRoutes();

Route::group([
    'prefix'        => config('admin.route.prefix'),
    'namespace'     => config('admin.route.namespace'),
    'middleware'    => config('admin.route.middleware'),
], function (Router $router) {

    // $router->get('/', 'HomeController@index');
     $router->get('/', 'IndexController@index');
     $router->get('baseset', 'BasesetController@index');
     $router->post('baseset/update/{id}', 'BasesetController@update');
     $router->resource('goods', GoodController::class);
     $router->get('goods/delete/{id}', 'GoodController@delete');
     $router->get('goods/{id}/isselling/{isSelling}', 'GoodController@updateS');
     $router->post('goods/bselling', 'GoodController@batchSelling');
     $router->resource('room', RoomController::class);
     $router->get('room/delete/{id}', 'RoomController@delete');
     $router->get('room/{id}/isselling/{isSelling}', 'RoomController@updateS');
     $router->resource('news', NewsController::class);
     $router->get('news/{id}/status/{status}', 'NewsController@updateS');
});
