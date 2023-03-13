<?php

use plugin\stone\app\controller\LoginController;
use plugin\stone\app\controller\UploadController;
use Webman\Route;


//请求路径
//http://127.0.0.1:8787/app/stone/system/user/index
//http://127.0.0.1:8787/app/stone/system/user/read?id=12



Route::group("/app/stone/system",function (){
    Route::any('/login', [LoginController::class, 'login']);//验证码
    Route::any('/captcha', [LoginController::class, 'captcha']);
    Route::any('/logout', [LoginController::class, 'logout']);
    Route::any('/refresh', [LoginController::class, 'refresh']);
    Route::any('/getInfo', [LoginController::class, 'getInfo']);//用户信息


    Route::any('/uploadFile', [UploadController::class, 'uploadFile']);
    Route::any('/uploadImage', [UploadController::class, 'uploadImage']);
    Route::any('/saveNetworkImage', [UploadController::class, 'saveNetworkImage']);
    Route::any('/getAllFiles', [UploadController::class, 'getAllFiles']);
    Route::any('/getFileInfo', [UploadController::class, 'getFileInfo']);
    Route::any('/download', [UploadController::class, 'download']);


});
