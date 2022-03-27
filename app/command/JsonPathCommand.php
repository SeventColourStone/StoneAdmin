<?php

namespace app\command;

use JmesPath\Lexer;
use JmesPath\Parser;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Output\OutputInterface;


class JsonPathCommand extends Command
{
    protected static $defaultName = 'stoneAdmin:JsonPathCommand';
    protected static $defaultDescription = 'JsonPathCommand';

    /**
     * @return void
     */
    protected function configure()
    {
        $this->addArgument('name', InputArgument::OPTIONAL, 'Name description');
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return int
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $name = $input->getArgument('name');
        $output->writeln('Hello JsonPathCommand');

        $json = <<<JSON
{"a": {"b": {"c": {"d": "value"}}}}
JSON;

        $expression = 'a.b.c.d';

//        JmesPath($expression, $json);
//        $p = new Parser(new Lexer());
//        $p->parse($expression);
//        $info = \JmesPath\search($expression,json_decode($json,true));
        $info = \JmesPath\search($expression,nyuwa_is_json($json));
        var_dump($info);




        return self::SUCCESS;
    }

    private $client_id = 'client_id'; // 你的client_id
    private $client_secret = 'client_secret'; // 你的client_secret
    private function GetPDDApi($apiType, $param)
    {
        $appInfo = zfun::f_getset('pdd_client_id,pdd_client_secret');
        $param['client_id'] = $this->client_id;
        $param['type'] = $apiType;
        $param['data_type'] = 'JSON';
        $param['timestamp'] = self::getMillisecond();
        ksort($param); // 排序
        $str = ''; // 拼接的字符串
        foreach ($param as $k => $v) $str .= $k . $v;
        $sign = strtoupper(md5($this->client_secret. $str . $this->client_secret)); // 生成签名 MD5加密转大写
        $param['sign'] = $sign;
        $url = 'http://gw-api.pinduoduo.com/api/router';
// return self::curl_post($url, $param);
        $data=self::curl_post($url, $param);
    }

// post请求
    private static function curl_post($url, $curlPost)
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($ch, CURLOPT_HEADER, false);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_REFERER, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $curlPost);
        $result = curl_exec($ch);
        curl_close($ch);
        return $result;
    }

// 获取13位时间戳
    private static function getMillisecond()
    {
        list($t1, $t2) = explode(' ', microtime());
        return sprintf('%.0f', (floatval($t1) + floatval($t2)) * 1000);
    }
    public function ceshi(){
        return "1111";
    }

}
