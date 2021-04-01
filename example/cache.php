<?php

use mon\store\Cache;
require __DIR__ . '/../vendor/autoload.php';


// 文件缓存类型配置
// $config = [
//     'type'          => 'File',
//     // 有效时间
//     'expire'        => 0,
//     // 使用子目录保存
//     'cache_subdir'  => true,
//     // 缓存前缀
//     'prefix'        => '',
//     // 缓存路径
//     'path'          => __DIR__ . '/cache',
//     // 数据压缩
//     'data_compress' => false,
// ];

// redis缓存配置
$config = [
    'type'      => 'redis',
    // 链接host
    'host'      => '127.0.0.1',
    // 链接端口
    'port'      => 6379,
    // 链接密码
    'password'  => '',
    // 自定义键前缀
    'prefix'    => '',
    // 读取超时时间
    'timeout'   => 0,
    // 缓存有效时间
    'expire'    => 0,
];

Cache::instance($config);
Cache::instance()->set('test', ['a' => 1, '2a' => 3]);

$data = Cache::instance()->get('test');

debug($data);
