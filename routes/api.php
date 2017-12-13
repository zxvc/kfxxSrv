<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

//Route::middleware('auth:api')->get('/user', function (Request $request) {
//    return $request->user();
//});


//用户类路由
Route::group(['prefix' => '', 'middleware' => ['BeforeRequest']], function () {
    // 示例接口
    Route::get('test', 'API\TestController@test');

    //获取七牛token
    Route::get('user/getQiniuToken', 'API\UserController@getQiniuToken');

    //获取首页广告图信息
    Route::get('ad/getADs', 'API\ADController@getADs');
    Route::get('ad/getById', 'API\ADController@getADById');

    //根据id获取用户信息
    Route::get('user/getById', 'API\UserController@getUserById');
    //根据id获取用户信息带token
    Route::get('user/getByIdWithToken', 'API\UserController@getUserInfoByIdWithToken')->middleware('CheckToken');
    //根据code获取openid
    Route::get('user/getXCXOpenId', 'API\UserController@getXCXOpenId');
    //登录
    Route::post('user/login', 'API\UserController@login');
    //注册
    Route::post('user/register', 'API\UserController@register');
    //更新用户信息
    Route::post('user/updateById', 'API\UserController@updateUserById')->middleware('CheckToken');
    //发送验证码
    Route::post('user/sendVertifyCode', 'API\UserController@sendVertifyCode');

    //宣教相关
    Route::get('xj/getXJTypes', 'API\XJController@getXJTypes');
    Route::get('xj/getByCon', 'API\XJController@getXJList');
    Route::get('xj/getXJInfoById', 'API\XJController@getXJInfoById');

    //数据项相关
    Route::get('sjx/getList', 'API\SJXController@getList');

    //康复模板相关
    Route::get('kfmb/getKFMBById', 'API\KFMBController@getKFMBById');


});