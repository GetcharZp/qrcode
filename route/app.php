<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006~2018 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: liu21st <liu21st@gmail.com>
// +----------------------------------------------------------------------
use think\facade\Route;

Route::get('/', 'Index/index');
Route::post("createQrcode", "Index/Createqrcode");

Route::group("api", function () {
    Route::get('/', 'Api/index');
    Route::post('upload', 'Api/upload');
    Route::get("qrcode", "Api/Createqrcode");
    Route::get("decode", "Api/decode");
    Route::get("batchCreateQrcode", "Api/batchCreateQrcode");
});

Route::group("decode", function () {
    Route::get('/', 'Decode/index');
});

Route::group("batchCreateQrcode", function () {
    Route::get('/', 'BatchCreateQrcode/index');
});