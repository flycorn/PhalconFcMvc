<?php
namespace App\Modules\Backend\Controllers;
/**
 * 后台主页
 * author: flycorn
 * email: ym1992it@163.com
 * time: 16/12/1 上午11:33
 */
class IndexController extends BaseController
{
    public function indexAction()
    {
        $this->view->title = '后台模块';
    }
}