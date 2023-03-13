<?php

namespace plugin\admin\app\controller;

use plugin\admin\app\common\Util;
use plugin\admin\app\model\User;
use support\Db;
use support\exception\BusinessException;
use support\Request;
use support\Response;
use think\db\Where;
use Workerman\Worker;

class IndexController
{

    /**
     * 无需登录的方法
     * @var string[]
     */
    protected $noNeedLogin = ['index'];

    /**
     * 不需要鉴权的方法
     * @var string[]
     */
    protected $noNeedAuth = ['dashboard'];

    /**
     * 后台主页
     * @param Request $request
     * @return Response
     * @throws BusinessException
     */
    public function index(Request $request): Response
    {
        clearstatcache();
        if (!is_file(base_path('plugin/admin/config/database.php'))) {
            return view('index/install');
        }
        $admin = admin();
        if (!$admin) {
            return view('account/login');
        }
        return view('index/index');
    }

    /**
     * 仪表板
     * @param Request $request
     * @return Response
     */
    public function dashboard(Request $request): Response
    {
        // 今日新增用户数
        $today_user_count = User::where('created_at', '>', date('Y-m-d') . ' 00:00:00')->count();
        // 7天内新增用户数
        $day7_user_count = User::where('created_at', '>', date('Y-m-d H:i:s', time() - 7 * 24 * 60 * 60))->count();
        // 30天内新增用户数
        $day30_user_count = User::where('created_at', '>', date('Y-m-d H:i:s', time() - 30 * 24 * 60 * 60))->count();
        // 总用户数
        $user_count = User::count();
        // mysql版本
        $version = Db::select('select VERSION() as version');
        $mysql_version = $version[0]->version ?? 'unknown';

        $day7_detail = [];
        $now = time();
        for ($i = 0; $i < 7; $i++) {
            $date = date('Y-m-d', $now - 24 * 60 * 60 * $i);
            $day7_detail[substr($date, 5)] = User::where('created_at', '>', "$date 00:00:00")
                ->where('created_at', '<', "$date 23:59:59")->count();
        }

        return view('index/dashboard', [
            'today_user_count' => $today_user_count,
            'day7_user_count' => $day7_user_count,
            'day30_user_count' => $day30_user_count,
            'user_count' => $user_count,
            'php_version' => PHP_VERSION,
            'workerman_version' =>  Worker::VERSION,
            'webman_version' => Util::getPackageVersion('workerman/webman-framework'),
            'admin_version' => config('plugin.admin.app.version'),
            'mysql_version' => $mysql_version,
            'os' => PHP_OS,
            'day7_detail' => array_reverse($day7_detail),
        ]);
    }

}
