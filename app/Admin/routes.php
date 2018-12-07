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
     $router->get('room/sale/{id}', 'RoomController@sale');
     $router->resource('news', NewsController::class);
     $router->get('news/{id}/status/{status}', 'NewsController@updateS');
     $router->resource('members', MemberController::class);
     $router->get('members/addMemberCard/{id}', 'MemberController@addMemberCard');
     $router->post('members/saveMemberCard', 'MemberController@saveMemberCard');
     $router->resource('tables', TablesController::class);
     $router->get('tables/down/{id}', 'TablesController@down');
     $router->resource('dian', DianController::class);
     $router->get('dian/delete/{id}', 'DianController@delete');
     $router->get('dian/{id}/isselling/{isSelling}', 'DianController@updateS');
     $router->resource('npccates', NpcCateController::class);
     $router->get('npccates/delete/{id}', 'NpcCateController@delete');
     $router->resource('newscates', NewsCateController::class);
     $router->get('newscates/delete/{id}', 'NewsCateController@delete');
     $router->resource('orders', OrderController::class);
     $router->get('orders/printInfo/{id}', 'OrderController@printInfo');
     $router->get('orders/member/{id}', 'OrderController@member');
     $router->get('orders/stock/real', 'OrderController@realStock');
     $router->get('orders/{id}/isselling/{isSelling}', 'OrderController@updateS');
     $router->resource('cards', CardsController::class);
     $router->get('cards/delete/{id}', 'CardsController@delete');
     $router->get('cards/{id}/isselling/{isSelling}', 'CardsController@updateS');
     $router->resource('banners', BannerController::class);
     $router->resource('activities', ActivityController::class);
     $router->get('activities/delete/{id}', 'ActivityController@delete');
     $router->get('activities/{id}/isselling/{isSelling}', 'ActivityController@updateS');
     $router->get('finances/index', 'FinanceController@index');
     $router->get('finances/getSearch', 'FinanceController@getSearch');
     $router->get('finances/charges', 'FinanceController@charges');
     $router->get('finances/getCharges', 'FinanceController@getCharges');
     $router->get('finances/buys', 'FinanceController@buys');
     $router->get('finances/getBuys', 'FinanceController@getBuys');
     $router->get('finances/sales', 'FinanceController@sales');
     $router->get('finances/getSales', 'FinanceController@getSales');
     $router->get('finances/express', 'FinanceController@express');
     $router->post('finances/express/store', 'FinanceController@store');
});
