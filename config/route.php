<?php
/**
 * This file is part of webman.
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the MIT-LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @author    walkor<walkor@workerman.net>
 * @copyright walkor<walkor@workerman.net>
 * @link      http://www.workerman.net/
 * @license   http://www.opensource.org/licenses/mit-license.php MIT License
 */

use Webman\Route;

Route::get("/",function (){
    return response("welcome to stone admin !");
});

Route::group("/system",function (){
    Route::any('/login', [app\admin\controller\LoginController::class, 'login']);//验证码
    Route::any('/captcha', [app\admin\controller\LoginController::class, 'captcha']);
    Route::any('/logout', [app\admin\controller\LoginController::class, 'logout']);
    Route::any('/refresh', [app\admin\controller\LoginController::class, 'refresh']);
    Route::any('/getInfo', [app\admin\controller\LoginController::class, 'getInfo']);//用户信息


    Route::any('/uploadFile', [app\admin\controller\UploadController::class, 'uploadFile']);
    Route::any('/uploadImage', [app\admin\controller\UploadController::class, 'uploadImage']);
    Route::any('/saveNetworkImage', [app\admin\controller\UploadController::class, 'saveNetworkImage']);
    Route::any('/getAllFiles', [app\admin\controller\UploadController::class, 'getAllFiles']);
    Route::any('/getFileInfo', [app\admin\controller\UploadController::class, 'getFileInfo']);
    Route::any('/download', [app\admin\controller\UploadController::class, 'download']);


});



//自动路由解析

$dir_iterator = new \RecursiveDirectoryIterator(app_path());
//ui自动引入路由
$iterator = new \RecursiveIteratorIterator($dir_iterator);
foreach ($iterator as $file) {
    // 忽略目录和非php文件
    if (is_dir($file) || $file->getExtension() != 'php') {
        continue;
    }

    $file_path = str_replace('\\', '/',$file->getPathname());
    // 文件路径里不带controller的文件忽略
    if (strpos($file_path, 'controller') === false) {
        continue;
    }

    // 根据文件路径是被类名
    $class_name = str_replace('/', '\\',substr(substr($file_path, strlen(base_path())), 0, -4));

    if (!class_exists($class_name)) {
        echo "Class $class_name not found, skip route for it\n";
        continue;
    }

    // 通过反射找到这个类的所有共有方法作为action
    $class = new ReflectionClass($class_name);

    $methods = [];
    foreach ($class->getmethods(ReflectionMethod::IS_PUBLIC) as $method){
        //获取当前目录下类下的路由
        if ($method->class == $class->getName())
            $methods[] = $method;
    }


    $route = function ($uri, $cb) {
        $uri = str_replace("/adminUi/","/ui/",$uri);
        $uri = str_replace(["/admin/",'/Admin/'],"/",$uri);
        $underLineUriPath = nyuwa_uncamelize($uri);
        $camelCaseUriPath = nyuwa_camelize($underLineUriPath,'_',0);
//        var_dump("小驼峰：".$camelCaseUriPath);
        $routeName = str_replace("/",":",trim($camelCaseUriPath,"/"));
        //系统路由过滤
        str_replace([':userCenter:',':dataCenter:',':setting:',':devCenter:',':monitorCenter:'],":",$routeName);
//        var_dump("路由名：".$routeName);
        if ($underLineUriPath == $camelCaseUriPath){
            $uriPathArr = [$underLineUriPath];
        }else{
            $centerScoreCaseUriPath = nyuwa_uncamelize($camelCaseUriPath,"-");
            if ($camelCaseUriPath == $centerScoreCaseUriPath){
                $uriPathArr = [$underLineUriPath,$camelCaseUriPath];
            }else{
                $uriPathArr = [$underLineUriPath,$camelCaseUriPath,$centerScoreCaseUriPath];
            }
        }
        foreach ($uriPathArr as $uri){
//            echo "Route $uri [{$cb[0]}, {$cb[1]}]\n";
//            \support\Log::info("$routeName Route $uri [{$cb[0]}, {$cb[1]}]");
            Route::any($uri, $cb)->name($routeName);
            Route::any($uri.'/', $cb)->name($routeName);
        }
    };

    // 根据文件路径计算uri
    //兼容容错uri
    $uri_path = str_replace(['controller/','Controller','app/'], '',substr(substr($file_path, strlen(base_path())), 0, -4));


    // 设置路由
    foreach ($methods as $item) {
        $action = $item->name;
        if (in_array($action, ['__construct', '__destruct'])) {
            continue;
        }
        // action为index时uri里末尾/index可以省略
        if ($action === 'index') {
            $route($uri_path, [$class_name, $action]);
        }
        $route($uri_path.'/'.$action, [$class_name, $action]);

    }

    // 回退路由，设置默认的路由兜底
//    Route::fallback();


}
