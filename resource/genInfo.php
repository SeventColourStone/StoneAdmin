<?php

return [
    [
        "name" => "im_friend",
        "comment" => "im 好友",
        'menu_name' => "好友管理hhh",
        'module_name' => "im",
        'namespace' => "app\im",
        'belong_menu_id' => "3000",
        'generate_menus' => "save,update,read,delete,recycle,changeStatus,numberOperation,import,export",
        "options" => [
            ["relations" => [["name" => "userInfo", "type" => "hasOne", "model" => "\\app\\admin\\model\\system\\SystemUser", "foreignKey" => "id", "localKey" => "user_id", "table" => ""]], "tree_id" => "user_id", "tree_parent_id" => "friend_id", "tree_name" => "message"]
        ]
    ],
    [
        "name" => "bbs_articles",
        "comment" => "bbs 文章",
        'menu_name' => "文章管理hhh",
        'module_name' => "bbs",
        'namespace' => "app\bbs",
        'belong_menu_id' => "3000",
        'generate_menus' => "save,update,read,delete,recycle,changeStatus,numberOperation,import,export",
    ]
];