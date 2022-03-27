<?php

use nyuwa\aop\example\User1Aspect;

return [
    'annotations' => [
        'scan' => [
            'paths' => [
                BASE_PATH . '/app',
                BASE_PATH . '/nyuwa',
            ],
            'ignore_annotations' => [
                'mixin',
            ],
            'class_map' => [
            ],
        ],
    ],
    'aspects' => [
        // 这里写入对应的 Aspect
        User1Aspect::class,
    ]
];
