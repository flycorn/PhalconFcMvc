<?php
/**
 * 项目配置文件
 * author: flycorn
 * email: ym1992it@163.com
 * time: 16/12/5 下午2:04
 */
return [
    //数据库配置
    'database'    => [
        'adapter'     => 'Mysql',
        'host'        => 'localhost',
        'username'    => 'root',
        'password'    => '123456',
        'dbname'      => 'phalcon_test',
        'charset'     => 'utf8',
    ],
    //redis配置
    'redis' => [
        'host' => 'localhost',
        'port' => '6379'
    ],
    //beanstalk配置
    'beanstalk' => [
        'host' => 'localhost',
        'port' => '11300',
    ],
];