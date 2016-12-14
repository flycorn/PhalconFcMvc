<?php
namespace App;

/**
 * 程序
 * author: flycorn
 * email: ym1992it@163.com
 * time: 16/12/1 下午4:39
 */
use Phalcon\Di\FactoryDefault as Di;
use Phalcon\Mvc\Application;
use Phalcon\Mvc\Router;

class Bootstrap
{
    protected $di;

    protected $serviceProviders = [];

    protected $app;

    protected $config;

    //初始化
    public function __construct()
    {
        //创建容器
        $this->di = new Di();

        //初始化服务
        $this -> initializeServices();

        //创建应用
        $this->app = new Application;
        $this->di['app'] = $this->app;
        $this->app->setDI($this->di);

        /**
         * 注册模块
         */
        $this->app->registerModules($this->config['modules']);
    }

    /**
     * 初始化服务
     */
    protected function initializeServices()
    {
        /**
         * 获取配置
         */
        $config = array_merge(include BASE_PATH."/config/app.php", include BASE_PATH."/config/modules.php");
        $this->config = $config;

        /**
         * 设置配置
         */
        $this->di->setShared('config', function () use($config){
            return $config;
        });

        /**
         * 注册路由
         */
        $this->di->set('router', function () {
            //获取模块配置
            $config = $this->getConfig();
            $modules = $config['modules'];

            $router = new Router();

            $defaultModule = ''; //默认模块
            if(!empty($modules)){
                $module_nums = count($modules);
                $index = 1;
                //设置路由模块
                foreach ($modules as $k => $module)
                {
                    //判断是否默认
                    if($module_nums == $index){
                        $defaultModule = $k;
                    }
                    //模块名称
                    $module_name = empty($module['name']) ? '' : '/'.$module['name'];

                    if($module_nums != $index){
                        $router->add($module_name, array(
                            'module'     => $k,
                            'controller' => 1,
                            'action'     => 2,
                        ));
                        $router->add($module_name.'/:controller', array(
                            'module'     => $k,
                            'controller' => 1,
                            'action'     => 2,
                        ));
                        $router->add($module_name.'/:controller/:action', array(
                            'module'     => $k,
                            'controller' => 1,
                            'action'     => 2,
                        ));
                    }
                    $index++;
                }

                //设置默认模块
                $router->setDefaultModule($defaultModule);
            }
            return $router;
        });
    }

    /**
     * 获取返回内容
     * @return bool|\Phalcon\Http\ResponseInterface|string
     */
    protected function getOutput()
    {
        return $this->app->handle()->getContent();
    }

    /**
     * 执行
     * @return bool|\Phalcon\Http\ResponseInterface|string
     */
    public function run()
    {
        try{
            return $this->getOutput();
        }catch(\Exception $e){
            return $e->getMessage();
        }
    }
}
