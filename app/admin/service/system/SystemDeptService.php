<?php

declare(strict_types=1);

namespace app\admin\service\system;


use app\admin\mapper\system\SystemDeptMapper;
use DI\Annotation\Inject;
use nyuwa\abstracts\AbstractService;
use nyuwa\exception\NormalStatusException;

class SystemDeptService extends AbstractService
{
    /**
     * @Inject
     * @var SystemDeptMapper
     */
    public $mapper;


    /**
     * @param array|null $params
     * @param bool $isScope
     * @return array
     */
    public function getTreeList(?array $params = null, bool $isScope = true): array
    {
        $params = array_merge(['orderBy' => 'sort', 'orderType' => 'desc'], $params);
        return parent::getTreeList($params, $isScope);
    }

    /**
     * 获取前端选择树
     * @return array
     */
    public function getSelectTree(): array
    {
        return $this->mapper->getSelectTree();
    }

    /**
     * 新增部门
     * @param array $data
     * @return int
     */
    public function save(array $data): string
    {
        return $this->mapper->save($this->handleData($data));
    }

    /**
     * 更新部门
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
        $pid = $data['parent_id'] ?? 0;

        if ($pid === 0) {
            $data['level'] = $data['parent_id'] = '0';
        } else if (is_array($pid)){
            array_unshift($pid, '0');
            $data['level'] = implode(',', $pid);
            $data['parent_id'] = array_pop($pid);
        } else {
            $data['level'] = $this->read($data['id'])->level . ',' . $data['parent_id'];
        }

        if ($data['id'] == $data['parent_id']) {
            throw new NormalStatusException(trans('system.parent_dept_error'), 500);
        }

        return $data;
    }

    /**
     * 真实删除部门
     * @param string $ids
     * @return array|null
     */
    public function realDel(string $ids): ?array
    {
        $ids = explode(',', $ids);
        // 跳过的部门
        $ctuIds = [];
        if (count($ids)) foreach ($ids as $id) {
            if (!$this->checkChildrenExists( (int) $id)) {
                $this->mapper->realDelete([$id]);
            } else {
                array_push($ctuIds, $id);
            }
        }
        return count($ctuIds) ? $this->mapper->getDeptName($ctuIds) : null;
    }

    /**
     * 检查子部门是否存在
     * @param int $id
     * @return bool
     */
    public function checkChildrenExists(int $id): bool
    {
        return $this->mapper->checkChildrenExists($id);
    }

}
