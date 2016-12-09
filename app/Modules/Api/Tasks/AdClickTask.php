<?php
namespace App\Modules\Api\Tasks;
/**
 * 广告点击
 * author: flycorn
 * email: ym1992it@163.com
 * time: 16/12/9 下午2:00
 */
use App\Providers\TaskProvider;
use App\Modules\Api\Services\InMobiService;

class AdClickTask extends TaskProvider
{
    protected $log; //日志

    //初始化
    public function initialize()
    {
        $this->inMobiService = new InMobiService();
        //绑定日志
        $this->inMobiService->log = $this->console->log;
        //绑定配置
        $this->inMobiService->config = $this->config;
    }

    public function mainAction($arguments)
    {
        if(!empty($arguments)){
            try{
                //获取参数
                $arguments = $arguments['arguments'];
                $this->inMobiService->handleAdShow($arguments['ua'], $arguments['ad_token']);
            }catch (\Exception $exception){
                throw new \Exception('ping失败...');
            }
        }
    }
}