<?php


namespace nyuwa\helper;


////获取当前登录的用户数据。
use InvalidArgumentException;
use nyuwa\auth\JWT;
use nyuwa\exception\TokenException;
use support\Log;
use Webman\Container;

class LoginUser
{
    /**
     * @var JWT
     */
    protected $jwt;

    public function __construct(string $scene = 'default'){
        $this->jwt = nyuwa_app(JWT::class);
        $this->jwt->setScene($scene);
    }

    /**
     * 验证token
     * @param string|null $token
     * @param string $scene
     * @return bool
     * @throws \Psr\Container\ContainerExceptionInterface
     * @throws \Psr\Container\NotFoundExceptionInterface
     */
    public function check(?string $token = null, string $scene = 'default'): bool
    {
        try {
            if ($this->jwt->verifyTokenByScene($token, $scene)) {
                return true;
            }
        } catch (InvalidArgumentException $e) {
            throw new TokenException(nyuwa_trans('jwt.no_token'));
        } catch (\Throwable $e) {
            throw new TokenException(nyuwa_trans('jwt.no_login'));
        }

        return false;
    }

    /**
     * 获取JWT对象
     * @return Jwt
     */
    public function getJwt(): Jwt
    {
        return $this->jwt;
    }

    /**
     * 获取当前登录用户信息
     * @param string|null $token
     * @return array
     */
    public function getUserInfo(?string $token = null): array
    {
        return $this->jwt->getClaimsByToken($token);
    }

    /**
     * 获取当前登录用户ID
     * @return string
     */
    public function getId(): string
    {
        return (string) $this->jwt->getClaimsByToken()['id'];
    }

    /**
     * 获取当前登录用户名
     * @return string
     */
    public function getUsername(): string
    {
        return $this->jwt->getClaimsByToken()['username'];
    }

    /**
     * 获取当前token用户角色
     * @return string
     */
    public function getRole(): string
    {
        return $this->jwt->getClaimsByToken()['role'];
    }

    /**
     * 获取当前token用户类型
     * @return string
     */
    public function getUserType(): string
    {
        return $this->jwt->getClaimsByToken()['user_type'];
    }

    /**
     * 获取当前token用户部门ID
     * @return string
     */
    public function getDeptId(): string
    {
        return $this->jwt->getClaimsByToken()['dept_id'];
    }

    /**
     * 是否为超级管理员（创始人），用户禁用对创始人没用
     * @return bool
     */
    public function isSuperAdmin():bool
    {
        return env('SUPER_ADMIN') == $this->getId();
    }

    /**
     * 是否为管理员角色
     * @return bool
     */
    public function isAdminRole(): bool
    {
        return env('ADMIN_ROLE') == $this->getRole();
    }

    /**
     * 获取Token
     * @param array $user
     * @return string
     * @throws InvalidArgumentException
     */
    public function createToken(array $user): array
    {
        Log::info("创建的用户：".json_encode($user,JSON_UNESCAPED_UNICODE));
        return $this->jwt->createToken($user);
    }

    /**
     * 刷新token
     * @return string
     * @throws InvalidArgumentException
     */
    public function refresh(string $token): string
    {
        return $this->jwt->refreshToken($token);
    }

}
