<?php


return [
    // 默认磁盘
    'default' => 'local',
    // 磁盘列表
    'disks'   => [
        'local'  => [
            'type' => 'local',
            'root' => app()->getRuntimePath() . 'storage',
        ],


        'result' => [
            // 磁盘类型
            'type'       => 'local',
            // 磁盘路径
            'root'       => app()->getRootPath() . 'public/result',
            // 磁盘路径对应的外部URL路径
            'url'        => '/result',
            // 可见性
            'visibility' => 'public',
        ],
    ],
];
