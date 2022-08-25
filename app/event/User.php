<?php


namespace App\event;


use app\admin\model\system\SystemUser;
use app\admin\service\system\SystemLoginLogService;
use DI\Annotation\Inject;
use nyuwa\event\UserLoginAfter;
use nyuwa\helper\Str;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;
use support\Redis;

class User
{

    /**
     * @var SystemLoginLogService
     * @Inject
     */
    protected $systemLoginLogService;

    public function loginBefore(){

    }

    public function loginAfter(UserLoginAfter $event){
            $request = request();
            var_dump($request->header('user-agent'));
            $agent = $request->header('user-agent');
            $ip = $request->getRealIp();

            $is = $this->systemLoginLogService->save([
                'username' => $event->userinfo['username'],
                'ip' => $ip,
                'ip_location' => Str::ipToRegion($ip),
                'os' => $this->os($agent),
                'browser' => $this->browser($agent),
                'status' => !$event->loginStatus,
                'message' => $event->message,
                'login_time' => date('Y-m-d H:i:s')
            ]);

        $key = sprintf("%sToken:%s", config('cache.default.prefix'), $event->userinfo['id']);

        Redis::exists($key) && Redis::del($key);
        ($event->loginStatus && $event->token) && Redis::set( $key, $event->token, config('jwt.ttl') );

        if ($event->loginStatus) {
            $event->userinfo['login_ip'] = $ip;
            $event->userinfo['login_time'] = date('Y-m-d H:i:s');

            SystemUser::query()->where('id', $event->userinfo['id'])->update([
                'login_ip' => $ip,
                'login_time' => date('Y-m-d H:i:s'),
            ]);
        }
    }

    public function logout(){

    }


    /**
     * @param $agent
     * @return string
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    private function os($agent): string
    {
        if (false !== stripos($agent, 'win') && preg_match('/nt 6.1/i', $agent)) {
            return 'Windows 7';
        }
        if (false !== stripos($agent, 'win') && preg_match('/nt 6.2/i', $agent)) {
            return 'Windows 8';
        }
        if(false !== stripos($agent, 'win') && preg_match('/nt 10.0/i', $agent)) {
            return 'Windows 10';
        }
        if(false !== stripos($agent, 'win') && preg_match('/nt 11.0/i', $agent)) {
            return 'Windows 11';
        }
        if (false !== stripos($agent, 'win') && preg_match('/nt 5.1/i', $agent)) {
            return 'Windows XP';
        }
        if (false !== stripos($agent, 'linux')) {
            return 'Linux';
        }
        if (false !== stripos($agent, 'mac')) {
            return 'Mac';
        }
        return nyuwa_trans('jwt.unknown');
    }

    /**
     * @param $agent
     * @return string
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    private function browser($agent): string
    {
        if (false !== stripos($agent, "MSIE")) {
            return 'MSIE';
        }
        if (false !== stripos($agent, "Edg")) {
            return 'Edge';
        }
        if (false !== stripos($agent, "Chrome")) {
            return 'Chrome';
        }
        if (false !== stripos($agent, "Firefox")) {
            return 'Firefox';
        }
        if (false !== stripos($agent, "Safari")) {
            return 'Safari';
        }
        if (false !== stripos($agent, "Opera")) {
            return 'Opera';
        }
        return nyuwa_trans('jwt.unknown');
    }
}