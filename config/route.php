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


//无须auth
Route::group("/api/system",function (){
    Route::any('/login', [app\admin\controller\api\system\LoginController::class, 'login']);//验证码
    Route::get('/captcha', [app\admin\controller\api\system\LoginController::class, 'getCaptcha']);
});

//需要auth
Route::group("/api",function (){

        Route::group("/setting",function () {
            Route::group("/config",function (){

                Route::any('/getSysConfig', [app\admin\controller\api\system\setting\SystemConfigController::class, 'getSysConfig']);
                Route::any('/getSystemConfig', [app\admin\controller\api\system\setting\SystemConfigController::class, 'getSystemGroupConfig']);
                Route::any('/getExtendConfig', [app\admin\controller\api\system\setting\SystemConfigController::class, 'getExtendGroupConfig']);
                Route::any('/getConfigByGroup', [app\admin\controller\api\system\setting\SystemConfigController::class, 'getConfigByGroup']);
                Route::any('/getConfigByKey', [app\admin\controller\api\system\setting\SystemConfigController::class, 'getConfigByKey']);
                Route::any('/saveSystemConfig', [app\admin\controller\api\system\setting\SystemConfigController::class, 'saveSystemConfig']);
                Route::any('/save', [app\admin\controller\api\system\setting\SystemConfigController::class, 'save']);
                Route::any('/update', [app\admin\controller\api\system\setting\SystemConfigController::class, 'update']);
                Route::any('/delete', [app\admin\controller\api\system\setting\SystemConfigController::class, 'delete']);
                Route::any('/clearCache', [app\admin\controller\api\system\setting\SystemConfigController::class, 'clearCache']);
            });
        });

        Route::group("/system",function (){

            Route::any('/logout', [app\admin\controller\api\system\LoginController::class, 'logout']);
            Route::any('/getInfo', [app\admin\controller\api\system\LoginController::class, 'getInfo']);
            Route::any('/refresh', [app\admin\controller\api\system\LoginController::class, 'refresh']);

            Route::group("/user",function (){
                Route::any('/index', [app\admin\controller\api\system\userCenter\UserController::class, 'index'])->name("api:system:userCenter:user:index");
                Route::any('/recycle', [app\admin\controller\api\system\userCenter\UserController::class, 'recycle'])->name("api:system:userCenter:user:recycle");
                Route::any('/save', [app\admin\controller\api\system\userCenter\UserController::class, 'save'])->name("api:system:userCenter:user:save");
                Route::any('/read', [app\admin\controller\api\system\userCenter\UserController::class, 'read'])->name("api:system:userCenter:user:read");
                Route::any('/update', [app\admin\controller\api\system\userCenter\UserController::class, 'update'])->name("api:system:userCenter:user:update");
                Route::any('/delete', [app\admin\controller\api\system\userCenter\UserController::class, 'delete'])->name("api:system:userCenter:user:delete");
                Route::any('/realDelete', [app\admin\controller\api\system\userCenter\UserController::class, 'realDelete'])->name("api:system:userCenter:user:realDelete");
                Route::any('/recovery', [app\admin\controller\api\system\userCenter\UserController::class, 'recovery'])->name("api:system:userCenter:user:recovery");
                Route::any('/changeStatus', [app\admin\controller\api\system\userCenter\UserController::class, 'changeStatus'])->name("api:system:userCenter:user:changeStatus");
                Route::any('/clearCache', [app\admin\controller\api\system\userCenter\UserController::class, 'clearCache'])->name("api:system:userCenter:user:clearCache");
                Route::any('/setHomePage', [app\admin\controller\api\system\userCenter\UserController::class, 'setHomePage'])->name("api:system:userCenter:user:setHomePage");
                Route::any('/initUserPassword', [app\admin\controller\api\system\userCenter\UserController::class, 'initUserPassword'])->name("api:system:userCenter:user:initUserPassword");
                Route::any('/updateInfo', [app\admin\controller\api\system\userCenter\UserController::class, 'updateInfo'])->name("api:system:userCenter:user:updateInfo");
                Route::any('/modifyPassword', [app\admin\controller\api\system\userCenter\UserController::class, 'modifyPassword'])->name("api:system:userCenter:user:modifyPassword");
                Route::any('/clearSelfCache', [app\admin\controller\api\system\userCenter\UserController::class, 'clearSelfCache'])->name("api:system:userCenter:user:clearSelfCache");
                Route::any('/export', [app\admin\controller\api\system\userCenter\UserController::class, 'export'])->name("api:system:userCenter:user:export");
                Route::any('/import', [app\admin\controller\api\system\userCenter\UserController::class, 'import'])->name("api:system:userCenter:user:import");
                Route::any('/downloadTemplate', [app\admin\controller\api\system\userCenter\UserController::class, 'downloadTemplate'])->name("api:system:userCenter:user:downloadTemplate");
            });

            Route::group("/role",function (){
                Route::any('/index', [app\admin\controller\api\system\userCenter\RoleController::class, 'index'])->name("api:system:userCenter:role:index");
                Route::any('/recycle', [app\admin\controller\api\system\userCenter\RoleController::class, 'recycle'])->name("api:system:userCenter:role:recycle");
                Route::any('/save', [app\admin\controller\api\system\userCenter\RoleController::class, 'save'])->name("api:system:userCenter:role:save");
                Route::any('/update', [app\admin\controller\api\system\userCenter\RoleController::class, 'update'])->name("api:system:userCenter:role:update");
                Route::any('/delete', [app\admin\controller\api\system\userCenter\RoleController::class, 'delete'])->name("api:system:userCenter:role:delete");
                Route::any('/realDelete', [app\admin\controller\api\system\userCenter\RoleController::class, 'realDelete'])->name("api:system:userCenter:role:realDelete");
                Route::any('/recovery', [app\admin\controller\api\system\userCenter\RoleController::class, 'recovery'])->name("api:system:userCenter:role:recovery");
                Route::any('/changeStatus', [app\admin\controller\api\system\userCenter\RoleController::class, 'changeStatus'])->name("api:system:userCenter:role:changeStatus");

                Route::any('/list', [app\admin\controller\api\system\userCenter\RoleController::class, 'list'])->name("api:system:userCenter:role:list");
                Route::any('/menuPermission', [app\admin\controller\api\system\userCenter\RoleController::class, 'menuPermission'])->name("api:system:userCenter:role:menuPermission");
                Route::any('/dataPermission', [app\admin\controller\api\system\userCenter\RoleController::class, 'dataPermission'])->name("api:system:userCenter:role:dataPermission");


                Route::any('/getMenuByRole', [app\admin\controller\api\system\userCenter\RoleController::class, 'getMenuByRole'])->name("api:system:userCenter:role:getMenuByRole");
                Route::any('/getDeptByRole', [app\admin\controller\api\system\userCenter\RoleController::class, 'getDeptByRole'])->name("api:system:userCenter:role:getDeptByRole");
            });

            Route::group("/menu",function (){
                Route::any('/index', [app\admin\controller\api\system\userCenter\MenuController::class, 'index'])->name("api:system:userCenter:menu:index");
                Route::any('/recycle', [app\admin\controller\api\system\userCenter\MenuController::class, 'recycle'])->name("api:system:userCenter:menu:recycle");
                Route::any('/save', [app\admin\controller\api\system\userCenter\MenuController::class, 'save'])->name("api:system:userCenter:menu:save");
                Route::any('/update', [app\admin\controller\api\system\userCenter\MenuController::class, 'update'])->name("api:system:userCenter:menu:update");
                Route::any('/delete', [app\admin\controller\api\system\userCenter\MenuController::class, 'delete'])->name("api:system:userCenter:menu:delete");
                Route::any('/realDelete', [app\admin\controller\api\system\userCenter\MenuController::class, 'realDelete'])->name("api:system:userCenter:menu:realDelete");
                Route::any('/recovery', [app\admin\controller\api\system\userCenter\MenuController::class, 'recovery'])->name("api:system:userCenter:menu:recovery");
                Route::any('/tree', [app\admin\controller\api\system\userCenter\MenuController::class, 'tree'])->name("api:system:userCenter:menu:tree");

            });

            //标准一组操作api，单表模式，gen code单表使用这个模式。
            Route::group("/post",function (){
                Route::any('/index', [app\admin\controller\api\system\userCenter\PostController::class, 'index'])->name("api:system:userCenter:post:index");
                Route::any('/recycle', [app\admin\controller\api\system\userCenter\PostController::class, 'recycle'])->name("api:system:userCenter:post:recycle");
                Route::any('/list', [app\admin\controller\api\system\userCenter\PostController::class, 'list'])->name("api:system:userCenter:post:list");
                Route::any('/save', [app\admin\controller\api\system\userCenter\PostController::class, 'save'])->name("api:system:userCenter:post:save");
                Route::any('/read', [app\admin\controller\api\system\userCenter\PostController::class, 'read'])->name("api:system:userCenter:post:read");
                Route::any('/update', [app\admin\controller\api\system\userCenter\PostController::class, 'update'])->name("api:system:userCenter:post:update");
                Route::any('/delete', [app\admin\controller\api\system\userCenter\PostController::class, 'delete'])->name("api:system:userCenter:post:delete");
                Route::any('/realDelete', [app\admin\controller\api\system\userCenter\PostController::class, 'realDelete'])->name("api:system:userCenter:post:realDelete");
                Route::any('/recovery', [app\admin\controller\api\system\userCenter\PostController::class, 'recovery'])->name("api:system:userCenter:post:recovery");
                Route::any('/changeStatus', [app\admin\controller\api\system\userCenter\PostController::class, 'changeStatus'])->name("api:system:userCenter:post:changeStatus");



            });
            Route::group("/dept",function (){
                Route::any('/index', [app\admin\controller\api\system\userCenter\DeptController::class, 'index'])->name("api:system:userCenter:dept:index");
                Route::any('/recycle', [app\admin\controller\api\system\userCenter\DeptController::class, 'recycleTree'])->name("api:system:userCenter:dept:recycleTree");
                Route::any('/save', [app\admin\controller\api\system\userCenter\DeptController::class, 'save'])->name("api:system:userCenter:dept:save");
//                Route::any('/read', [app\admin\controller\api\system\userCenter\DeptController::class, 'read']);
                Route::any('/update', [app\admin\controller\api\system\userCenter\DeptController::class, 'update'])->name("api:system:userCenter:dept:update");
                Route::any('/delete', [app\admin\controller\api\system\userCenter\DeptController::class, 'delete'])->name("api:system:userCenter:dept:delete");
                Route::any('/realDelete', [app\admin\controller\api\system\userCenter\DeptController::class, 'realDelete'])->name("api:system:userCenter:dept:realDelete");
                Route::any('/recovery', [app\admin\controller\api\system\userCenter\DeptController::class, 'recovery'])->name("api:system:userCenter:dept:recovery");
                Route::any('/changeStatus', [app\admin\controller\api\system\userCenter\DeptController::class, 'changeStatus'])->name("api:system:userCenter:dept:changeStatus");


                Route::any('/recycleTree', [app\admin\controller\api\system\userCenter\DeptController::class, 'recycleTree'])->name("api:system:userCenter:dept:recycleTree");
                Route::any('/tree', [app\admin\controller\api\system\userCenter\DeptController::class, 'tree'])->name("api:system:userCenter:dept:tree");


            });

            Route::group("/dataDict",function (){
                Route::any('/index', [app\admin\controller\api\system\dataCenter\DictDataController::class, 'index'])->name("api:system:userCenter:dataDict:index");
                Route::any('/list', [app\admin\controller\api\system\dataCenter\DictDataController::class, 'list'])->name("api:system:userCenter:dataDict:list");
                Route::any('/lists', [app\admin\controller\api\system\dataCenter\DictDataController::class, 'lists'])->name("api:system:userCenter:dataDict:lists");
                Route::any('/clearCache', [app\admin\controller\api\system\dataCenter\DictDataController::class, 'clearCache'])->name("api:system:userCenter:dataDict:clearCache");
                Route::any('/recycle', [app\admin\controller\api\system\dataCenter\DictDataController::class, 'recycle'])->name("api:system:userCenter:dataDict:recycle");
                Route::any('/save', [app\admin\controller\api\system\dataCenter\DictDataController::class, 'save'])->name("api:system:userCenter:dataDict:save");
                Route::any('/read', [app\admin\controller\api\system\dataCenter\DictDataController::class, 'read'])->name("api:system:userCenter:dataDict:read");
                Route::any('update', [app\admin\controller\api\system\dataCenter\DictDataController::class, 'update'])->name("api:system:userCenter:dataDict:update");
                Route::any('delete', [app\admin\controller\api\system\dataCenter\DictDataController::class, 'delete'])->name("api:system:userCenter:dataDict:delete");
                Route::any('realDelete', [app\admin\controller\api\system\dataCenter\DictDataController::class, 'realDelete'])->name("api:system:userCenter:dataDict:realDelete");
                Route::any('recovery', [app\admin\controller\api\system\dataCenter\DictDataController::class, 'recovery'])->name("api:system:userCenter:dataDict:recovery");
                Route::any('changeStatus', [app\admin\controller\api\system\dataCenter\DictDataController::class, 'changeStatus'])->name("api:system:userCenter:dataDict:changeStatus");
            });


            /**
             * 字典类型
             * 释义：根据这里可以把对应上字段典，并找到对应的字典数据，开启表字段到字典数据的自动转换。
             */
            Route::group("/dictType",function (){
                Route::any('/index', [app\admin\controller\api\system\dataCenter\DictTypeController::class, 'index'])->name("api:system:userCenter:dictType:index");
                Route::any('/recycle', [app\admin\controller\api\system\dataCenter\DictTypeController::class, 'recycle'])->name("api:system:userCenter:dictType:recycle");
                Route::any('/save', [app\admin\controller\api\system\dataCenter\DictTypeController::class, 'save'])->name("api:system:userCenter:dictType:save");
                Route::any('/read', [app\admin\controller\api\system\dataCenter\DictTypeController::class, 'read'])->name("api:system:userCenter:dictType:read");
                Route::any('/update', [app\admin\controller\api\system\dataCenter\DictTypeController::class, 'update'])->name("api:system:userCenter:dictType:update");
                Route::any('/delete', [app\admin\controller\api\system\dataCenter\DictTypeController::class, 'delete'])->name("api:system:userCenter:dictType:delete");
                Route::any('/realDelete', [app\admin\controller\api\system\dataCenter\DictTypeController::class, 'realDelete'])->name("api:system:userCenter:dictType:realDelete");
                Route::any('/recovery', [app\admin\controller\api\system\dataCenter\DictTypeController::class, 'recovery'])->name("api:system:userCenter:dictType:recovery");
            });


            /**
             * 字段典
             * 释义：根据这里可以把业务表状态字段映射到对应的字典类型
             */
            Route::group("/dictField",function (){
                Route::any('/index', [app\admin\controller\api\system\dataCenter\DictFieldController::class, 'index'])->name("api:system:userCenter:dictField:index");
                Route::any('/recycle', [app\admin\controller\api\system\dataCenter\DictFieldController::class, 'recycle'])->name("api:system:userCenter:dictField:recycle");
                Route::any('/save', [app\admin\controller\api\system\dataCenter\DictFieldController::class, 'save'])->name("api:system:userCenter:dictField:save");
                Route::any('/read', [app\admin\controller\api\system\dataCenter\DictFieldController::class, 'read'])->name("api:system:userCenter:dictField:read");
                Route::any('/update', [app\admin\controller\api\system\dataCenter\DictFieldController::class, 'update'])->name("api:system:userCenter:dictField:update");
                Route::any('/delete', [app\admin\controller\api\system\dataCenter\DictFieldController::class, 'delete'])->name("api:system:userCenter:dictField:delete");
                Route::any('/realDelete', [app\admin\controller\api\system\dataCenter\DictFieldController::class, 'realDelete'])->name("api:system:userCenter:dictField:realDelete");
                Route::any('/recovery', [app\admin\controller\api\system\dataCenter\DictFieldController::class, 'recovery'])->name("api:system:userCenter:dictField:recovery");
            });

            /**
             * 数据源
             * 释义：根据这里可以获取所有的业务表
             * dataMaintain
             *
             */
            Route::group("/dataSource",function (){
                Route::any('/index', [app\admin\controller\api\system\dataCenter\DataSourceController::class, 'index'])->name("api:system:userCenter:dataSource:index");//数据库表列表
                Route::any('/detailed', [app\admin\controller\api\system\dataCenter\DataSourceController::class, 'detailed'])->name("api:system:userCenter:dataSource:detailed");//数据表字段列表
                Route::any('/optimize', [app\admin\controller\api\system\dataCenter\DataSourceController::class, 'optimize'])->name("api:system:userCenter:dataSource:optimize");//优化表
                Route::any('/fragment', [app\admin\controller\api\system\dataCenter\DataSourceController::class, 'fragment'])->name("api:system:userCenter:dataSource:fragment");//清理表碎片
            });





            Route::group("/notice",function (){
                Route::any('/index', [app\admin\controller\api\system\dataCenter\NoticeController::class, 'index'])->name("api:system:userCenter:notice:index");;
                Route::any('/recycle', [app\admin\controller\api\system\dataCenter\NoticeController::class, 'recycle']);
                Route::any('/save', [app\admin\controller\api\system\dataCenter\NoticeController::class, 'save']);
                Route::any('/read', [app\admin\controller\api\system\dataCenter\NoticeController::class, 'read']);
                Route::any('/update', [app\admin\controller\api\system\dataCenter\NoticeController::class, 'update']);
                Route::any('/delete', [app\admin\controller\api\system\dataCenter\NoticeController::class, 'delete']);
                Route::any('/realDelete', [app\admin\controller\api\system\dataCenter\NoticeController::class, 'realDelete']);
                Route::any('/recovery', [app\admin\controller\api\system\dataCenter\NoticeController::class, 'recovery']);
            });


            Route::group("/onlineUser",function (){
                Route::any('/index', [app\admin\controller\api\system\monitorCenter\OnlineUserMonitorController::class, 'getPageList']);
                Route::any('/kick', [app\admin\controller\api\system\monitorCenter\OnlineUserMonitorController::class, 'kickUser']);
            });

        });

});


//基础表格模板






//自动路由解析

$dir_iterator = new \RecursiveDirectoryIterator(app_path());//.DS."adminUi"
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
            \support\Log::info("$routeName Route $uri [{$cb[0]}, {$cb[1]}]");
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
//        if ($action === 'index') {
//            $route($uri_path, [$class_name, $action]);
//        }
        $route($uri_path.'/'.$action, [$class_name, $action]);

    }

    // 回退路由，设置默认的路由兜底
//    Route::fallback();


}
