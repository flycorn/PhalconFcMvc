<?php
namespace App\Providers;
/**
 * 任务供应商
 * author: flycorn
 * email: ym1992it@163.com
 * time: 16/12/8 下午10:08
 */
use \Phalcon\CLI\Task;
abstract class TaskProvider extends Task
{
    public function mainAction()
    {
        echo "\nThis is the default task and the default action \n";
    }
}