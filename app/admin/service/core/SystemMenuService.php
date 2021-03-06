<?php

declare(strict_types=1);

namespace app\admin\service\core;


use app\admin\mapper\core\SystemMenuMapper;
use DI\Annotation\Inject;
use nyuwa\abstracts\AbstractService;

class SystemMenuService extends AbstractService
{
    /**
     * @Inject
     * @var SystemMenuMapper
     */
    public $mapper;


    /**
     * @param array|null $params
     * @return array
     */
    public function getTreeList(?array $params = null): array
    {
        $select = "id,parent_id,level,name as title,icon,redirect as href,type,open_type as openType";
        $params = array_merge(['orderBy' => 'sort', 'orderType' => 'asc','select' =>$select], $params);
        return parent::getTreeList($params);
    }

    /**
     * @param array|null $params
     * @return array
     */
    public function getTreeListByRecycle(?array $params = null): array
    {
        $params = array_merge(['orderBy' => 'sort', 'orderType' => 'asc'], $params);
        return parent::getTreeListByRecycle($params);
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
     * 通过code获取菜单名称
     * @param string $code
     * @return string
     * @throws \Psr\Container\ContainerExceptionInterface
     * @throws \Psr\Container\NotFoundExceptionInterface
     */
    public function findNameByCode(string $code): string
    {
        if (strlen($code) < 1) {
            return nyuwa_trans('system.undefined_menu');
        }
        $name = $this->mapper->findNameByCode($code);
        return $name ?? nyuwa_trans('system.undefined_menu');
    }

    /**
     * 新增菜单
     * @param array $data
     * @return string
     */
    public function save(array $data): string
    {
        $id = $this->mapper->save($this->handleData($data));

        // 生成RESTFUL按钮菜单
//        if ($data['type'] == SystemMenu::MENUS_LIST && $data['restful'] == '0') {
//            $model = $this->mapper->model::find($id, ['id', 'name', 'code']);
//            $this->genButtonMenu($model);
//        }

        return $id;
    }


    /**
     * 生成按钮菜单
     * @param SystemMenu $model
     * @return bool
     */
    public function genButtonMenu(SystemMenu $model): bool
    {
        $buttonMenus = [
            ['name' => $model->name.'列表', 'code' => $model->code.':index'],
            ['name' => $model->name.'回收站', 'code' => $model->code.':recycle'],
            ['name' => $model->name.'保存', 'code' => $model->code.':save'],
            ['name' => $model->name.'更新', 'code' => $model->code.':update'],
            ['name' => $model->name.'删除', 'code' => $model->code.':delete'],
            ['name' => $model->name.'读取', 'code' => $model->code.':read'],
            ['name' => $model->name.'恢复', 'code' => $model->code.':recovery'],
            ['name' => $model->name.'真实删除', 'code' => $model->code.':realDelete'],
            ['name' => $model->name.'导入', 'code' => $model->code.':import'],
            ['name' => $model->name.'导出', 'code' => $model->code.':export']
        ];

        foreach ($buttonMenus as $button) {
            $this->save(
                array_merge(
                    ['parent_id' => $model->id, 'type' => SystemMenu::BUTTON],
                    $button
                )
            );
        }

        return true;
    }

    /**
     * 更新菜单
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
     * @return mixed
     */
    protected function handleData($data) {
        if ($data['parent_id'] == 0) {
            $data['level'] = '0';
            $data['type'] = SystemMenu::DIRECTORY_LIST;
        } else {
            if (is_array($data['parent_id'])) {
                $data['parent_id'] = array_pop($data['parent_id']);
            }
            $parentMenu = $this->mapper->read((string)$data['parent_id']);
            $data['level'] = $parentMenu['level'] . ',' . $parentMenu['id'];
        }
        return $data;
    }

    /**
     * 真实删除菜单
     * @param string $ids
     * @return array
     */
    public function realDel(string $ids): ?array
    {
        $ids = explode(',', $ids);
        // 跳过的菜单
        $ctuIds = [];
        if (count($ids)) foreach ($ids as $id) {
            if (!$this->checkChildrenExists( (int) $id)) {
                $this->mapper->realDelete([$id]);
            } else {
                array_push($ctuIds, $id);
            }
        }
        return count($ctuIds) ? $this->mapper->getMenuName($ctuIds) : null;
    }

    /**
     * 检查子菜单是否存在
     * @param int $id
     * @return bool
     */
    public function checkChildrenExists(int $id): bool
    {
        return $this->mapper->checkChildrenExists($id);
    }

}
