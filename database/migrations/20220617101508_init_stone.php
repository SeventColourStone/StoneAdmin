<?php

use Phinx\Db\Adapter\MysqlAdapter;

class InitStone extends Phinx\Migration\AbstractMigration
{
    public function change()
    {
        $this->execute("ALTER DATABASE CHARACTER SET 'utf8mb4';");
        $this->execute("ALTER DATABASE COLLATE='utf8mb4_general_ci';");
        $this->table('setting_config', [
                'id' => false,
                'primary_key' => ['key'],
                'engine' => 'InnoDB',
                'encoding' => 'utf8mb4',
                'collation' => 'utf8mb4_general_ci',
                'comment' => '参数配置信息表',
                'row_format' => 'DYNAMIC',
            ])
            ->addColumn('key', 'string', [
                'null' => false,
                'limit' => 32,
                'collation' => 'utf8mb4_general_ci',
                'encoding' => 'utf8mb4',
                'comment' => '配置键名',
            ])
            ->addColumn('value', 'string', [
                'null' => true,
                'limit' => 255,
                'collation' => 'utf8mb4_general_ci',
                'encoding' => 'utf8mb4',
                'comment' => '配置值',
                'after' => 'key',
            ])
            ->addColumn('name', 'string', [
                'null' => true,
                'limit' => 255,
                'collation' => 'utf8mb4_general_ci',
                'encoding' => 'utf8mb4',
                'comment' => '配置名称',
                'after' => 'value',
            ])
            ->addColumn('group_name', 'string', [
                'null' => true,
                'limit' => 100,
                'collation' => 'utf8mb4_general_ci',
                'encoding' => 'utf8mb4',
                'comment' => '组名称',
                'after' => 'name',
            ])
            ->addColumn('sort', 'integer', [
                'null' => true,
                'default' => '0',
                'limit' => MysqlAdapter::INT_TINY,
                'comment' => '排序',
                'after' => 'group_name',
            ])
            ->addColumn('remark', 'string', [
                'null' => true,
                'limit' => 255,
                'collation' => 'utf8mb4_general_ci',
                'encoding' => 'utf8mb4',
                'comment' => '备注',
                'after' => 'sort',
            ])
            ->addIndex(['group_name'], [
                'name' => 'setting_config_group_name_index',
                'unique' => false,
            ])
            ->create();
        $this->table('setting_crontab', [
                'id' => false,
                'primary_key' => ['id'],
                'engine' => 'InnoDB',
                'encoding' => 'utf8mb4',
                'collation' => 'utf8mb4_general_ci',
                'comment' => '定时任务信息表',
                'row_format' => 'DYNAMIC',
            ])
            ->addColumn('id', 'integer', [
                'null' => false,
                'limit' => MysqlAdapter::INT_BIG,
                'signed' => false,
                'comment' => '主键',
            ])
            ->addColumn('name', 'string', [
                'null' => true,
                'limit' => 100,
                'collation' => 'utf8mb4_general_ci',
                'encoding' => 'utf8mb4',
                'comment' => '任务名称',
                'after' => 'id',
            ])
            ->addColumn('type', 'char', [
                'null' => true,
                'default' => '4',
                'limit' => 1,
                'collation' => 'utf8mb4_general_ci',
                'encoding' => 'utf8mb4',
                'comment' => '任务类型 (1 command, 2 class, 3 url, 4 eval)',
                'after' => 'name',
            ])
            ->addColumn('target', 'string', [
                'null' => true,
                'limit' => 500,
                'collation' => 'utf8mb4_general_ci',
                'encoding' => 'utf8mb4',
                'comment' => '调用任务字符串',
                'after' => 'type',
            ])
            ->addColumn('parameter', 'string', [
                'null' => true,
                'limit' => 1000,
                'collation' => 'utf8mb4_general_ci',
                'encoding' => 'utf8mb4',
                'comment' => '调用任务参数',
                'after' => 'target',
            ])
            ->addColumn('rule', 'string', [
                'null' => true,
                'limit' => 32,
                'collation' => 'utf8mb4_general_ci',
                'encoding' => 'utf8mb4',
                'comment' => '任务执行表达式',
                'after' => 'parameter',
            ])
            ->addColumn('running_times', 'integer', [
                'null' => true,
                'limit' => '10',
                'comment' => '执行次数',
                'after' => 'rule',
            ])
            ->addColumn('last_running_time', 'datetime', [
                'null' => true,
                'comment' => '最后执行时间',
                'after' => 'running_times',
            ])
            ->addColumn('singleton', 'char', [
                'null' => true,
                'default' => '0',
                'limit' => 1,
                'collation' => 'utf8mb4_general_ci',
                'encoding' => 'utf8mb4',
                'comment' => '是否单次执行 (0 是 1 不是)',
                'after' => 'last_running_time',
            ])
            ->addColumn('status', 'char', [
                'null' => true,
                'default' => '0',
                'limit' => 1,
                'collation' => 'utf8mb4_general_ci',
                'encoding' => 'utf8mb4',
                'comment' => '状态 (0正常 1停用)',
                'after' => 'singleton',
            ])
            ->addColumn('created_by', 'integer', [
                'null' => true,
                'limit' => MysqlAdapter::INT_BIG,
                'comment' => '创建者',
                'after' => 'status',
            ])
            ->addColumn('updated_by', 'integer', [
                'null' => true,
                'limit' => MysqlAdapter::INT_BIG,
                'comment' => '更新者',
                'after' => 'created_by',
            ])
            ->addColumn('created_at', 'timestamp', [
                'null' => true,
                'comment' => '创建时间',
                'after' => 'updated_by',
            ])
            ->addColumn('updated_at', 'timestamp', [
                'null' => true,
                'comment' => '更新时间',
                'after' => 'created_at',
            ])
            ->addColumn('remark', 'string', [
                'null' => true,
                'limit' => 255,
                'collation' => 'utf8mb4_general_ci',
                'encoding' => 'utf8mb4',
                'comment' => '备注',
                'after' => 'updated_at',
            ])
            ->create();
        $this->table('setting_crontab_log', [
                'id' => false,
                'primary_key' => ['id'],
                'engine' => 'InnoDB',
                'encoding' => 'utf8mb4',
                'collation' => 'utf8mb4_general_ci',
                'comment' => '定时任务执行日志表',
                'row_format' => 'DYNAMIC',
            ])
            ->addColumn('id', 'integer', [
                'null' => false,
                'limit' => MysqlAdapter::INT_BIG,
                'signed' => false,
                'identity' => 'enable',
                'comment' => '主键',
            ])
            ->addColumn('crontab_id', 'integer', [
                'null' => false,
                'limit' => MysqlAdapter::INT_BIG,
                'signed' => false,
                'comment' => '任务ID',
                'after' => 'id',
            ])
            ->addColumn('name', 'string', [
                'null' => true,
                'limit' => 255,
                'collation' => 'utf8mb4_general_ci',
                'encoding' => 'utf8mb4',
                'comment' => '任务名称',
                'after' => 'crontab_id',
            ])
            ->addColumn('target', 'string', [
                'null' => true,
                'limit' => 500,
                'collation' => 'utf8mb4_general_ci',
                'encoding' => 'utf8mb4',
                'comment' => '任务调用目标字符串',
                'after' => 'name',
            ])
            ->addColumn('parameter', 'string', [
                'null' => true,
                'limit' => 1000,
                'collation' => 'utf8mb4_general_ci',
                'encoding' => 'utf8mb4',
                'comment' => '任务调用参数',
                'after' => 'target',
            ])
            ->addColumn('exception_info', 'string', [
                'null' => true,
                'limit' => 2000,
                'collation' => 'utf8mb4_general_ci',
                'encoding' => 'utf8mb4',
                'comment' => '异常信息',
                'after' => 'parameter',
            ])
            ->addColumn('status', 'char', [
                'null' => true,
                'default' => '0',
                'limit' => 1,
                'collation' => 'utf8mb4_general_ci',
                'encoding' => 'utf8mb4',
                'comment' => '执行状态 (0成功 1失败)',
                'after' => 'exception_info',
            ])
            ->addColumn('created_at', 'timestamp', [
                'null' => true,
                'comment' => '创建时间',
                'after' => 'status',
            ])
            ->create();
        $this->table('setting_generate_columns', [
                'id' => false,
                'primary_key' => ['id'],
                'engine' => 'InnoDB',
                'encoding' => 'utf8mb4',
                'collation' => 'utf8mb4_general_ci',
                'comment' => '代码生成业务字段信息表',
                'row_format' => 'DYNAMIC',
            ])
            ->addColumn('id', 'integer', [
                'null' => false,
                'limit' => MysqlAdapter::INT_BIG,
                'signed' => false,
                'comment' => '主键',
            ])
            ->addColumn('table_id', 'integer', [
                'null' => false,
                'limit' => MysqlAdapter::INT_BIG,
                'signed' => false,
                'comment' => '所属表ID',
                'after' => 'id',
            ])
            ->addColumn('column_name', 'string', [
                'null' => true,
                'limit' => 200,
                'collation' => 'utf8mb4_general_ci',
                'encoding' => 'utf8mb4',
                'comment' => '字段名称',
                'after' => 'table_id',
            ])
            ->addColumn('column_comment', 'string', [
                'null' => true,
                'limit' => 255,
                'collation' => 'utf8mb4_general_ci',
                'encoding' => 'utf8mb4',
                'comment' => '字段注释',
                'after' => 'column_name',
            ])
            ->addColumn('column_type', 'string', [
                'null' => true,
                'limit' => 50,
                'collation' => 'utf8mb4_general_ci',
                'encoding' => 'utf8mb4',
                'comment' => '字段类型',
                'after' => 'column_comment',
            ])
            ->addColumn('is_pk', 'char', [
                'null' => true,
                'default' => '0',
                'limit' => 1,
                'collation' => 'utf8mb4_general_ci',
                'encoding' => 'utf8mb4',
                'comment' => '0 非主键 1 主键',
                'after' => 'column_type',
            ])
            ->addColumn('is_required', 'char', [
                'null' => true,
                'default' => '0',
                'limit' => 1,
                'collation' => 'utf8mb4_general_ci',
                'encoding' => 'utf8mb4',
                'comment' => '0 非必填 1 必填',
                'after' => 'is_pk',
            ])
            ->addColumn('is_insert', 'char', [
                'null' => true,
                'default' => '0',
                'limit' => 1,
                'collation' => 'utf8mb4_general_ci',
                'encoding' => 'utf8mb4',
                'comment' => '0 非插入字段 1 插入字段',
                'after' => 'is_required',
            ])
            ->addColumn('is_edit', 'char', [
                'null' => true,
                'default' => '0',
                'limit' => 1,
                'collation' => 'utf8mb4_general_ci',
                'encoding' => 'utf8mb4',
                'comment' => '0 非编辑字段 1 编辑字段',
                'after' => 'is_insert',
            ])
            ->addColumn('is_list', 'char', [
                'null' => true,
                'default' => '0',
                'limit' => 1,
                'collation' => 'utf8mb4_general_ci',
                'encoding' => 'utf8mb4',
                'comment' => '0 非列表显示字段 1 列表显示字段',
                'after' => 'is_edit',
            ])
            ->addColumn('is_query', 'char', [
                'null' => true,
                'default' => '0',
                'limit' => 1,
                'collation' => 'utf8mb4_general_ci',
                'encoding' => 'utf8mb4',
                'comment' => '0 非查询字段 1 查询字段',
                'after' => 'is_list',
            ])
            ->addColumn('query_type', 'string', [
                'null' => true,
                'default' => 'eq',
                'limit' => 100,
                'collation' => 'utf8mb4_general_ci',
                'encoding' => 'utf8mb4',
                'comment' => '查询方式 eq 等于, neq 不等于, gt 大于, lt 小于, like 范围',
                'after' => 'is_query',
            ])
            ->addColumn('view_type', 'string', [
                'null' => true,
                'default' => 'text',
                'limit' => 100,
                'collation' => 'utf8mb4_general_ci',
                'encoding' => 'utf8mb4',
                'comment' => '页面控件，text, textarea, password, select, checkbox, radio, date, upload, ma-upload（封装的上传控件）',
                'after' => 'query_type',
            ])
            ->addColumn('dict_type', 'string', [
                'null' => true,
                'limit' => 200,
                'collation' => 'utf8mb4_general_ci',
                'encoding' => 'utf8mb4',
                'comment' => '字典类型',
                'after' => 'view_type',
            ])
            ->addColumn('allow_roles', 'string', [
                'null' => true,
                'limit' => 255,
                'collation' => 'utf8mb4_general_ci',
                'encoding' => 'utf8mb4',
                'comment' => '允许查看该字段的角色',
                'after' => 'dict_type',
            ])
            ->addColumn('options', 'string', [
                'null' => true,
                'limit' => 1000,
                'collation' => 'utf8mb4_general_ci',
                'encoding' => 'utf8mb4',
                'comment' => '字段其他设置',
                'after' => 'allow_roles',
            ])
            ->addColumn('sort', 'integer', [
                'null' => true,
                'default' => '0',
                'limit' => MysqlAdapter::INT_TINY,
                'comment' => '排序',
                'after' => 'options',
            ])
            ->addColumn('created_by', 'integer', [
                'null' => true,
                'limit' => MysqlAdapter::INT_BIG,
                'comment' => '创建者',
                'after' => 'sort',
            ])
            ->addColumn('updated_by', 'integer', [
                'null' => true,
                'limit' => MysqlAdapter::INT_BIG,
                'comment' => '更新者',
                'after' => 'created_by',
            ])
            ->addColumn('created_at', 'timestamp', [
                'null' => true,
                'comment' => '创建时间',
                'after' => 'updated_by',
            ])
            ->addColumn('updated_at', 'timestamp', [
                'null' => true,
                'comment' => '更新时间',
                'after' => 'created_at',
            ])
            ->addColumn('remark', 'string', [
                'null' => true,
                'limit' => 255,
                'collation' => 'utf8mb4_general_ci',
                'encoding' => 'utf8mb4',
                'comment' => '备注',
                'after' => 'updated_at',
            ])
            ->create();
        $this->table('setting_generate_tables', [
                'id' => false,
                'primary_key' => ['id'],
                'engine' => 'InnoDB',
                'encoding' => 'utf8mb4',
                'collation' => 'utf8mb4_general_ci',
                'comment' => '代码生成业务信息表',
                'row_format' => 'DYNAMIC',
            ])
            ->addColumn('id', 'integer', [
                'null' => false,
                'limit' => MysqlAdapter::INT_BIG,
                'signed' => false,
                'comment' => '主键',
            ])
            ->addColumn('table_name', 'string', [
                'null' => true,
                'limit' => 200,
                'collation' => 'utf8mb4_general_ci',
                'encoding' => 'utf8mb4',
                'comment' => '表名称',
                'after' => 'id',
            ])
            ->addColumn('table_comment', 'string', [
                'null' => true,
                'limit' => 500,
                'collation' => 'utf8mb4_general_ci',
                'encoding' => 'utf8mb4',
                'comment' => '表注释',
                'after' => 'table_name',
            ])
            ->addColumn('module_name', 'string', [
                'null' => true,
                'limit' => 100,
                'collation' => 'utf8mb4_general_ci',
                'encoding' => 'utf8mb4',
                'comment' => '所属模块',
                'after' => 'table_comment',
            ])
            ->addColumn('namespace', 'string', [
                'null' => true,
                'limit' => 255,
                'collation' => 'utf8mb4_general_ci',
                'encoding' => 'utf8mb4',
                'comment' => '命名空间',
                'after' => 'module_name',
            ])
            ->addColumn('menu_name', 'string', [
                'null' => true,
                'limit' => 100,
                'collation' => 'utf8mb4_general_ci',
                'encoding' => 'utf8mb4',
                'comment' => '生成菜单名',
                'after' => 'namespace',
            ])
            ->addColumn('belong_menu_id', 'integer', [
                'null' => true,
                'limit' => MysqlAdapter::INT_BIG,
                'comment' => '所属菜单',
                'after' => 'menu_name',
            ])
            ->addColumn('package_name', 'string', [
                'null' => true,
                'limit' => 100,
                'collation' => 'utf8mb4_general_ci',
                'encoding' => 'utf8mb4',
                'comment' => '控制器包名',
                'after' => 'belong_menu_id',
            ])
            ->addColumn('type', 'string', [
                'null' => true,
                'limit' => 100,
                'collation' => 'utf8mb4_general_ci',
                'encoding' => 'utf8mb4',
                'comment' => '生成类型，single 单表CRUD，tree 树表CRUD，parent_sub父子表CRUD',
                'after' => 'package_name',
            ])
            ->addColumn('generate_type', 'char', [
                'null' => true,
                'default' => '0',
                'limit' => 1,
                'collation' => 'utf8mb4_general_ci',
                'encoding' => 'utf8mb4',
                'comment' => '0 压缩包下载 1 生成到模块',
                'after' => 'type',
            ])
            ->addColumn('generate_menus', 'string', [
                'null' => true,
                'limit' => 255,
                'collation' => 'utf8mb4_general_ci',
                'encoding' => 'utf8mb4',
                'comment' => '生成菜单列表',
                'after' => 'generate_type',
            ])
            ->addColumn('build_menu', 'char', [
                'null' => true,
                'default' => '0',
                'limit' => 1,
                'collation' => 'utf8mb4_general_ci',
                'encoding' => 'utf8mb4',
                'comment' => '是否构建菜单',
                'after' => 'generate_menus',
            ])
            ->addColumn('component_type', 'char', [
                'null' => true,
                'default' => '0',
                'limit' => 1,
                'collation' => 'utf8mb4_general_ci',
                'encoding' => 'utf8mb4',
                'comment' => '组件显示方式',
                'after' => 'build_menu',
            ])
            ->addColumn('options', 'string', [
                'null' => true,
                'limit' => 1500,
                'collation' => 'utf8mb4_general_ci',
                'encoding' => 'utf8mb4',
                'comment' => '其他业务选项',
                'after' => 'component_type',
            ])
            ->addColumn('created_by', 'integer', [
                'null' => true,
                'limit' => MysqlAdapter::INT_BIG,
                'comment' => '创建者',
                'after' => 'options',
            ])
            ->addColumn('updated_by', 'integer', [
                'null' => true,
                'limit' => MysqlAdapter::INT_BIG,
                'comment' => '更新者',
                'after' => 'created_by',
            ])
            ->addColumn('created_at', 'timestamp', [
                'null' => true,
                'comment' => '创建时间',
                'after' => 'updated_by',
            ])
            ->addColumn('updated_at', 'timestamp', [
                'null' => true,
                'comment' => '更新时间',
                'after' => 'created_at',
            ])
            ->addColumn('remark', 'string', [
                'null' => true,
                'limit' => 255,
                'collation' => 'utf8mb4_general_ci',
                'encoding' => 'utf8mb4',
                'comment' => '备注',
                'after' => 'updated_at',
            ])
            ->create();
        $this->table('system_dept', [
                'id' => false,
                'primary_key' => ['id'],
                'engine' => 'InnoDB',
                'encoding' => 'utf8mb4',
                'collation' => 'utf8mb4_general_ci',
                'comment' => '部门信息表',
                'row_format' => 'DYNAMIC',
            ])
            ->addColumn('id', 'integer', [
                'null' => false,
                'limit' => MysqlAdapter::INT_BIG,
                'signed' => false,
                'comment' => '主键',
            ])
            ->addColumn('parent_id', 'integer', [
                'null' => false,
                'limit' => MysqlAdapter::INT_BIG,
                'signed' => false,
                'comment' => '父ID',
                'after' => 'id',
            ])
            ->addColumn('level', 'string', [
                'null' => false,
                'limit' => 500,
                'collation' => 'utf8mb4_general_ci',
                'encoding' => 'utf8mb4',
                'comment' => '组级集合',
                'after' => 'parent_id',
            ])
            ->addColumn('name', 'string', [
                'null' => false,
                'limit' => 30,
                'collation' => 'utf8mb4_general_ci',
                'encoding' => 'utf8mb4',
                'comment' => '部门名称',
                'after' => 'level',
            ])
            ->addColumn('leader', 'string', [
                'null' => true,
                'limit' => 20,
                'collation' => 'utf8mb4_general_ci',
                'encoding' => 'utf8mb4',
                'comment' => '负责人',
                'after' => 'name',
            ])
            ->addColumn('phone', 'string', [
                'null' => true,
                'limit' => 11,
                'collation' => 'utf8mb4_general_ci',
                'encoding' => 'utf8mb4',
                'comment' => '联系电话',
                'after' => 'leader',
            ])
            ->addColumn('status', 'char', [
                'null' => true,
                'default' => '0',
                'limit' => 1,
                'collation' => 'utf8mb4_general_ci',
                'encoding' => 'utf8mb4',
                'comment' => '状态 (0正常 1停用)',
                'after' => 'phone',
            ])
            ->addColumn('sort', 'integer', [
                'null' => true,
                'default' => '0',
                'limit' => MysqlAdapter::INT_TINY,
                'comment' => '排序',
                'after' => 'status',
            ])
            ->addColumn('created_by', 'integer', [
                'null' => true,
                'limit' => MysqlAdapter::INT_BIG,
                'comment' => '创建者',
                'after' => 'sort',
            ])
            ->addColumn('updated_by', 'integer', [
                'null' => true,
                'limit' => MysqlAdapter::INT_BIG,
                'comment' => '更新者',
                'after' => 'created_by',
            ])
            ->addColumn('created_at', 'timestamp', [
                'null' => true,
                'comment' => '创建时间',
                'after' => 'updated_by',
            ])
            ->addColumn('updated_at', 'timestamp', [
                'null' => true,
                'comment' => '更新时间',
                'after' => 'created_at',
            ])
            ->addColumn('deleted_at', 'timestamp', [
                'null' => true,
                'comment' => '删除时间',
                'after' => 'updated_at',
            ])
            ->addColumn('remark', 'string', [
                'null' => true,
                'limit' => 255,
                'collation' => 'utf8mb4_general_ci',
                'encoding' => 'utf8mb4',
                'comment' => '备注',
                'after' => 'deleted_at',
            ])
            ->create();
        $this->table('system_dict_data', [
                'id' => false,
                'primary_key' => ['id'],
                'engine' => 'InnoDB',
                'encoding' => 'utf8mb4',
                'collation' => 'utf8mb4_general_ci',
                'comment' => '字典数据表',
                'row_format' => 'DYNAMIC',
            ])
            ->addColumn('id', 'integer', [
                'null' => false,
                'limit' => MysqlAdapter::INT_BIG,
                'signed' => false,
                'comment' => '主键',
            ])
            ->addColumn('type_id', 'integer', [
                'null' => false,
                'limit' => MysqlAdapter::INT_BIG,
                'signed' => false,
                'comment' => '字典类型ID',
                'after' => 'id',
            ])
            ->addColumn('label', 'string', [
                'null' => true,
                'limit' => 50,
                'collation' => 'utf8mb4_general_ci',
                'encoding' => 'utf8mb4',
                'comment' => '字典标签',
                'after' => 'type_id',
            ])
            ->addColumn('value', 'string', [
                'null' => true,
                'limit' => 100,
                'collation' => 'utf8mb4_general_ci',
                'encoding' => 'utf8mb4',
                'comment' => '字典值',
                'after' => 'label',
            ])
            ->addColumn('code', 'string', [
                'null' => true,
                'limit' => 100,
                'collation' => 'utf8mb4_general_ci',
                'encoding' => 'utf8mb4',
                'comment' => '字典标示',
                'after' => 'value',
            ])
            ->addColumn('sort', 'integer', [
                'null' => true,
                'default' => '0',
                'limit' => MysqlAdapter::INT_TINY,
                'comment' => '排序',
                'after' => 'code',
            ])
            ->addColumn('status', 'char', [
                'null' => true,
                'default' => '0',
                'limit' => 1,
                'collation' => 'utf8mb4_general_ci',
                'encoding' => 'utf8mb4',
                'comment' => '状态 (0正常 1停用)',
                'after' => 'sort',
            ])
            ->addColumn('created_by', 'integer', [
                'null' => true,
                'limit' => MysqlAdapter::INT_BIG,
                'comment' => '创建者',
                'after' => 'status',
            ])
            ->addColumn('updated_by', 'integer', [
                'null' => true,
                'limit' => MysqlAdapter::INT_BIG,
                'comment' => '更新者',
                'after' => 'created_by',
            ])
            ->addColumn('created_at', 'timestamp', [
                'null' => true,
                'comment' => '创建时间',
                'after' => 'updated_by',
            ])
            ->addColumn('updated_at', 'timestamp', [
                'null' => true,
                'comment' => '更新时间',
                'after' => 'created_at',
            ])
            ->addColumn('deleted_at', 'timestamp', [
                'null' => true,
                'comment' => '删除时间',
                'after' => 'updated_at',
            ])
            ->addColumn('remark', 'string', [
                'null' => true,
                'limit' => 255,
                'collation' => 'utf8mb4_general_ci',
                'encoding' => 'utf8mb4',
                'comment' => '备注',
                'after' => 'deleted_at',
            ])
            ->addIndex(['type_id'], [
                'name' => 'system_dict_data_type_id_index',
                'unique' => false,
            ])
            ->create();
        $this->table('system_dict_type', [
                'id' => false,
                'primary_key' => ['id'],
                'engine' => 'InnoDB',
                'encoding' => 'utf8mb4',
                'collation' => 'utf8mb4_general_ci',
                'comment' => '字典类型表',
                'row_format' => 'DYNAMIC',
            ])
            ->addColumn('id', 'integer', [
                'null' => false,
                'limit' => MysqlAdapter::INT_BIG,
                'signed' => false,
                'comment' => '主键',
            ])
            ->addColumn('name', 'string', [
                'null' => true,
                'limit' => 50,
                'collation' => 'utf8mb4_general_ci',
                'encoding' => 'utf8mb4',
                'comment' => '字典名称',
                'after' => 'id',
            ])
            ->addColumn('code', 'string', [
                'null' => true,
                'limit' => 100,
                'collation' => 'utf8mb4_general_ci',
                'encoding' => 'utf8mb4',
                'comment' => '字典标示',
                'after' => 'name',
            ])
            ->addColumn('status', 'char', [
                'null' => true,
                'default' => '0',
                'limit' => 1,
                'collation' => 'utf8mb4_general_ci',
                'encoding' => 'utf8mb4',
                'comment' => '状态 (0正常 1停用)',
                'after' => 'code',
            ])
            ->addColumn('created_by', 'integer', [
                'null' => true,
                'limit' => MysqlAdapter::INT_BIG,
                'comment' => '创建者',
                'after' => 'status',
            ])
            ->addColumn('updated_by', 'integer', [
                'null' => true,
                'limit' => MysqlAdapter::INT_BIG,
                'comment' => '更新者',
                'after' => 'created_by',
            ])
            ->addColumn('created_at', 'timestamp', [
                'null' => true,
                'comment' => '创建时间',
                'after' => 'updated_by',
            ])
            ->addColumn('updated_at', 'timestamp', [
                'null' => true,
                'comment' => '更新时间',
                'after' => 'created_at',
            ])
            ->addColumn('deleted_at', 'timestamp', [
                'null' => true,
                'comment' => '删除时间',
                'after' => 'updated_at',
            ])
            ->addColumn('remark', 'string', [
                'null' => true,
                'limit' => 255,
                'collation' => 'utf8mb4_general_ci',
                'encoding' => 'utf8mb4',
                'comment' => '备注',
                'after' => 'deleted_at',
            ])
            ->create();
        $this->table('system_distribution', [
                'id' => false,
                'primary_key' => ['id'],
                'engine' => 'InnoDB',
                'encoding' => 'utf8mb4',
                'collation' => 'utf8mb4_general_ci',
                'comment' => '',
                'row_format' => 'DYNAMIC',
            ])
            ->addColumn('id', 'integer', [
                'null' => false,
                'limit' => MysqlAdapter::INT_BIG,
                'comment' => '分销Id',
            ])
            ->addColumn('user_id', 'integer', [
                'null' => true,
                'limit' => MysqlAdapter::INT_BIG,
                'comment' => '用户Id',
                'after' => 'id',
            ])
            ->addColumn('parent_id', 'integer', [
                'null' => true,
                'limit' => MysqlAdapter::INT_BIG,
                'comment' => '推荐人Id',
                'after' => 'user_id',
            ])
            ->addColumn('level', 'integer', [
                'null' => true,
                'limit' => MysqlAdapter::INT_REGULAR,
                'comment' => '层级',
                'after' => 'parent_id',
            ])
            ->addColumn('business', 'string', [
                'null' => true,
                'limit' => 20,
                'collation' => 'utf8mb4_general_ci',
                'encoding' => 'utf8mb4',
                'comment' => '业务类型',
                'after' => 'level',
            ])
            ->create();
        $this->table('system_login_log', [
                'id' => false,
                'primary_key' => ['id'],
                'engine' => 'InnoDB',
                'encoding' => 'utf8mb4',
                'collation' => 'utf8mb4_general_ci',
                'comment' => '登录日志表',
                'row_format' => 'DYNAMIC',
            ])
            ->addColumn('id', 'integer', [
                'null' => false,
                'limit' => MysqlAdapter::INT_BIG,
                'signed' => false,
                'comment' => '主键',
            ])
            ->addColumn('username', 'string', [
                'null' => false,
                'limit' => 20,
                'collation' => 'utf8mb4_general_ci',
                'encoding' => 'utf8mb4',
                'comment' => '用户名',
                'after' => 'id',
            ])
            ->addColumn('ip', 'string', [
                'null' => true,
                'limit' => 45,
                'collation' => 'utf8mb4_general_ci',
                'encoding' => 'utf8mb4',
                'comment' => '登录IP地址',
                'after' => 'username',
            ])
            ->addColumn('ip_location', 'string', [
                'null' => true,
                'limit' => 255,
                'collation' => 'utf8mb4_general_ci',
                'encoding' => 'utf8mb4',
                'comment' => 'IP所属地',
                'after' => 'ip',
            ])
            ->addColumn('os', 'string', [
                'null' => true,
                'limit' => 50,
                'collation' => 'utf8mb4_general_ci',
                'encoding' => 'utf8mb4',
                'comment' => '操作系统',
                'after' => 'ip_location',
            ])
            ->addColumn('browser', 'string', [
                'null' => true,
                'limit' => 50,
                'collation' => 'utf8mb4_general_ci',
                'encoding' => 'utf8mb4',
                'comment' => '浏览器',
                'after' => 'os',
            ])
            ->addColumn('status', 'char', [
                'null' => false,
                'default' => '0',
                'limit' => 1,
                'collation' => 'utf8mb4_general_ci',
                'encoding' => 'utf8mb4',
                'comment' => '登录状态 (0成功 1失败)',
                'after' => 'browser',
            ])
            ->addColumn('message', 'string', [
                'null' => true,
                'limit' => 50,
                'collation' => 'utf8mb4_general_ci',
                'encoding' => 'utf8mb4',
                'comment' => '提示消息',
                'after' => 'status',
            ])
            ->addColumn('login_time', 'timestamp', [
                'null' => false,
                'comment' => '登录时间',
                'after' => 'message',
            ])
            ->addColumn('remark', 'string', [
                'null' => true,
                'limit' => 255,
                'collation' => 'utf8mb4_general_ci',
                'encoding' => 'utf8mb4',
                'comment' => '备注',
                'after' => 'login_time',
            ])
            ->create();
        $this->table('system_menu', [
                'id' => false,
                'primary_key' => ['id'],
                'engine' => 'InnoDB',
                'encoding' => 'utf8mb4',
                'collation' => 'utf8mb4_general_ci',
                'comment' => '菜单信息表',
                'row_format' => 'DYNAMIC',
            ])
            ->addColumn('id', 'integer', [
                'null' => false,
                'limit' => MysqlAdapter::INT_BIG,
                'signed' => false,
                'comment' => '主键',
            ])
            ->addColumn('parent_id', 'integer', [
                'null' => false,
                'limit' => MysqlAdapter::INT_BIG,
                'signed' => false,
                'comment' => '父ID',
                'after' => 'id',
            ])
            ->addColumn('level', 'string', [
                'null' => false,
                'limit' => 500,
                'collation' => 'utf8mb4_general_ci',
                'encoding' => 'utf8mb4',
                'comment' => '组级集合',
                'after' => 'parent_id',
            ])
            ->addColumn('name', 'string', [
                'null' => false,
                'limit' => 50,
                'collation' => 'utf8mb4_general_ci',
                'encoding' => 'utf8mb4',
                'comment' => '菜单名称',
                'after' => 'level',
            ])
            ->addColumn('code', 'string', [
                'null' => false,
                'limit' => 100,
                'collation' => 'utf8mb4_general_ci',
                'encoding' => 'utf8mb4',
                'comment' => '菜单标识代码',
                'after' => 'name',
            ])
            ->addColumn('icon', 'string', [
                'null' => true,
                'limit' => 50,
                'collation' => 'utf8mb4_general_ci',
                'encoding' => 'utf8mb4',
                'comment' => '菜单图标',
                'after' => 'code',
            ])
            ->addColumn('route', 'string', [
                'null' => true,
                'limit' => 200,
                'collation' => 'utf8mb4_general_ci',
                'encoding' => 'utf8mb4',
                'comment' => '路由地址',
                'after' => 'icon',
            ])
            ->addColumn('component', 'string', [
                'null' => true,
                'limit' => 255,
                'collation' => 'utf8mb4_general_ci',
                'encoding' => 'utf8mb4',
                'comment' => '组件路径',
                'after' => 'route',
            ])
            ->addColumn('redirect', 'string', [
                'null' => true,
                'limit' => 255,
                'collation' => 'utf8mb4_general_ci',
                'encoding' => 'utf8mb4',
                'comment' => '跳转地址',
                'after' => 'component',
            ])
            ->addColumn('is_hidden', 'char', [
                'null' => false,
                'default' => '1',
                'limit' => 1,
                'collation' => 'utf8mb4_general_ci',
                'encoding' => 'utf8mb4',
                'comment' => '是否隐藏 (0是 1否)',
                'after' => 'redirect',
            ])
            ->addColumn('type', 'char', [
                'null' => false,
                'default' => '',
                'limit' => 1,
                'collation' => 'utf8mb4_general_ci',
                'encoding' => 'utf8mb4',
                'comment' => '菜单类型, (M菜单 B按钮 L链接 I iframe)',
                'after' => 'is_hidden',
            ])
            ->addColumn('status', 'char', [
                'null' => true,
                'default' => '0',
                'limit' => 1,
                'collation' => 'utf8mb4_general_ci',
                'encoding' => 'utf8mb4',
                'comment' => '状态 (0正常 1停用)',
                'after' => 'type',
            ])
            ->addColumn('sort', 'integer', [
                'null' => true,
                'default' => '0',
                'limit' => MysqlAdapter::INT_TINY,
                'comment' => '排序',
                'after' => 'status',
            ])
            ->addColumn('created_by', 'integer', [
                'null' => true,
                'limit' => MysqlAdapter::INT_BIG,
                'comment' => '创建者',
                'after' => 'sort',
            ])
            ->addColumn('updated_by', 'integer', [
                'null' => true,
                'limit' => MysqlAdapter::INT_BIG,
                'comment' => '更新者',
                'after' => 'created_by',
            ])
            ->addColumn('created_at', 'timestamp', [
                'null' => true,
                'comment' => '创建时间',
                'after' => 'updated_by',
            ])
            ->addColumn('updated_at', 'timestamp', [
                'null' => true,
                'comment' => '更新时间',
                'after' => 'created_at',
            ])
            ->addColumn('deleted_at', 'timestamp', [
                'null' => true,
                'comment' => '删除时间',
                'after' => 'updated_at',
            ])
            ->addColumn('remark', 'string', [
                'null' => true,
                'limit' => 255,
                'collation' => 'utf8mb4_general_ci',
                'encoding' => 'utf8mb4',
                'comment' => '备注',
                'after' => 'deleted_at',
            ])
            ->create();
        $this->table('system_notice', [
                'id' => false,
                'primary_key' => ['id'],
                'engine' => 'InnoDB',
                'encoding' => 'utf8mb4',
                'collation' => 'utf8mb4_general_ci',
                'comment' => '系统公告表',
                'row_format' => 'DYNAMIC',
            ])
            ->addColumn('id', 'integer', [
                'null' => false,
                'limit' => MysqlAdapter::INT_BIG,
                'signed' => false,
                'comment' => '主键',
            ])
            ->addColumn('message_id', 'integer', [
                'null' => true,
                'limit' => MysqlAdapter::INT_BIG,
                'comment' => '消息ID',
                'after' => 'id',
            ])
            ->addColumn('title', 'string', [
                'null' => false,
                'limit' => 255,
                'collation' => 'utf8mb4_general_ci',
                'encoding' => 'utf8mb4',
                'comment' => '标题',
                'after' => 'message_id',
            ])
            ->addColumn('type', 'char', [
                'null' => false,
                'limit' => 1,
                'collation' => 'utf8mb4_general_ci',
                'encoding' => 'utf8mb4',
                'comment' => '公告类型（1通知 2公告）',
                'after' => 'title',
            ])
            ->addColumn('content', 'text', [
                'null' => true,
                'limit' => 65535,
                'collation' => 'utf8mb4_general_ci',
                'encoding' => 'utf8mb4',
                'comment' => '公告内容',
                'after' => 'type',
            ])
            ->addColumn('click_num', 'integer', [
                'null' => true,
                'default' => '0',
                'limit' => MysqlAdapter::INT_REGULAR,
                'comment' => '浏览次数',
                'after' => 'content',
            ])
            ->addColumn('created_by', 'integer', [
                'null' => true,
                'limit' => MysqlAdapter::INT_BIG,
                'comment' => '创建者',
                'after' => 'click_num',
            ])
            ->addColumn('updated_by', 'integer', [
                'null' => true,
                'limit' => MysqlAdapter::INT_BIG,
                'comment' => '更新者',
                'after' => 'created_by',
            ])
            ->addColumn('created_at', 'timestamp', [
                'null' => true,
                'comment' => '创建时间',
                'after' => 'updated_by',
            ])
            ->addColumn('updated_at', 'timestamp', [
                'null' => true,
                'comment' => '更新时间',
                'after' => 'created_at',
            ])
            ->addColumn('deleted_at', 'timestamp', [
                'null' => true,
                'comment' => '删除时间',
                'after' => 'updated_at',
            ])
            ->addColumn('remark', 'string', [
                'null' => true,
                'limit' => 255,
                'collation' => 'utf8mb4_general_ci',
                'encoding' => 'utf8mb4',
                'comment' => '备注',
                'after' => 'deleted_at',
            ])
            ->create();
        $this->table('system_oper_log', [
                'id' => false,
                'primary_key' => ['id'],
                'engine' => 'InnoDB',
                'encoding' => 'utf8mb4',
                'collation' => 'utf8mb4_general_ci',
                'comment' => '操作日志表',
                'row_format' => 'DYNAMIC',
            ])
            ->addColumn('id', 'integer', [
                'null' => false,
                'limit' => MysqlAdapter::INT_BIG,
                'signed' => false,
                'comment' => '主键',
            ])
            ->addColumn('username', 'string', [
                'null' => false,
                'limit' => 20,
                'collation' => 'utf8mb4_general_ci',
                'encoding' => 'utf8mb4',
                'comment' => '用户名',
                'after' => 'id',
            ])
            ->addColumn('method', 'string', [
                'null' => false,
                'limit' => 20,
                'collation' => 'utf8mb4_general_ci',
                'encoding' => 'utf8mb4',
                'comment' => '请求方式',
                'after' => 'username',
            ])
            ->addColumn('router', 'string', [
                'null' => false,
                'limit' => 500,
                'collation' => 'utf8mb4_general_ci',
                'encoding' => 'utf8mb4',
                'comment' => '请求路由',
                'after' => 'method',
            ])
            ->addColumn('service_name', 'string', [
                'null' => false,
                'limit' => 30,
                'collation' => 'utf8mb4_general_ci',
                'encoding' => 'utf8mb4',
                'comment' => '业务名称',
                'after' => 'router',
            ])
            ->addColumn('ip', 'string', [
                'null' => true,
                'limit' => 45,
                'collation' => 'utf8mb4_general_ci',
                'encoding' => 'utf8mb4',
                'comment' => '请求IP地址',
                'after' => 'service_name',
            ])
            ->addColumn('ip_location', 'string', [
                'null' => true,
                'limit' => 255,
                'collation' => 'utf8mb4_general_ci',
                'encoding' => 'utf8mb4',
                'comment' => 'IP所属地',
                'after' => 'ip',
            ])
            ->addColumn('request_data', 'text', [
                'null' => true,
                'limit' => 65535,
                'collation' => 'utf8mb4_general_ci',
                'encoding' => 'utf8mb4',
                'comment' => '请求数据',
                'after' => 'ip_location',
            ])
            ->addColumn('response_code', 'string', [
                'null' => true,
                'limit' => 5,
                'collation' => 'utf8mb4_general_ci',
                'encoding' => 'utf8mb4',
                'comment' => '响应状态码',
                'after' => 'request_data',
            ])
            ->addColumn('response_data', 'text', [
                'null' => true,
                'limit' => 65535,
                'collation' => 'utf8mb4_general_ci',
                'encoding' => 'utf8mb4',
                'comment' => '响应数据',
                'after' => 'response_code',
            ])
            ->addColumn('created_by', 'integer', [
                'null' => true,
                'limit' => MysqlAdapter::INT_BIG,
                'comment' => '创建者',
                'after' => 'response_data',
            ])
            ->addColumn('updated_by', 'integer', [
                'null' => true,
                'limit' => MysqlAdapter::INT_BIG,
                'comment' => '更新者',
                'after' => 'created_by',
            ])
            ->addColumn('created_at', 'timestamp', [
                'null' => true,
                'comment' => '创建时间',
                'after' => 'updated_by',
            ])
            ->addColumn('updated_at', 'timestamp', [
                'null' => true,
                'comment' => '更新时间',
                'after' => 'created_at',
            ])
            ->addColumn('deleted_at', 'timestamp', [
                'null' => true,
                'comment' => '删除时间',
                'after' => 'updated_at',
            ])
            ->addColumn('remark', 'string', [
                'null' => true,
                'limit' => 255,
                'collation' => 'utf8mb4_general_ci',
                'encoding' => 'utf8mb4',
                'comment' => '备注',
                'after' => 'deleted_at',
            ])
            ->create();
        $this->table('system_post', [
                'id' => false,
                'primary_key' => ['id'],
                'engine' => 'InnoDB',
                'encoding' => 'utf8mb4',
                'collation' => 'utf8mb4_general_ci',
                'comment' => '岗位信息表',
                'row_format' => 'DYNAMIC',
            ])
            ->addColumn('id', 'integer', [
                'null' => false,
                'limit' => MysqlAdapter::INT_BIG,
                'signed' => false,
                'comment' => '主键',
            ])
            ->addColumn('name', 'string', [
                'null' => false,
                'limit' => 50,
                'collation' => 'utf8mb4_general_ci',
                'encoding' => 'utf8mb4',
                'comment' => '岗位名称',
                'after' => 'id',
            ])
            ->addColumn('code', 'string', [
                'null' => false,
                'limit' => 100,
                'collation' => 'utf8mb4_general_ci',
                'encoding' => 'utf8mb4',
                'comment' => '岗位代码',
                'after' => 'name',
            ])
            ->addColumn('sort', 'integer', [
                'null' => true,
                'default' => '0',
                'limit' => MysqlAdapter::INT_TINY,
                'comment' => '排序',
                'after' => 'code',
            ])
            ->addColumn('status', 'char', [
                'null' => true,
                'default' => '0',
                'limit' => 1,
                'collation' => 'utf8mb4_general_ci',
                'encoding' => 'utf8mb4',
                'comment' => '状态 (0正常 1停用)',
                'after' => 'sort',
            ])
            ->addColumn('created_by', 'integer', [
                'null' => true,
                'limit' => MysqlAdapter::INT_BIG,
                'comment' => '创建者',
                'after' => 'status',
            ])
            ->addColumn('updated_by', 'integer', [
                'null' => true,
                'limit' => MysqlAdapter::INT_BIG,
                'comment' => '更新者',
                'after' => 'created_by',
            ])
            ->addColumn('created_at', 'timestamp', [
                'null' => true,
                'comment' => '创建时间',
                'after' => 'updated_by',
            ])
            ->addColumn('updated_at', 'timestamp', [
                'null' => true,
                'comment' => '更新时间',
                'after' => 'created_at',
            ])
            ->addColumn('deleted_at', 'timestamp', [
                'null' => true,
                'comment' => '删除时间',
                'after' => 'updated_at',
            ])
            ->addColumn('remark', 'string', [
                'null' => true,
                'limit' => 255,
                'collation' => 'utf8mb4_general_ci',
                'encoding' => 'utf8mb4',
                'comment' => '备注',
                'after' => 'deleted_at',
            ])
            ->create();
        $this->table('system_queue_log', [
                'id' => false,
                'primary_key' => ['id'],
                'engine' => 'InnoDB',
                'encoding' => 'utf8mb4',
                'collation' => 'utf8mb4_general_ci',
                'comment' => '队列日志表',
                'row_format' => 'DYNAMIC',
            ])
            ->addColumn('id', 'integer', [
                'null' => false,
                'limit' => MysqlAdapter::INT_BIG,
                'signed' => false,
                'comment' => '主键',
            ])
            ->addColumn('exchange_name', 'string', [
                'null' => false,
                'limit' => 32,
                'collation' => 'utf8mb4_general_ci',
                'encoding' => 'utf8mb4',
                'comment' => '交换机名称',
                'after' => 'id',
            ])
            ->addColumn('routing_key_name', 'string', [
                'null' => false,
                'limit' => 32,
                'collation' => 'utf8mb4_general_ci',
                'encoding' => 'utf8mb4',
                'comment' => '路由名称',
                'after' => 'exchange_name',
            ])
            ->addColumn('queue_name', 'string', [
                'null' => false,
                'limit' => 64,
                'collation' => 'utf8mb4_general_ci',
                'encoding' => 'utf8mb4',
                'comment' => '队列名称',
                'after' => 'routing_key_name',
            ])
            ->addColumn('queue_content', 'text', [
                'null' => true,
                'limit' => MysqlAdapter::TEXT_LONG,
                'collation' => 'utf8mb4_general_ci',
                'encoding' => 'utf8mb4',
                'comment' => '队列数据',
                'after' => 'queue_name',
            ])
            ->addColumn('log_content', 'text', [
                'null' => true,
                'limit' => 65535,
                'collation' => 'utf8mb4_general_ci',
                'encoding' => 'utf8mb4',
                'comment' => '队列日志',
                'after' => 'queue_content',
            ])
            ->addColumn('produce_status', 'char', [
                'null' => true,
                'default' => '0',
                'limit' => 1,
                'collation' => 'utf8mb4_general_ci',
                'encoding' => 'utf8mb4',
                'comment' => '生产状态 0:未生产 1:生产中 2:生产成功 3:生产失败 4:生产重复',
                'after' => 'log_content',
            ])
            ->addColumn('consume_status', 'char', [
                'null' => true,
                'default' => '0',
                'limit' => 1,
                'collation' => 'utf8mb4_general_ci',
                'encoding' => 'utf8mb4',
                'comment' => '消费状态 0:未消费 1:消费中 2:消费成功 3:消费失败 4:消费重复',
                'after' => 'produce_status',
            ])
            ->addColumn('delay_time', 'integer', [
                'null' => false,
                'limit' => MysqlAdapter::INT_REGULAR,
                'signed' => false,
                'comment' => '延迟时间（秒）',
                'after' => 'consume_status',
            ])
            ->addColumn('created_by', 'integer', [
                'null' => true,
                'limit' => MysqlAdapter::INT_BIG,
                'comment' => '创建者',
                'after' => 'delay_time',
            ])
            ->addColumn('updated_by', 'integer', [
                'null' => true,
                'limit' => MysqlAdapter::INT_BIG,
                'comment' => '更新者',
                'after' => 'created_by',
            ])
            ->addColumn('created_at', 'timestamp', [
                'null' => true,
                'comment' => '创建时间',
                'after' => 'updated_by',
            ])
            ->addColumn('updated_at', 'timestamp', [
                'null' => true,
                'comment' => '更新时间',
                'after' => 'created_at',
            ])
            ->create();
        $this->table('system_queue_message', [
                'id' => false,
                'primary_key' => ['id'],
                'engine' => 'InnoDB',
                'encoding' => 'utf8mb4',
                'collation' => 'utf8mb4_general_ci',
                'comment' => '队列消息表',
                'row_format' => 'DYNAMIC',
            ])
            ->addColumn('id', 'integer', [
                'null' => false,
                'limit' => MysqlAdapter::INT_BIG,
                'signed' => false,
                'comment' => '主键',
            ])
            ->addColumn('content_type', 'string', [
                'null' => true,
                'limit' => 64,
                'collation' => 'utf8mb4_general_ci',
                'encoding' => 'utf8mb4',
                'comment' => '内容类型',
                'after' => 'id',
            ])
            ->addColumn('title', 'string', [
                'null' => true,
                'limit' => 255,
                'collation' => 'utf8mb4_general_ci',
                'encoding' => 'utf8mb4',
                'comment' => '消息标题',
                'after' => 'content_type',
            ])
            ->addColumn('content', 'text', [
                'null' => true,
                'limit' => MysqlAdapter::TEXT_LONG,
                'collation' => 'utf8mb4_general_ci',
                'encoding' => 'utf8mb4',
                'comment' => '消息内容',
                'after' => 'title',
            ])
            ->addColumn('send_by', 'integer', [
                'null' => false,
                'limit' => MysqlAdapter::INT_BIG,
                'signed' => false,
                'comment' => '发送人',
                'after' => 'content',
            ])
            ->addColumn('created_by', 'integer', [
                'null' => true,
                'limit' => MysqlAdapter::INT_BIG,
                'comment' => '创建者',
                'after' => 'send_by',
            ])
            ->addColumn('updated_by', 'integer', [
                'null' => true,
                'limit' => MysqlAdapter::INT_BIG,
                'comment' => '更新者',
                'after' => 'created_by',
            ])
            ->addColumn('created_at', 'timestamp', [
                'null' => true,
                'comment' => '创建时间',
                'after' => 'updated_by',
            ])
            ->addColumn('updated_at', 'timestamp', [
                'null' => true,
                'comment' => '更新时间',
                'after' => 'created_at',
            ])
            ->addColumn('remark', 'string', [
                'null' => true,
                'limit' => 255,
                'collation' => 'utf8mb4_general_ci',
                'encoding' => 'utf8mb4',
                'comment' => '备注',
                'after' => 'updated_at',
            ])
            ->addIndex(['content_type'], [
                'name' => 'system_queue_message_content_type_index',
                'unique' => false,
            ])
            ->addIndex(['send_by'], [
                'name' => 'system_queue_message_send_by_index',
                'unique' => false,
            ])
            ->create();
        $this->table('system_queue_message_receive', [
                'id' => false,
                'primary_key' => ['message_id', 'user_id'],
                'engine' => 'InnoDB',
                'encoding' => 'utf8mb4',
                'collation' => 'utf8mb4_general_ci',
                'comment' => '队列消息接收人表',
                'row_format' => 'DYNAMIC',
            ])
            ->addColumn('message_id', 'integer', [
                'null' => false,
                'limit' => MysqlAdapter::INT_BIG,
                'signed' => false,
                'comment' => '队列消息主键',
            ])
            ->addColumn('user_id', 'integer', [
                'null' => false,
                'limit' => MysqlAdapter::INT_BIG,
                'signed' => false,
                'comment' => '接收用户主键',
                'after' => 'message_id',
            ])
            ->addColumn('read_status', 'char', [
                'null' => true,
                'default' => '0',
                'limit' => 1,
                'collation' => 'utf8mb4_general_ci',
                'encoding' => 'utf8mb4',
                'comment' => '已读状态 (0未读 1已读)',
                'after' => 'user_id',
            ])
            ->create();
        $this->table('system_role', [
                'id' => false,
                'primary_key' => ['id'],
                'engine' => 'InnoDB',
                'encoding' => 'utf8mb4',
                'collation' => 'utf8mb4_general_ci',
                'comment' => '角色信息表',
                'row_format' => 'DYNAMIC',
            ])
            ->addColumn('id', 'integer', [
                'null' => false,
                'limit' => MysqlAdapter::INT_BIG,
                'signed' => false,
                'comment' => '主键，角色ID',
            ])
            ->addColumn('name', 'string', [
                'null' => false,
                'limit' => 30,
                'collation' => 'utf8mb4_general_ci',
                'encoding' => 'utf8mb4',
                'comment' => '角色名称',
                'after' => 'id',
            ])
            ->addColumn('code', 'string', [
                'null' => false,
                'limit' => 100,
                'collation' => 'utf8mb4_general_ci',
                'encoding' => 'utf8mb4',
                'comment' => '角色代码',
                'after' => 'name',
            ])
            ->addColumn('data_scope', 'char', [
                'null' => true,
                'default' => '0',
                'limit' => 1,
                'collation' => 'utf8mb4_general_ci',
                'encoding' => 'utf8mb4',
                'comment' => '数据范围（0：全部数据权限 1：自定义数据权限 2：本部门数据权限 3：本部门及以下数据权限 4：本人数据权限）',
                'after' => 'code',
            ])
            ->addColumn('status', 'char', [
                'null' => true,
                'default' => '0',
                'limit' => 1,
                'collation' => 'utf8mb4_general_ci',
                'encoding' => 'utf8mb4',
                'comment' => '状态 (0正常 1停用)',
                'after' => 'data_scope',
            ])
            ->addColumn('sort', 'integer', [
                'null' => true,
                'default' => '0',
                'limit' => MysqlAdapter::INT_TINY,
                'comment' => '排序',
                'after' => 'status',
            ])
            ->addColumn('created_by', 'integer', [
                'null' => true,
                'limit' => MysqlAdapter::INT_BIG,
                'comment' => '创建者',
                'after' => 'sort',
            ])
            ->addColumn('updated_by', 'integer', [
                'null' => true,
                'limit' => MysqlAdapter::INT_BIG,
                'comment' => '更新者',
                'after' => 'created_by',
            ])
            ->addColumn('created_at', 'timestamp', [
                'null' => true,
                'comment' => '创建时间',
                'after' => 'updated_by',
            ])
            ->addColumn('updated_at', 'timestamp', [
                'null' => true,
                'comment' => '更新时间',
                'after' => 'created_at',
            ])
            ->addColumn('deleted_at', 'timestamp', [
                'null' => true,
                'comment' => '删除时间',
                'after' => 'updated_at',
            ])
            ->addColumn('remark', 'string', [
                'null' => true,
                'limit' => 255,
                'collation' => 'utf8mb4_general_ci',
                'encoding' => 'utf8mb4',
                'comment' => '备注',
                'after' => 'deleted_at',
            ])
            ->create();
        $this->table('system_role_dept', [
                'id' => false,
                'primary_key' => ['role_id', 'dept_id'],
                'engine' => 'InnoDB',
                'encoding' => 'utf8mb4',
                'collation' => 'utf8mb4_general_ci',
                'comment' => '角色与部门关联表',
                'row_format' => 'DYNAMIC',
            ])
            ->addColumn('role_id', 'integer', [
                'null' => false,
                'limit' => MysqlAdapter::INT_BIG,
                'signed' => false,
                'comment' => '角色主键',
            ])
            ->addColumn('dept_id', 'integer', [
                'null' => false,
                'limit' => MysqlAdapter::INT_BIG,
                'signed' => false,
                'comment' => '部门主键',
                'after' => 'role_id',
            ])
            ->create();
        $this->table('system_role_menu', [
                'id' => false,
                'primary_key' => ['role_id', 'menu_id'],
                'engine' => 'InnoDB',
                'encoding' => 'utf8mb4',
                'collation' => 'utf8mb4_general_ci',
                'comment' => '角色与菜单关联表',
                'row_format' => 'DYNAMIC',
            ])
            ->addColumn('role_id', 'integer', [
                'null' => false,
                'limit' => MysqlAdapter::INT_BIG,
                'signed' => false,
                'comment' => '角色主键',
            ])
            ->addColumn('menu_id', 'integer', [
                'null' => false,
                'limit' => MysqlAdapter::INT_BIG,
                'signed' => false,
                'comment' => '菜单主键',
                'after' => 'role_id',
            ])
            ->create();
        $this->table('system_uploadfile', [
                'id' => false,
                'primary_key' => ['id'],
                'engine' => 'InnoDB',
                'encoding' => 'utf8mb4',
                'collation' => 'utf8mb4_general_ci',
                'comment' => '上传文件信息表',
                'row_format' => 'DYNAMIC',
            ])
            ->addColumn('id', 'integer', [
                'null' => false,
                'limit' => MysqlAdapter::INT_BIG,
                'signed' => false,
                'comment' => '主键',
            ])
            ->addColumn('storage_mode', 'char', [
                'null' => true,
                'default' => '1',
                'limit' => 1,
                'collation' => 'utf8mb4_general_ci',
                'encoding' => 'utf8mb4',
                'comment' => '状态 (1 本地 2 阿里云 3 七牛云 4 腾讯云)',
                'after' => 'id',
            ])
            ->addColumn('origin_name', 'string', [
                'null' => true,
                'limit' => 255,
                'collation' => 'utf8mb4_general_ci',
                'encoding' => 'utf8mb4',
                'comment' => '原文件名',
                'after' => 'storage_mode',
            ])
            ->addColumn('object_name', 'string', [
                'null' => true,
                'limit' => 50,
                'collation' => 'utf8mb4_general_ci',
                'encoding' => 'utf8mb4',
                'comment' => '新文件名',
                'after' => 'origin_name',
            ])
            ->addColumn('mime_type', 'string', [
                'null' => true,
                'limit' => 255,
                'collation' => 'utf8mb4_general_ci',
                'encoding' => 'utf8mb4',
                'comment' => '资源类型',
                'after' => 'object_name',
            ])
            ->addColumn('storage_path', 'string', [
                'null' => true,
                'limit' => 100,
                'collation' => 'utf8mb4_general_ci',
                'encoding' => 'utf8mb4',
                'comment' => '存储目录',
                'after' => 'mime_type',
            ])
            ->addColumn('suffix', 'string', [
                'null' => true,
                'limit' => 10,
                'collation' => 'utf8mb4_general_ci',
                'encoding' => 'utf8mb4',
                'comment' => '文件后缀',
                'after' => 'storage_path',
            ])
            ->addColumn('size_byte', 'integer', [
                'null' => true,
                'limit' => MysqlAdapter::INT_BIG,
                'comment' => '字节数',
                'after' => 'suffix',
            ])
            ->addColumn('size_info', 'string', [
                'null' => true,
                'limit' => 50,
                'collation' => 'utf8mb4_general_ci',
                'encoding' => 'utf8mb4',
                'comment' => '文件大小',
                'after' => 'size_byte',
            ])
            ->addColumn('url', 'string', [
                'null' => true,
                'limit' => 255,
                'collation' => 'utf8mb4_general_ci',
                'encoding' => 'utf8mb4',
                'comment' => 'url地址',
                'after' => 'size_info',
            ])
            ->addColumn('created_by', 'integer', [
                'null' => true,
                'limit' => MysqlAdapter::INT_BIG,
                'comment' => '创建者',
                'after' => 'url',
            ])
            ->addColumn('updated_by', 'integer', [
                'null' => true,
                'limit' => MysqlAdapter::INT_BIG,
                'comment' => '更新者',
                'after' => 'created_by',
            ])
            ->addColumn('created_at', 'timestamp', [
                'null' => true,
                'comment' => '创建时间',
                'after' => 'updated_by',
            ])
            ->addColumn('updated_at', 'timestamp', [
                'null' => true,
                'comment' => '更新时间',
                'after' => 'created_at',
            ])
            ->addColumn('deleted_at', 'timestamp', [
                'null' => true,
                'comment' => '删除时间',
                'after' => 'updated_at',
            ])
            ->addColumn('remark', 'string', [
                'null' => true,
                'limit' => 255,
                'collation' => 'utf8mb4_general_ci',
                'encoding' => 'utf8mb4',
                'comment' => '备注',
                'after' => 'deleted_at',
            ])
            ->addIndex(['storage_path'], [
                'name' => 'system_uploadfile_storage_path_index',
                'unique' => false,
            ])
            ->create();
        $this->table('system_user', [
                'id' => false,
                'primary_key' => ['id'],
                'engine' => 'InnoDB',
                'encoding' => 'utf8mb4',
                'collation' => 'utf8mb4_general_ci',
                'comment' => '用户信息表',
                'row_format' => 'DYNAMIC',
            ])
            ->addColumn('id', 'integer', [
                'null' => false,
                'limit' => MysqlAdapter::INT_BIG,
                'signed' => false,
                'comment' => '用户ID，主键',
            ])
            ->addColumn('username', 'string', [
                'null' => false,
                'limit' => 20,
                'collation' => 'utf8mb4_general_ci',
                'encoding' => 'utf8mb4',
                'comment' => '用户名',
                'after' => 'id',
            ])
            ->addColumn('password', 'string', [
                'null' => false,
                'limit' => 100,
                'collation' => 'utf8mb4_general_ci',
                'encoding' => 'utf8mb4',
                'comment' => '密码',
                'after' => 'username',
            ])
            ->addColumn('user_type', 'string', [
                'null' => true,
                'default' => '100',
                'limit' => 3,
                'collation' => 'utf8mb4_general_ci',
                'encoding' => 'utf8mb4',
                'comment' => '用户类型：(100系统用户)',
                'after' => 'password',
            ])
            ->addColumn('nickname', 'string', [
                'null' => true,
                'limit' => 30,
                'collation' => 'utf8mb4_general_ci',
                'encoding' => 'utf8mb4',
                'comment' => '用户昵称',
                'after' => 'user_type',
            ])
            ->addColumn('phone', 'string', [
                'null' => true,
                'limit' => 11,
                'collation' => 'utf8mb4_general_ci',
                'encoding' => 'utf8mb4',
                'comment' => '手机',
                'after' => 'nickname',
            ])
            ->addColumn('email', 'string', [
                'null' => true,
                'limit' => 50,
                'collation' => 'utf8mb4_general_ci',
                'encoding' => 'utf8mb4',
                'comment' => '用户邮箱',
                'after' => 'phone',
            ])
            ->addColumn('avatar', 'string', [
                'null' => true,
                'limit' => 255,
                'collation' => 'utf8mb4_general_ci',
                'encoding' => 'utf8mb4',
                'comment' => '用户头像',
                'after' => 'email',
            ])
            ->addColumn('signed', 'string', [
                'null' => true,
                'limit' => 255,
                'collation' => 'utf8mb4_general_ci',
                'encoding' => 'utf8mb4',
                'comment' => '个人签名',
                'after' => 'avatar',
            ])
            ->addColumn('dashboard', 'string', [
                'null' => true,
                'limit' => 100,
                'collation' => 'utf8mb4_general_ci',
                'encoding' => 'utf8mb4',
                'comment' => '后台首页类型',
                'after' => 'signed',
            ])
            ->addColumn('dept_id', 'integer', [
                'null' => true,
                'limit' => MysqlAdapter::INT_BIG,
                'signed' => false,
                'comment' => '部门ID',
                'after' => 'dashboard',
            ])
            ->addColumn('status', 'char', [
                'null' => true,
                'default' => '0',
                'limit' => 1,
                'collation' => 'utf8mb4_general_ci',
                'encoding' => 'utf8mb4',
                'comment' => '状态 (0正常 1停用)',
                'after' => 'dept_id',
            ])
            ->addColumn('login_ip', 'string', [
                'null' => true,
                'limit' => 45,
                'collation' => 'utf8mb4_general_ci',
                'encoding' => 'utf8mb4',
                'comment' => '最后登陆IP',
                'after' => 'status',
            ])
            ->addColumn('login_time', 'timestamp', [
                'null' => true,
                'comment' => '最后登陆时间',
                'after' => 'login_ip',
            ])
            ->addColumn('backend_setting', 'string', [
                'null' => true,
                'limit' => 500,
                'collation' => 'utf8mb4_general_ci',
                'encoding' => 'utf8mb4',
                'comment' => '后台设置数据',
                'after' => 'login_time',
            ])
            ->addColumn('created_by', 'integer', [
                'null' => true,
                'limit' => MysqlAdapter::INT_BIG,
                'comment' => '创建者',
                'after' => 'backend_setting',
            ])
            ->addColumn('updated_by', 'integer', [
                'null' => true,
                'limit' => MysqlAdapter::INT_BIG,
                'comment' => '更新者',
                'after' => 'created_by',
            ])
            ->addColumn('created_at', 'timestamp', [
                'null' => true,
                'comment' => '创建时间',
                'after' => 'updated_by',
            ])
            ->addColumn('updated_at', 'timestamp', [
                'null' => true,
                'comment' => '更新时间',
                'after' => 'created_at',
            ])
            ->addColumn('deleted_at', 'timestamp', [
                'null' => true,
                'comment' => '删除时间',
                'after' => 'updated_at',
            ])
            ->addColumn('remark', 'string', [
                'null' => true,
                'limit' => 255,
                'collation' => 'utf8mb4_general_ci',
                'encoding' => 'utf8mb4',
                'comment' => '备注',
                'after' => 'deleted_at',
            ])
            ->addIndex(['username'], [
                'name' => 'system_user_username_unique',
                'unique' => true,
            ])
            ->addIndex(['dept_id'], [
                'name' => 'system_user_dept_id_index',
                'unique' => false,
            ])
            ->create();
        $this->table('system_user_post', [
                'id' => false,
                'primary_key' => ['user_id', 'post_id'],
                'engine' => 'InnoDB',
                'encoding' => 'utf8mb4',
                'collation' => 'utf8mb4_general_ci',
                'comment' => '用户与岗位关联表',
                'row_format' => 'DYNAMIC',
            ])
            ->addColumn('user_id', 'integer', [
                'null' => false,
                'limit' => MysqlAdapter::INT_BIG,
                'signed' => false,
                'comment' => '用户主键',
            ])
            ->addColumn('post_id', 'integer', [
                'null' => false,
                'limit' => MysqlAdapter::INT_BIG,
                'signed' => false,
                'comment' => '岗位主键',
                'after' => 'user_id',
            ])
            ->create();
        $this->table('system_user_role', [
                'id' => false,
                'primary_key' => ['user_id', 'role_id'],
                'engine' => 'InnoDB',
                'encoding' => 'utf8mb4',
                'collation' => 'utf8mb4_general_ci',
                'comment' => '用户与角色关联表',
                'row_format' => 'DYNAMIC',
            ])
            ->addColumn('user_id', 'integer', [
                'null' => false,
                'limit' => MysqlAdapter::INT_BIG,
                'signed' => false,
                'comment' => '用户主键',
            ])
            ->addColumn('role_id', 'integer', [
                'null' => false,
                'limit' => MysqlAdapter::INT_BIG,
                'signed' => false,
                'comment' => '角色主键',
                'after' => 'user_id',
            ])
            ->create();
    }
}
