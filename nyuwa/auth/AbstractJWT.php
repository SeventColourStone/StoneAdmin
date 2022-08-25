<?php


namespace nyuwa\auth;


use Lcobucci\JWT\Token\Plain;

abstract class AbstractJWT
{


    /**
     * 获取jwt token
     *
     * @param array $claims
     * @return array
     */
    abstract public function createToken(array $claims):string;

    /**
     * 对jwt token进行验证
     *
     * @param string $token
     * @return bool
     */
    abstract public function verifyToken(string $token): bool;

    /**
     * 对jwt token进行验证，根据应用场景
     *
     * @param string $token
     * @return bool
     */
    abstract public function verifyTokenByScene(string $token,string $scene = "default"): bool;


    /**
     * 获取jwt中的场景值
     *
     * @param string $token
     * @return string
     */
    abstract public function getSceneByToken(string $token): string;

    /**
     * 刷新jwt token
     *
     * @param string $token
     * @return array
     */
    abstract public function refreshToken(string $token): string;

    /**
     * 获取JWT token的claims部分
     *
     * @param string $token
     * @return array
     */
    abstract public function getClaimsByToken(string $token): array;


    /**
     * token 转 plain
     * @param string $token
     * @return Plain
     */
    abstract public function tokenToPlain(string $token): Plain;

    /**
     * 获取jwt的有效时间
     *
     * @param string $token
     * @return int
     */
    abstract public function getTokenTTL(string $token): int;

    /**
     * 获取jwt的剩余的有效时间
     *
     * @param string $token
     * @return int
     */
    abstract public function getTokenDynamicCacheTime(string $token): int;

    /**
     * 使当前jwt失效
     *
     * @param string $token
     * @return bool
     */
    abstract public function invalidToken(string $token): bool;

    /**
     * token加入黑名单
     *
     * @param Plain $token
     * @return bool
     */
    abstract public function addTokenBlack(Plain $token): bool;

    /**
     * 黑名单是否存在当前token
     *
     * @param array $claims
     * @return bool
     */
    abstract public function hasTokenBlack(Plain $token): bool;

    /**
     * @param array $sceneConfig
     * @param string $claimJti
     * @return string
     */
    abstract public function getCacheKey(array $sceneConfig, string $claimJti): string;

    /**
     * Get the cache time limit.
     *
     * @return int
     */
    abstract public function getCacheTTL(string $token = null): int;
}
