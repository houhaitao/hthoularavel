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
        Route::get('ajaxMenuPath/{id}','admin\menu@ajaxMenuPath')->where('id','[0-9]+');
        Route::get('{id}','admin\menu@show')->where('id','[0-9]+');
        Route::post('search','admin\menu@search');
        Route::post('delete','admin\menu@delete');
        Route::post('listorder','admin\menu@listorder');
    });

    /**
     * 管理员
     */
    Route::group(['prefix'=>'manager'],function(){
        Route::resource('/','admin\manager');
        Route::get('{id}','admin\manager@show')->where('id','[0-9]+');

        /**
         * 管理员搜索列表
         */
        Route::get('name/{name?}','admin\manager@index');
        Route::post('search','admin\manager@search');
        Route::post('delete','admin\manager@delete');
    });

    /**
     * 数据资源
     */
    Route::group(['prefix'=>'resource'],function(){
        Route::resource('/','admin\resource');
        Route::get('{id}','admin\resource@show')->where('id','[0-9]+');
        Route::get('restree','admin\resource@ajaxGetResTree');
        Route::post('listorder','admin\resource@listorder');
        Route::post('delete','admin\resource@delete');
    });

    /**
     * 分类管理
     */

    Route::group(['prefix'=>'type'],function(){
        Route::resource('/','admin\type');
        Route::get('{id}','admin\type@show')->where('id','[0-9]+');
        Route::post('search','admin\type@search');
        Route::post('delete','admin\type@delete');
        Route::post('listorder','admin\type@listorder');
    });
    /**
     * 权限元管理
     */

    Route::group(['prefix'=>'privilege'],function(){
        Route::resource('/','admin\privilege');
        Route::get('{id}','admin\privilege@show')->where('id','[0-9]+');
        Route::get('typedata','admin\privilege@getTypeData');
        Route::post('search','admin\privilege@search');
        Route::post('delete','admin\privilege@delete');
        Route::post('listorder','admin\privilege@listorder');
    });

    /**
     * 组管理
     */

    Route::group(['prefix'=>'group'],function(){
        Route::resource('/','admin\group');
        Route::get('{id}','admin\group@show')->where('id','[0-9]+');
        Route::get('groupres/{id}','admin\group@ajaxGetGroupResource')->where('id','[0-9]+');
        Route::get('allgroup','admin\group@getAllGroups');
        Route::post('search','admin\group@search');
        Route::post('delete','admin\group@delete');
        Route::post('listorder','admin\group@listorder');
        Route::post('priv','admin\group@storePriv');
    });
    /**
     * 角色管理
     */

    Route::group(['prefix'=>'role'],function(){
        Route::resource('/','admin\role');
        Route::get('{id}','admin\role@show')->where('id','[0-9]+');
        Route::get('allrole','admin\role@getAllRoles');
        Route::post('search','admin\role@search');
        Route::post('delete','admin\role@delete');
        Route::post('listorder','admin\role@listorder');
        Route::post('storepriv','admin\role@privStore');
    });

});





