<?php
namespace App\Modules\Frontend\Controllers;
/**
 *
 * author: flycorn
 * email: ym1992it@163.com
 * time: 16/12/1 上午11:33
 */
class IndexController extends BaseController
{
    public function indexAction()
    {
        $this->view->title = '前台模块';
    }
}