<?php


namespace nyuwa\auth\util;


use Lcobucci\JWT\ClaimsFormatter;
use Lcobucci\JWT\Encoding\JoseEncoder;
use Lcobucci\JWT\Token\Builder;
use Lcobucci\JWT\Token\DataSet;
use Lcobucci\JWT\Token\Parser;
use Lcobucci\JWT\Decoder;
use Lcobucci\JWT\Encoder;
use Lcobucci\JWT\Validation\Validator;
use Webman\App;

class JWTUtil
{
    /**
     * claims对象转换成数组
     *
     * @param $claims
     * @return mixed
     */
    public static function claimsToArray(DataSet $claims)
    {
        return $claims->all();
    }

    /**
     * 获取jwt token
     * @return array
     */
    public static function getToken()
    {
        $request = App::request();
        $token = $request->header('Authorization') ?? '';
        $token = self::handleToken($token);
        return $token;
    }

    /**
     * 解析token
     * @return array
     */
    public static function getParserData()
    {
        $request = App::request();
        $token = $request->header('Authorization') ?? '';
        $token = self::handleToken($token);
        return self::getParser()->parse($token)->claims()->all();
    }

    /**
     * 处理token
     * @param string $token
     * @param string $prefix
     * @return bool|mixed|string
     */
    public static function handleToken(string $token, string $prefix = 'Bearer')
    {
        if (strlen($token) > 0) {
            $token = ucfirst($token);
            $arr = explode("{$prefix} ", $token);
            $token = $arr[1] ?? '';
            if (strlen($token) > 0) {
                return $token;
            }
        }
        return false;
    }

    /**
     * @return Parser
     */
    public static function getParser(Decoder $decoder = null): Parser
    {
        if ($decoder == null) {
            return new Parser(new JoseEncoder());
        }
        return new Parser($decoder);
    }

    public static function getValidator(): Validator
    {
        return new Validator();
    }
}
