<?php
namespace App\Librarys;
/**
 * 模块Beanstalk
 * author: flycorn
 * email: ym1992it@163.com
 * time: 16/12/12 上午10:45
 */
use Phalcon\Queue\Beanstalk;
class ModuleBeanstalk
{
    protected $module; //模块
    protected $config = [
        'host' => 'localhost',
        'port' => '11300',
    ]; //Beanstalk配置
    protected $mode = 'choose'; //方式
    protected $beanstalk;

    public function __construct($param = [])
    {
        if(!empty($param) && is_array($param)){
            //配置数据
            if(isset($param['module'])){
                $this->module=$param['module'];
            }
            if(isset($param['config'])) {
                $this->config = $param['config'];
            }
        }
        //连接
        $this-> _connect();
        //选择方式
        if(isset($param['mode'])){
            $this->$param['mode']($this->module);
        }
    }

    //连接
    protected function _connect()
    {
        if($this->beanstalk) return $this->beanstalk;
        $this->beanstalk = new Beanstalk($this->config);
        return $this->beanstalk;
    }

    //状态
    protected function choose($module)
    {
        $this->beanstalk->choose($module);
    }

    //状态
    protected function watch($module)
    {
        $this->beanstalk->watch($module);
    }

    //退出
    public function quit() {
        if ($this->beanstalk) {
            return $this->beanstalk->quit();
        }
    }

    //判断是否连接
    public function isConnected() {
        return $this->beanstalk ? true : false;
    }

    public function reserve($timeout = null)
    {
        return $this->beanstalk->reserve($timeout);
    }

    public function peekReady()
    {
        return $this->beanstalk->peekReady();
    }

    public function put($data, array $options = null)
    {
        return $this->beanstalk->put($data, $options);
    }
    
}