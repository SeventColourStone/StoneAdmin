<?php


namespace app\admin\service\system;


use app\admin\mapper\system\SystemRoleMapper;
use DI\Annotation\Inject;
use nyuwa\abstracts\AbstractService;
use nyuwa\exception\NormalStatusException;

class SystemRoleService extends AbstractService
{
    /**
     * @Inject
     * @var SystemRoleMapper
     */
    public $mapper;

    /**
     * 获取角色列表，并过滤掉超管角色
     * @param array|null $params
     * @return array
     */
    public function getList(?array $params = null): array
    {
        $params['filterManager'] = true;
        return parent::getList($params);
    }

    public function save(array $data): string
    {
        if ($this->mapper->checkRoleCode($data['code'])) {
            throw new NormalStatusException(trans('system.rolecode_exists'));
        }
        return $this->mapper->save($this->handleData($data));
    }

    /**
     * 通过角色获取菜单
     * @param string $id
     * @return array
     */
    public function getMenuByRole(string $id): array
    {
        return $this->mapper->getMenuIdsByRoleIds(['ids' => $id]);
    }

    /**
     * 通过角色获取部门
     * @param string $id
     * @return array
     */
    public function getDeptByRole(string $id): array
    {
        return $this->mapper->getDeptIdsByRoleIds(['ids' => $id]);
    }

    /**
     * 更新角色信息
     * @param string $id
     * @param array $data
     * @return bool
     */
    public function update(string $id, array $data): bool
    {
        return $this->mapper->update($id, $this->handleData($data));
    }

    /**
     * 处理数据
     * @param $data
     * @return array
     */
    protected function handleData($data): array
    {
        if (!empty($data['dept_ids']) && !is_array($data['dept_ids'])) {
            $data['dept_ids'] = explode(',', $data['dept_ids']);
        }
        if (!empty($data['menu_ids']) && !is_array($data['menu_ids'])) {
            $data['menu_ids'] = explode(',', $data['menu_ids']);
        }
        return $data;
    }
}
