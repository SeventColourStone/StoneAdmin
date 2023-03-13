<?php /** @noinspection PhpUnused */
/**

 */

namespace plugin\stone\nyuwa\helper;


class NyuwaCode
{
    public const TOKEN_EXPIRED = 1001;      // TOKEN过期、不存在
    public const VALIDATE_FAILED = 1002;    // 数据验证失败
    public const NO_PERMISSION = 1003;      // 没有权限
    public const NO_DATA = 1004;            // 没有数据
    public const NORMAL_STATUS = 1005;      // 正常状态异常代码

    public const NO_USER = 1010;            // 用户不存在
    public const PASSWORD_ERROR = 1011;     // 密码错误
    public const USER_BAN = 1012;           // 用户被禁

    public const METHOD_NOT_ALLOW = 2000;   // 地址使用了不允许的访问方法
    public const NOT_FOUND = 2100;          // 资源不存在
    public const INTERFACE_EXCEPTION = 2150;// 接口异常
    public const RESOURCE_STOP = 2200;      // 资源被停用

    public const APP_BAN = 2300;            // APP被停用

    public const API_AUTH_EXCEPTION = 10000;        // 接口鉴权异常
    public const API_AUTH_FAIL = 10010;             // 接口鉴权失败
    public const API_APP_ID_MISSING = 10101;        // 缺少APP ID
    public const API_APP_SECRET_MISSING = 10102;    // 缺少APP SECRET
    public const API_ACCESS_TOKEN_MISSING = 10103;  // 缺少 ACCESS TOKEN
    public const API_PARAMS_ERROR = 10104;          // API参数错误
    public const API_SIGN_MISSING = 10105;          // 缺少签名
    public const API_SIGN_ERROR   = 10106;          // 签名错误
    public const API_VERIFY_PASS = 10160;           // API验证通过
}