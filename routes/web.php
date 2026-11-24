<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\BlogController;
use App\Http\Controllers\UserController;

// 根路径直接跳到首页（原来访问 / 是 404）
Route::get('/', function () {
    return redirect('/index');
});

// 主页（列表 + 搜索）
Route::get('/index', [BlogController::class, 'search']);

// 登录页面
Route::get('/login', function () {
    return view('login');
});

// 用户登录
Route::post('/user/login', [UserController::class, 'login']);

// 用户登出
Route::get('/user/loginoff', [UserController::class, 'loginoff']);

// 博客搜索
Route::get('/blog/search', [BlogController::class, 'search']);

// 添加博客
Route::post('/blog/add', [BlogController::class, 'add']);

// 打开单篇博客的修改页
Route::get('/blog/mod/{bid}', [BlogController::class, 'get']);

// 保存修改
Route::post('/blog/mod', [BlogController::class, 'mod']);

// 删除博客（改为 POST，带 CSRF 校验，避免被链接或图片链误删）
Route::post('/blog/del/{bid}', [BlogController::class, 'del']);
