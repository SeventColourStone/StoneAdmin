<?php

use Webman\Route;


Route::group("/bbs",function (){

    Route::get("/",function (){
        return response("welcome to stone bbs !");
    });

});

\support\Log::info("测试");
//自动路由解析
$controllerPath = base_path().DS."vendor".DS."stone".DS.config("plugin.stone.bbs.app.app_name");
\support\Log::info("bbs".$controllerPath);
$dir_iterator = new \RecursiveDirectoryIterator($controllerPath);
//ui自动引入路由
$iterator = new \RecursiveIteratorIterator($dir_iterator);
foreach ($iterator as $file) {
    // 忽略目录和非php文件
    if (is_dir($file) || $file->getExtension() != 'php') {
        continue;
    }

    $file_path = str_replace('\\', '/',$file->getPathname());
    // 文件路径里不带controller的文件忽略
    if (strpos($file_path, 'Controller') === false) {
        continue;
    }

    // /vendor/stone/bbs/src/
    // 根据文件路径是被类名
    $file_path = str_replace("/vendor/stone/bbs/src/","Stone/Bbs/",$file_path);

    var_dump(substr(substr($file_path, strlen(base_path())), 0, -4));
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
//        $uri = str_replace(["/admin/",'/Admin/'],"/",$uri);
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
            \support\Log::info("$routeName Route $uri [{$cb[0]}, {$cb[1]}]");
            Route::any("/".$uri, $cb)->name($routeName);
            Route::any("/".$uri.'/', $cb)->name($routeName);
        }
    };

    // 根据文件路径计算uri
    //兼容容错uri
    $uri_path = str_replace(['controller/','Controller/','app/'], '',substr(substr($file_path, strlen(base_path())), 0, -4));


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
}