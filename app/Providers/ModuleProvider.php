<?php
namespace App\Providers;
/**
 * 模块服务商
 * author: flycorn
 * email: ym1992it@163.com
 * time: 16/12/6 上午8:35
 */
use Phalcon\Loader;
use Phalcon\Mvc\ModuleDefinitionInterface;
use Phalcon\DiInterface;
use Phalcon\Mvc\Dispatcher;
use Phalcon\Mvc\View;
use Phalcon\Mvc\View\Engine\Php as PhpEngine;
use Phalcon\Mvc\View\Engine\Volt as VoltEngine;
//use Phalcon\Mvc\Model\Metadata\Memory as MetaDataAdapter;
use Phalcon\Session\Adapter\Files as SessionAdapter;
use Phalcon\Events\Manager as EventsManager;
use Phalcon\Logger\Adapter\File as FileAdapter;

abstract class ModuleProvider implements ModuleDefinitionInterface
{
    protected $moduleName;

    /**
     * 注册自动加载
     * @param DiInterface|null $di
     */
    public function registerAutoloaders(DiInterface $di = null){}

    /**
     * 注册服务
     * @param $di
     */
    public function registerServices(DiInterface $di)
    {
        $moduleName = ucfirst($this->moduleName);
        //注册派遣器
        $di->set('dispatcher', function () use ($moduleName){
            $eventsManager = new EventsManager;
            //错误处理
            $eventsManager->attach('dispatch:beforeException', function(Event $event, Dispatcher $dispatcher, \Exception $exception) use ($moduleName){
                if ($exception instanceof DispatcherException) {
                    switch ($exception->getCode()) {
                        case Dispatcher::EXCEPTION_HANDLER_NOT_FOUND:
                        case Dispatcher::EXCEPTION_ACTION_NOT_FOUND:
                            $dispatcher->forward([
                                'controller' => 'base',
                                'action' => 'error404'
                            ]);
                            return false;
                    }
                }
                $dispatcher->forward([
                    'controller' => 'base',
                    'action'     => 'error500'
                ]);
                return false;
            });

            $dispatcher = new Dispatcher();
            $dispatcher->setDefaultNamespace('App\Modules\\'.$moduleName.'\Controllers\\');
            $dispatcher->setEventsManager($eventsManager);
            return $dispatcher;
        });

        //注册视图
        $di->set('view', function () use ($moduleName){
            $view = new View();
            $view->setViewsDir('../app/Modules/'.$moduleName.'/Views/');

            $view->registerEngines([
                '.volt' => function ($view) use ($moduleName){

                    $volt = new VoltEngine($view, $this);

                    $dirPath = BASE_PATH.'/storage/cache/'.lcfirst($moduleName).'/';

                    //检测目录是否存在
                    if(!is_dir($dirPath)) mkdir($dirPath, 0777, true);

                    $volt->setOptions([
                        'compiledPath' => $dirPath,
                        'compiledSeparator' => '_'
                    ]);

                    return $volt;
                },
                '.phtml' => PhpEngine::class

            ]);
            return $view;
        });

        //注册日志
        $di->set('log', function() use($moduleName){
            $dirPath = BASE_PATH.'/storage/logs/app/'.lcfirst($moduleName).'/';
            //检测目录是否存在
            if(!is_dir($dirPath)) mkdir($dirPath, 0777, true);
            $fileLog = new FileAdapter($dirPath.date('Ymd').'.log');
            return $fileLog;
        });

        //注册session
        $di->set('session', function () {
            $session = new SessionAdapter();
            $session->start();

            return $session;
        });

        //注册数据库
        $di->set('db', function () {
            $config = $this->getConfig();

            $class = 'Phalcon\Db\Adapter\Pdo\\' . $config['database']['adapter'];
            $params = [
                'host'     => $config['database']['host'],
                'username' => $config['database']['username'],
                'password' => $config['database']['password'],
                'dbname'   => $config['database']['dbname'],
                'charset'  => $config['database']['charset']
            ];
            if ($config['database']['adapter'] == 'Postgresql') {
                unset($params['charset']);
            }
            $connection = new $class($params);

            return $connection;
        });

        //注册redis
        $di->set('redis', function () {
            $config = $this->getConfig();

            $host = $config['redis']['host'];
            $port = $config['redis']['port'];
            $redis = new Redis();
            $redis->connect($host, $port);

            return $redis;
        });

//        $di->set('modelsMetadata', function () {
//            return new MetaDataAdapter();
//        });
    }
}