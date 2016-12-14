<?php
namespace App\Modules\Api\Controllers;
/**
 * 接口基类控制器
 * author: flycorn
 * email: ym1992it@163.com
 * time: 16/12/6 上午10:44
 */
use App\Providers\ControllerProvider;
use App\Modules\Api\Services\ResponseService;

class BaseController extends ControllerProvider
{
    //初始化
    public function initialize()
    {
        $this->responseService = new ResponseService();
    }
}