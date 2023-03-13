<?php


namespace plugin\stone\nyuwa\auth;


use Lcobucci\Clock\SystemClock;
use Lcobucci\JWT\Configuration;
use Lcobucci\JWT\Encoding\ChainedFormatter;
use Lcobucci\JWT\Signer;
use Lcobucci\JWT\Signer\Key\InMemory;
use Lcobucci\JWT\Token\DataSet;
use Lcobucci\JWT\Token\Plain;
use Lcobucci\JWT\Token\RegisteredClaims;
use Lcobucci\JWT\Validation\Constraint\IdentifiedBy;
use Lcobucci\JWT\Validation\Constraint\IssuedBy;
use Lcobucci\JWT\Validation\Constraint\LooseValidAt;
use Lcobucci\JWT\Validation\Constraint\PermittedFor;
use Lcobucci\JWT\Validation\Constraint\RelatedTo;
use Lcobucci\JWT\Validation\Constraint\SignedWith;
use Lcobucci\JWT\Validation\Constraint\StrictValidAt;
use plugin\stone\nyuwa\auth\constant\JWTConstant as JWTConstantAlias;
use plugin\stone\nyuwa\auth\exception\JWTException;
use plugin\stone\nyuwa\auth\exception\TokenValidException;
use plugin\stone\nyuwa\auth\util\JWTUtil;
use plugin\stone\nyuwa\auth\util\TimeUtil;
use plugin\stone\nyuwa\exception\TokenException;
use ReflectionClass;
use support\Cache;
use support\Log;
use support\Request;
use Webman\App;
use Webman\Config;

class JWT extends AbstractJWT
{

    private $supportAlgs = [
        // 非对称算法
        'RS256' => 'Lcobucci\JWT\Signer\Rsa\Sha256',
        'RS384' => 'Lcobucci\JWT\Signer\Rsa\Sha384',
        'RS512' => 'Lcobucci\JWT\Signer\Rsa\Sha512',
        'ES256' => 'Lcobucci\JWT\Signer\Ecdsa\Sha256',
        'ES384' => 'Lcobucci\JWT\Signer\Ecdsa\Sha384',
        'ES512' => 'Lcobucci\JWT\Signer\Ecdsa\Sha512',

        // 对称算法
        'HS256' => 'Lcobucci\JWT\Signer\Hmac\Sha256',
        'HS384' => 'Lcobucci\JWT\Signer\Hmac\Sha384',
        'HS512' => 'Lcobucci\JWT\Signer\Hmac\Sha512',
    ];

    /**
     * @var string
     */
    private $jwtClaimScene = 'jwt_scene';

    private $scene = "";

    /**
     * 为默认scene配置
     * @var array|mixed
     */
    private $jwtConfig;

    /**
     * @var Configuration
     */
    private $lcobucciJwtConfiguration;

    /**
     * @var PathMatch
     */
    private $pathMatch;


    /**
     * 根据场景值初始化对应的配置信息
     * @param string $scene
     */
    private function initConfiguration(string $scene)
    {
        $this->setScene($scene);
        $jwtSceneConfig = $this->getJwtSceneConfig($scene);
        $useAlgsClass = $this->supportAlgs[$jwtSceneConfig['alg']];//这个地方貌似会出异常。
        $algInfo = ["alg"=>$jwtSceneConfig['alg'] ,"alg_class"=>$useAlgsClass];
        if (!$this->isAsymmetric($algInfo)) {
            $this->lcobucciJwtConfiguration = Configuration::forSymmetricSigner(
                new $useAlgsClass(),
                InMemory::base64Encoded(base64_encode($jwtSceneConfig['secret']))
            );
        } else {
            $this->lcobucciJwtConfiguration = Configuration::forAsymmetricSigner(
                new $useAlgsClass(),
                InMemory::file($jwtSceneConfig['keys']['private'], $jwtSceneConfig['keys']['passphrase']),
                InMemory::file($jwtSceneConfig['keys']['public'])
            );
        }
        return $this;
    }

    /**
     * 构造函数，初始化所有的配置信息进去
     * JWT constructor.
     */
    public function __construct()
    {
        //把所有应用都注入redis
        $expConfig = [
            "login_type"=> "sso",
            "sso_key"=> "id",
            "secret"=> "secret",
            "alg"=> "alg",
            "iss"=> "iss",
            "ttl"=> "ttl",
            //可选,非对称算法
            "keys"=> [
                "public"=>"",
                "private"=>"",
                "passphrase"=>"",//你的私钥的密码。不需要密码可以不用设置
            ],
        ];
    }


    public function createToken(array $claims):string
    {
        // 初始化lcobucci jwt config
        $this->initConfiguration($this->getScene());

        $claims[$this->jwtClaimScene] = $this->getScene(); // 加入场景值

        $expConfig = [
            "login_type"=> "sso",
            "sso_key"=> "id",
            "secret"=> "secret",
            "alg"=> "alg",
            "iss"=> "iss",
            "ttl"=> "ttl",
            //可选,非对称算法
            "keys"=> [
                "public"=>"",
                "private"=>"",
                "passphrase"=>"",//你的私钥的密码。不需要密码可以不用设置
            ],
        ];

        $jwtSceneConfig = $this->getJwtSceneConfig();
        $loginType = $jwtSceneConfig['login_type'];
        $ssoKey = $jwtSceneConfig['sso_key'];
        $issuedBy = $jwtSceneConfig[RegisteredClaims::ISSUER] ?? 'stone/jwt';
        if ($loginType == JWTConstantAlias::MPOP) { // 多点登录,场景值加上一个唯一id
            $uniqid = uniqid($this->getScene() . '_', true);
        } else { // 单点登录
            if (empty($claims[$ssoKey])) {
                //需要用户id key
                throw new JWTException("There is no {$ssoKey} key in the claims", 400);
            }
            $uniqid = $this->getScene() . "_" . $claims[$ssoKey];
        }

        $clock = SystemClock::fromSystemTimezone();
        $now = $clock->now();
        $expiresAt = $clock->now()->modify('+' . $jwtSceneConfig['ttl'] . ' second');
        $builder = $this->lcobucciJwtConfiguration->builder(ChainedFormatter::withUnixTimestampDates());
        foreach ($claims as $k => $v) {
            $builder = $builder->withClaim($k, $v); // 自定义数据
        }
        $builder = $builder
            ->issuedBy($issuedBy)
            // Configures the id (jti claim) 设置jwt的jti
            ->identifiedBy($uniqid)
            // Configures the time that the token was issue (iat claim) 发布时间
            ->issuedAt($now)
            // Configures the time that the token can be used (nbf claim) 在此之前不可用
            ->canOnlyBeUsedAfter($now)
            // Configures the expiration time of the token (exp claim) 到期时间
            ->expiresAt($expiresAt);
        $tokenPlain = $builder->getToken($this->lcobucciJwtConfiguration->signer(), $this->lcobucciJwtConfiguration->signingKey());
        $token = $tokenPlain->toString();

        return $token;
//            [
//            "expires_at" => $expiresAt->format("Y-m-d H:i:s"),
//            "expires_in" => $jwtSceneConfig['ttl'],
//            "access_token" => $token
//        ];
    }

    public function verifyToken(string $token): bool
    {
        if($token == null) {
            $token = JWTUtil::getToken();
        }

        $token = $this->tokenToPlain($token);
        $this->initConfiguration($this->getSceneByTokenPlain($token));

        $sceneConfig = $this->getSceneConfigByToken($token);
        $constraints = $this->validationConstraints($token->claims(), $this->lcobucciJwtConfiguration);
        if (!$this->lcobucciJwtConfiguration->validator()->validate($token, ...$constraints)) {
            throw new TokenValidException('Token authentication does not pass', 400);
        }

        // 验证token是否存在黑名单
        if ($sceneConfig['blacklist_enabled'] && $this->hasTokenBlack($token)) {
            throw new TokenValidException('Token authentication does not pass，in TokenBlackList', 400);
        }

        return true;
    }

    public function verifyTokenByScene(string $token = null, string $scene = "default"): bool
    {
        if($token == null) {
            $token = JWTUtil::getToken();
        }
        $plainToken = $this->tokenToPlain($token);
        $tokenScene = $this->getSceneByTokenPlain($plainToken);
        if ($scene != $tokenScene) {
            throw new JWTException('The token does not support the current scene', 400);
        }

        return $this->verifyToken($token);
    }

    public function getSceneByToken(string $token): string
    {
        if($token == null) {
            $token = JWTUtil::getToken();
        }
        $token = $this->tokenToPlain($token);
        return $this->getSceneByTokenPlain($token);
    }

    public function refreshToken(string $token = null): string
    {
        if($token == null) {
            //从header获取
            $token = JWTUtil::getToken();
        }

        $token = $this->tokenToPlain($token);

        $this->addTokenBlack($token);

        $claims = $token->claims();
        $data = JWTUtil::claimsToArray($claims);
        $scene = $this->getSceneByClaims($claims);
        unset($data[RegisteredClaims::ISSUER]);
        unset($data[RegisteredClaims::EXPIRATION_TIME]);
        unset($data[RegisteredClaims::NOT_BEFORE]);
        unset($data[RegisteredClaims::ISSUED_AT]);
        unset($data[RegisteredClaims::ID]);
        return $this->createToken($data,$scene);
    }

    public function getClaimsByToken(string $token = null): array
    {
        if($token == null) {
            $token = JWTUtil::getToken();
        }

        return $this->tokenToPlain($token)->claims()->all();
    }

    public function tokenToPlain(string $token): Plain
    {
        if($token == null) {
            $token = JWTUtil::getToken();
        }

        return JWTUtil::getParser()->parse($token);
    }

    public function getTokenTTL(string $token): int
    {
        if($token == null) {
            $token = JWTUtil::getToken();
        }

        $token = JWTUtil::getParser()->parse($token);
        $sceneConfig = $this->getSceneConfigByToken($token);
        return (int)$sceneConfig['ttl'];
    }

    public function getTokenDynamicCacheTime(string $token): int
    {
        if($token == null) {
            throw new JWTException("Missing token");
        }

        $nowTime = TimeUtil::now();
        $expTime = $this->tokenToPlain($token)->claims()->get(RegisteredClaims::EXPIRATION_TIME, $nowTime);
        if (!is_numeric($expTime)) {
            $expTime = $expTime->getTimestamp();
        }
        return TimeUtil::now()->setTimestamp($expTime)->max($nowTime)->diffInSeconds();
    }

    public function invalidToken(string $token = null): bool
    {
        if($token == null) {
            $token = JWTUtil::getToken();
        }

        $token = $this->tokenToPlain($token);
        $this->addTokenBlack($token);
        return true;
    }

    public function addTokenBlack(Plain $token): bool
    {
        $sceneConfig = $this->getSceneConfigByToken($token);
        $jwtConfig = Config::get(JWTConstantAlias::CONFIG_NAME);
        $claims = $token->claims();
        if ($sceneConfig['blacklist_enabled']) {
            $cacheKey = $this->getCacheKey($jwtConfig, $claims->get(RegisteredClaims::ID));
            // 单点登录没有宽限时间，直接失效
            $blacklistGracePeriod = 0;
            if ($sceneConfig['login_type'] == JWTConstantAlias::MPOP) {
                $blacklistGracePeriod = $sceneConfig['blacklist_grace_period'];
            }
            $expTime = $claims->get(RegisteredClaims::EXPIRATION_TIME);
            if (!is_numeric($expTime)) {
                $expTime = $expTime->getTimestamp();
            }

            $validUntil = TimeUtil::now()->addSeconds($blacklistGracePeriod)->getTimestamp();
            $expTime = TimeUtil::timestamp($expTime);
            $nowTime = TimeUtil::now();

            /**
             * 缓存时间取当前时间跟jwt过期时间的差值，单位秒
             */
            $tokenCacheTime = $expTime->max($nowTime)->diffInSeconds();
            return Cache::set(
                $cacheKey,
                ['valid_until' => $validUntil],
                $tokenCacheTime
            );
        }
        return false;
    }

    public function hasTokenBlack(Plain $token): bool
    {
        $sceneConfig = $this->getSceneConfigByToken($token);
        $jwtConfig = Config::get(JWTConstantAlias::CONFIG_NAME);
        if ($sceneConfig['blacklist_enabled']) {
            $claims = $token->claims();
            $cacheKey = $this->getCacheKey($jwtConfig, $claims->get(RegisteredClaims::ID));
            $cacheValue = Cache::get($cacheKey);
            if ($sceneConfig['login_type'] == JWTConstantAlias::MPOP) {
                return !empty($cacheValue['valid_until']) && !TimeUtil::isFuture($cacheValue['valid_until']);
            }

            if ($sceneConfig['login_type'] == JWTConstantAlias::SSO) {
                // 签发时间
                $iatTime = $claims->get(RegisteredClaims::ISSUED_AT);
                if (!is_numeric($iatTime)) {
                    $iatTime = $iatTime->getTimestamp();
                }
                if (!empty($cacheValue['valid_until']) && !empty($iatTime)) {
                    // 这里为什么要大于等于0，因为在刷新token时，缓存时间跟签发时间可能一致，详细请看刷新token方法
                    return !(($iatTime - $cacheValue['valid_until']) >= 0);
                }
            }
        }

        return false;
    }

    public function getCacheKey(array $sceneConfig, string $claimJti): string
    {
        return $sceneConfig["cache_prefix"] . '-' . $claimJti;
    }

    public function getCacheTTL(string $token = null): int
    {
        // TODO: Implement getCacheTTL() method.
    }



    public function getScene(): string
    {
        return $this->scene;
    }

    public function setScene(string $scene = 'default'): JWT
    {
        $this->scene = $scene;
        return $this;
    }

    /**
     * 获取当前场景的配置
     *
     * @return mixed
     */
    public function getJwtSceneConfig(string $scene = null) {

        $sceneConfig = Config::get(JWTConstantAlias::CONFIG_NAME, []);
        $cachePrefix = $sceneConfig['cache_prefix'];
        if ($scene != null) {
            //获取配置
            $sceneConfigKey = $cachePrefix . '-' .$scene;
            //获取不到则置空导致出错了
            $sceneConfigByCahce = Cache::get($sceneConfigKey);
            if (!$sceneConfigByCahce){
                //基础配置组
                $jwtBasicConfig = Config::get(JWTConstantAlias::DEFAULT_NANE);
                $sceneConfig = Config::get(JWTConstantAlias::CONFIG_NAME.".scene.".$scene);
                if (empty($sceneConfig)){
                    throw new TokenException("没有配置该应用授权信息:".$scene);
                }
                //把配置写入
                $sceneConfig = array_merge($jwtBasicConfig, $sceneConfig);
                Cache::set($sceneConfigKey, $sceneConfig);
                $sceneConfigByCahce = $sceneConfig;
            }
            return $sceneConfigByCahce;
        }
        //返回默认
        $sceneConfigKey = $cachePrefix . '-' .$this->getScene();
        return Cache::get($sceneConfigKey);
    }

    public function setConfigSecne($sceneConfigKey,$scene){
        //基础配置组
        $jwtBasicConfig = Config::get(JWTConstantAlias::DEFAULT_NANE);
        $sceneConfig = Config::get(JWTConstantAlias::CONFIG_NAME.".scene.".$scene);
        if (empty($sceneConfig)){
            throw new TokenException("没有配置该应用授权信息:".$scene);
        }
        //把配置写入
        $sceneConfig = array_merge($jwtBasicConfig, $sceneConfig);
        Cache::set($sceneConfigKey, $sceneConfig);
    }




    /**
     * 判断是否为非对称算法
     */
    protected function isAsymmetric(array $algInfo): bool
    {
        $reflect = new ReflectionClass($this->getSigner($algInfo));

        return $reflect->isSubclassOf(Signer\Rsa::class) || $reflect->isSubclassOf(Signer\Ecdsa::class);
    }

    /**
     * 获取Signer
     *
     * @return Signer
     */
    protected function getSigner(array $algInfo): Signer
    {
        $alg = $algInfo['alg'];
        if (! array_key_exists($alg, $this->supportAlgs)) {
            throw new JWTException('The given supportAlgs could not be found', 400);
        }

        return new $algInfo['alg_class'];//$this->supportAlgs[$alg];
    }

    /**
     * @param Plain $token
     * @return string
     */
    protected function getSceneByTokenPlain(Plain $token): string
    {
        $claims = $token->claims()->all();
        return $claims[$this->jwtClaimScene];
    }

    /**
     * 通过token获取当前场景的配置
     *
     * @param Plain $token
     * @return string
     */
    protected function getSceneConfigByToken(Plain $token): array
    {
        $scene = $this->getSceneByTokenPlain($token);
        return $this->getJwtSceneConfig($scene);
    }

    protected function getSceneByClaims(DataSet $claims) {
        return $claims->get($this->jwtClaimScene, $this->getScene());
    }

    /**
     * https://lcobucci-jwt.readthedocs.io/en/latest/validating-tokens/
     * JWT 验证时，支持的校验
     * 'Lcobucci\JWT\Validation\Constraint\IdentifiedBy',
     * 'Lcobucci\JWT\Validation\Constraint\IssuedBy',
     * 'Lcobucci\JWT\Validation\Constraint\PermittedFor',
     * 'Lcobucci\JWT\Validation\Constraint\RelatedTo',
     * 'Lcobucci\JWT\Validation\Constraint\SignedWith',
     * 'Lcobucci\JWT\Validation\Constraint\StrictValidAt',
     * 'Lcobucci\JWT\Validation\Constraint\LooseValidAt'
     * @return array
     */
    protected function validationConstraints(DataSet $claims, Configuration $configuration)
    {
        $clock = SystemClock::fromUTC();
        $validationConstraints = [
            new IdentifiedBy($claims->get(RegisteredClaims::ID)),
            new IssuedBy($claims->get(RegisteredClaims::ISSUER)),
            new LooseValidAt($clock),
            new StrictValidAt($clock),
            new SignedWith($configuration->signer(), $configuration->verificationKey())
        ];
        if ($claims->get(RegisteredClaims::AUDIENCE) != null) {
            $validationConstraints[] = new PermittedFor($claims->get(RegisteredClaims::AUDIENCE));
        }
        if ($claims->get(RegisteredClaims::SUBJECT) != null) {
            $validationConstraints[] = new RelatedTo($claims->get(RegisteredClaims::SUBJECT));
        }
        return $validationConstraints;
    }

}
