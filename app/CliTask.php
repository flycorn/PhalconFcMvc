<?php
namespace App;
/**
 * 命令行应用
 * author: flycorn
 * email: ym1992it@163.com
 * time: 16/12/8 下午6:01
 */
use Phalcon\DI\FactoryDefault\Cli as CliDi,
    Phalcon\CLI\Console as ConsoleApp,
    Phalcon\ClI\Dispatcher,
    Phalcon\Loader,
    Phalcon\Logger\Adapter\File as FileAdapter;

define('BASE_PATH', dirname(__DIR__));
define('APP_PATH', BASE_PATH.DIRECTORY_SEPARATOR.'app'.DIRECTORY_SEPARATOR);

date_default_timezone_set('Asia/Shanghai');
header("Content-type: text/html; charset=utf-8");
/**
 * 加载composer类库
 */
require_once BASE_PATH.DIRECTORY_SEPARATOR.'vendor'.DIRECTORY_SEPARATOR.'autoload.php';

class CliTask
{
    protected $di;
    protected $console;
    protected $module;
    protected $arguments;
    protected $config;

    public function __construct($argv)
    {
        /**
         * 处理console应用参数
         */
        $this->module = ''; //模块
        $this->arguments = []; //参数
        foreach ($argv as $k => $arg) {
            if($k == 1){
                $this->module = $arg;
            } elseif ($k == 2) {
                $this->arguments['task'] = $arg;
            } elseif ($k == 3) {
                $this->arguments['action'] = $arg;
            } elseif ($k >= 4) {
                $this->arguments['params'][] = $arg;
            }
        }
        // 定义全局的参数， 设定当前任务及动作
        define('CURRENT_MODULE',   (isset($argv[2]) ? $argv[2] : null));
        define('CURRENT_TASK',   (isset($argv[2]) ? $argv[2] : null));
        define('CURRENT_ACTION', (isset($argv[3]) ? $argv[3] : null));

        $this->module = ucfirst($this->module);

        //验证是否已传入模块
        if(empty($this->module)) throw new \Exception('请传入参数指定对应模块!');

        //获取模块Dir
        $moduleDir = APP_PATH.'/Modules/'.$this->module;
        //验证该模块是否已存在
        if(!is_dir($moduleDir)) throw new \Exception('该模块不存在!');
        //验证主任务是否存在
        if(!is_file($moduleDir.'/Tasks/MainTask.php')) throw new \Exception('主任务文件不存在!');

        /**
         * 注册目录及命名空间
         */
        $loader = new Loader();
        $loader->registerDirs(
            [
                $moduleDir.'/Tasks/'
            ]
        );
        $loader->registerNamespaces([
            'App' => APP_PATH
        ]);
        $loader->register();

        // 使用CLI工厂类作为默认的服务容器
        $this->di = new CliDi();

        //初始化服务
        $this -> initializeServices();

        //创建应用
        $this->console = new ConsoleApp();
        $this->console->setDI($this->di);
        //注入应用
        $this->di->setShared('console', $this->console);
    }

    /**
     * 初始化配置
     */
    protected function initializeServices()
    {
        $module = $this->module;

        /**
         * 获取配置
         */
        $config = array_merge(include BASE_PATH."/config/app.php", include BASE_PATH."/config/modules.php");
        $this->config = $config;

        //注册配置
        $this->di->setShared('config', function () use($config){
            return $config;
        });

        //注册模块
        $this->di->setShared('module', function () use($module){
            return $module;
        });

        //注册派遣器
        $this->di->set('dispatcher', function () use ($module){
            $dispatcher = new Dispatcher();
            $dispatcher->setDefaultNamespace('App\Modules\\'.$module.'\\Tasks\\');
            return $dispatcher;
        });

        //注册日志
        $this->di->set('log', function() use($module){
            $dirPath = BASE_PATH.'/storage/logs/cli/'.lcfirst($module).'/';
            //检测目录是否存在
            if(!is_dir($dirPath)) mkdir($dirPath, 0777, true);
            $fileLog = new FileAdapter($dirPath.date('Ymd').'.log');
            return $fileLog;
        });

        //注册数据库
        $this->di->set('db', function () {
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
        $this->di->set('redis', function () {
            $config = $this->getConfig();

            $host = $config['redis']['host'];
            $port = $config['redis']['port'];
            $redis = new Redis();
            $redis->connect($host, $port);

            return $redis;
        });

    }

    //执行
    public function run()
    {
        try{
            $this->console->handle($this->arguments);
        }catch(\Exception $e){
            echo $e->getMessage();
            exit(255);
        }
    }
}

//应用开始
$cli = new CliTask($argv);
$cli -> run();