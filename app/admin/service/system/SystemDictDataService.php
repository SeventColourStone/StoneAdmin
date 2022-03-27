<?php


namespace app\admin\service\system;


use app\admin\mapper\system\SystemDictDataMapper;
use DI\Annotation\Inject;
use nyuwa\abstracts\AbstractService;
use support\Redis;

class SystemDictDataService extends AbstractService
{
    /**
     * @Inject
     * @var SystemDictDataMapper
     */
    public $mapper;

    /**
     * @Value("cache.default.prefix")
     * @var string
     */
    protected $prefix;

    /**
     * 查询多个字典
     * @param array|null $params
     * @return array
     */
    public function getLists(?array $params = null): array
    {
        if (! isset($params['codes'])) {
            return [];
        }

        $codes = explode(',', $params['codes']);
        $data = [];

        foreach ($codes as $code) {
            $data[$code] = $this->getList(['code' => $code]);
        }

        return $data;
    }

    /**
     * 查询一个字典
     * @param array|null $params
     * @return array
     */
    public function getList(?array $params = null): array
    {
        if (! isset($params['code'])) {
            return [];
        }

        $key = $this->prefix . 'Dict:' .
            $params['code'];

        if ($data = Redis::get($key)) {
            return unserialize($data);
        }

        $args = [
            'select' => ['id', 'label', 'value'],
            'status' => '0',
            'orderBy' => 'sort',
            'orderType' => 'desc'
        ];
        $data = $this->mapper->getList(array_merge($args, $params), false);

        Redis::set($key, serialize($data));

        return $data;
    }

    /**
     * 清除缓存
     * @return bool
     */
    public function clearCache(): bool
    {
        $key = $this->prefix . 'Dict:*';
        foreach (Redis::keys($key) as $item) {
            Redis::del($item);
        }
        return true;
    }

}
