<?php
/**
 * 自动加载
 * author: flycorn
 * email: ym1992it@163.com
 * time: 16/12/5 下午3:23
 */
use Phalcon\Loader;

define('APP_PATH', BASE_PATH.DIRECTORY_SEPARATOR.'app'.DIRECTORY_SEPARATOR);

/**
 * 加载composer类库
 */
require_once BASE_PATH.DIRECTORY_SEPARATOR.'vendor'.DIRECTORY_SEPARATOR.'autoload.php';

/**
 * 加载
 */
$loader = new Loader();
//注册命名空间
$loader->registerNamespaces([
    'App' => APP_PATH
])->register();