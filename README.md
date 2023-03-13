
# 介绍

[参考文档](https://seventcolourstone.github.io/docs/)

Stone Admin 基于 [Webman](https://www.workerman.net/webman) 框架开发，迁移来自 [MineAdmin](https://v0.mineadmin.com/doc/guide/#%E5%86%85%E7%BD%AE%E5%8A%9F%E8%83%BD) 框架，使用MineAdmin 的0.7.1版本，前端用[SCUI](https://lolicode.gitee.io/scui-doc/)。您可以点击链接前往了解以上的开源项目，感谢开源社区。

> 为何会做这样的事情，hyperf 确实足够强大，但就喜欢 webman 小而美，而兼顾极致的性能。还有学习的一部分原因在。

# webman是什么

webman是一款基于[workerman](https://www.workerman.net/)开发的高性能HTTP服务框架。webman用于替代传统的php-fpm架构，提供超高性能可扩展的HTTP服务。你可以用webman开发网站，也可以开发HTTP接口或者微服务。

除此之外，webman还支持自定义进程，可以做workerman能做的任何事情，例如websocket服务、物联网、游戏、TCP服务、UDP服务、unix socket服务等等。
以上摘抄至Webman 官网介绍。具体可以前往了解。

而 Stone Admin 基于Webman 最新的1.4版本开发，主要把MineAdmin的后台管理功能迁移到Webman 最新的应用插件上，实现快速安装上手。


# SCUI是什么

[SCUI Admin](https://lolicode.gitee.io/scui-doc/)
高性能中后台前端解决方案
基于 Vue3、elementPlus 持续性的提供独家组件和丰富的业务模板帮助你快速搭建企业级中后台前端任务。


# 内置功能

基本迁移了MineAdmin 的所有基础管理。定时任务 使用Workerman 的定时器Timer

- 用户管理，完成用户添加、修改、删除配置，支持不同用户登录后台看到不同的首页
- 部门管理，部门组织机构（公司、部门、小组），树结构展现支持数据权限
- 岗位管理，可以给用户配置所担任职务
- 角色管理，角色菜单权限分配、角色数据权限分配
- 菜单管理，配置系统菜单和按钮等
- 字典管理，对系统中经常使用并且固定的数据可以重复使用和维护
- 系统配置，系统的一些常用设置管理
- 操作日志，用户对系统的一些正常操作的查询
- 登录日志，用户登录系统的记录查询
- 服务监控，查看当前服务器状态和PHP环境等信息
- 依赖监控，查看当前程序所依赖的库信息和版本
- 附件管理，管理当前系统上传的文件及图片等信息
- 数据表维护，对系统的数据表可以进行清理碎片和优化
- 数据表设计器，简单版数据库设计器，搭配代码生成器事半功倍
- 定时任务，在线（添加、修改、删除)任务调度包含执行结果日志
- 代码生成，前后端代码的生成（php、vue、js、sql），支持下载和生成到模块
- 缓存监控，查看Redis信息和系统所使用key的信息
