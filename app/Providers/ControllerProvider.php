<?php
namespace App\Providers;
/**
 * 控制器服务商
 * author: flycorn
 * email: ym1992it@163.com
 * time: 16/12/14 下午12:04
 */
use Phalcon\Mvc\Controller;
abstract class ControllerProvider extends Controller
{
    //404错误
    public function error404Action()
    {
        echo '404错误';
    }

    //500错误
    public function error500Action()
    {
        echo '500错误';
    }
}