<?php
/**
 *
 * author: flycorn
 * email: ym1992it@163.com
 * time: 16/12/1 上午11:19
 */
use App\Bootstrap;

define('BASE_PATH', dirname(__DIR__));

include BASE_PATH.DIRECTORY_SEPARATOR.'bootstrap'.DIRECTORY_SEPARATOR.'autoload.php';

$bootstrap = new Bootstrap();
echo $bootstrap->run();