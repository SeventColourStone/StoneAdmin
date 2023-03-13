<?php
/**
 *-------------------------------------------------------------------------p*
 * 配置文件
 *-------------------------------------------------------------------------h*
 * @copyright  Copyright (c) 2015-2022 Shopwwi Inc. (http://www.shopwwi.com)
 *-------------------------------------------------------------------------c*
 * @license    http://www.shopwwi.com        s h o p w w i . c o m
 *-------------------------------------------------------------------------e*
 * @link       http://www.shopwwi.com by 象讯科技 phcent.com
 *-------------------------------------------------------------------------n*
 * @since      shopwwi象讯·PHP商城系统Pro
 *-------------------------------------------------------------------------t*
 */

return [
    'enable' => true,
    'default' => 'local',
    'max_size' => 1024 * 1024 * 10, //单个文件大小10M
    'ext_yes' => [], //允许上传文件类型 为空则为允许所有
    'ext_no' => [], // 不允许上传文件类型 为空则不限制
    'storage' => [
        'public' => [
            'driver' => \Shopwwi\WebmanFilesystem\Adapter\LocalAdapterFactory::class,
            'root' => public_path().'\\'.env('UPLOAD_PATH', 'uploadfile'),
            'url' => '//127.0.0.1:8787' // 静态文件访问域名
        ],
        'local' => [
            'driver' => \Shopwwi\WebmanFilesystem\Adapter\LocalAdapterFactory::class,
            'root' => public_path().'\\'.env('UPLOAD_PATH', 'uploadfile'),
            'url' => '//127.0.0.1:8787' // 静态文件访问域名
        ],
        'ftp' => [
            'driver' => \Shopwwi\WebmanFilesystem\Adapter\FtpAdapterFactory::class,
            'host' => 'ftp.example.com',
            'username' => 'username',
            'password' => 'password',
            'url' => '' // 静态文件访问域名
            // 'port' => 21,
            // 'root' => '/path/to/root',
            // 'passive' => true,
            // 'ssl' => true,
            // 'timeout' => 30,
            // 'ignorePassiveAddress' => false,
            // 'timestampsOnUnixListingsEnabled' => true,
        ],
        'memory' => [
            'driver' => \Shopwwi\WebmanFilesystem\Adapter\MemoryAdapterFactory::class,
        ],
        's3' => [
            'driver' => \Shopwwi\WebmanFilesystem\Adapter\S3AdapterFactory::class,
            'credentials' => [
                'key' => 'S3_KEY',
                'secret' => 'S3_SECRET',
            ],
            'region' => 'S3_REGION',
            'version' => 'latest',
            'bucket_endpoint' => false,
            'use_path_style_endpoint' => false,
            'endpoint' => 'S3_ENDPOINT',
            'bucket_name' => 'S3_BUCKET',
            'url' => '' // 静态文件访问域名
        ],
        'minio' => [
            'driver' => \Shopwwi\WebmanFilesystem\Adapter\S3AdapterFactory::class,
            'credentials' => [
                'key' => 'S3_KEY',
                'secret' => 'S3_SECRET',
            ],
            'region' => 'S3_REGION',
            'version' => 'latest',
            'bucket_endpoint' => false,
            'use_path_style_endpoint' => true,
            'endpoint' => 'S3_ENDPOINT',
            'bucket_name' => 'S3_BUCKET',
            'url' => '' // 静态文件访问域名
        ],
        'oss' => [
            'driver' => \Shopwwi\WebmanFilesystem\Adapter\AliyunOssAdapterFactory::class,
            'accessId' => 'OSS_ACCESS_ID',
            'accessSecret' => 'OSS_ACCESS_SECRET',
            'bucket' => 'OSS_BUCKET',
            'endpoint' => 'OSS_ENDPOINT',
            'url' => '' // 静态文件访问域名
            // 'timeout' => 3600,
            // 'connectTimeout' => 10,
            // 'isCName' => false,
            // 'token' => null,
            // 'proxy' => null,
        ],
        'qiniu' => [
            'driver' => \Shopwwi\WebmanFilesystem\Adapter\QiniuAdapterFactory::class,
            'accessKey' => 'QINIU_ACCESS_KEY',
            'secretKey' => 'QINIU_SECRET_KEY',
            'bucket' => 'QINIU_BUCKET',
            'domain' => 'QINBIU_DOMAIN',
            'url' => '' // 静态文件访问域名
        ],
        'cos' => [
            'driver' => \Shopwwi\WebmanFilesystem\Adapter\CosAdapterFactory::class,
            'region' => 'COS_REGION',
            'app_id' => 'COS_APPID',
            'secret_id' => 'COS_SECRET_ID',
            'secret_key' => 'COS_SECRET_KEY',
            // 可选，如果 bucket 为私有访问请打开此项
            // 'signed_url' => false,
            'bucket' => 'COS_BUCKET',
            'read_from_cdn' => false,
            'url' => '' // 静态文件访问域名
            // 'timeout' => 60,
            // 'connect_timeout' => 60,
            // 'cdn' => '',
            // 'scheme' => 'https',
        ],
    ],
];