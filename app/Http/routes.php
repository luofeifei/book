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
    return view('login');//找视图在resources\views下
});

Route::get('/login',"View\MemberController@toLogin");//找控制器的方法/service/login
//命名空间下App\Http\Controllers\View
//控制器View下面的类MemberController的toLogin方法

Route::get('/register',"View\MemberController@toRegister");
Route::get('/category',"View\CategoryController@toCategory");
Route::get('/product/category_id/{category_id}',"View\CategoryController@toProduct");
Route::get('/product/{prodcuct_id}',"View\CategoryController@toPdtContent");

//中间键 拦截器 
Route::group(['prefix'=>'service'],function(){
	Route::post('register',"Service\MemberController@register");
	Route::post('login',"Service\MemberController@login");
	Route::get('category/parent_id/{parent_id}',"Service\CategoryController@toCategory");

	Route::any('validate_code/create/login',"Service\ValidateCodeController@create");
	Route::any('validate_code/create/send',"Service\ValidateCodeController@sendSMS");
	Route::get('validate_email',"Service\ValidateCodeController@validateEmail");

});


