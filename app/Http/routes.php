<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/

Route::get('/', function () {
    return view('welcome');
});
/**
 * 后台管理
 */
Route::group(['prefix'=>'admin'],function(){
    /**
     * 菜单管理
     */

    Route::group(['prefix'=>'menu'],function(){
        Route::resource('/','admin\menu');
        Route::get('ajaxMenuTree','admin\menu@ajaxMenuTree');
        Route::get('ajaxMenuPath/{id}','admin\menu@ajaxMenuPath');
        Route::get('{id}','admin\menu@show');

        /**
         * 菜单列表
         */
        Route::group(['prefix'=>'p/{id}'],function(){
            Route::get('/','admin\menu@index');
            Route::get('name/{name?}','admin\menu@index');
        });

        Route::post('search','admin\menu@search');
        Route::post('delete','admin\menu@delete');
        Route::post('listorder','admin\menu@listorder');
    });

    /**
     * 管理员
     */
    Route::group(['prefix'=>'manager'],function(){
        Route::resource('/','admin\manager');
        Route::get('{id}','admin\manager@show');

        /**
         * 管理员搜索列表
         */
        Route::get('name/{name?}','admin\manager@index');

        Route::post('search','admin\manager@search');
        Route::post('delete','admin\manager@delete');
    });

});





