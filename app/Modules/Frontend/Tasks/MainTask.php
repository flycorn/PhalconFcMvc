<?php
namespace App\Modules\Frontend\Tasks;
/**
 * 主任务
 * author: flycorn
 * email: ym1992it@163.com
 * time: 16/12/8 下午6:28
 */
use App\Providers\TaskProvider;
class MainTask extends TaskProvider
{
    public function mainAction()
    {
        echo "\nThis is the default task and the default action \n";
    }

    public function testAction()
    {
        echo "\nI will get printed too!\n";
    }
}