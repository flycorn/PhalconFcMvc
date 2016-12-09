<?php
namespace App\Modules\Api\Tasks;
/**
 * 队列任务
 * author: flycorn
 * email: ym1992it@163.com
 * time: 16/12/9 上午11:28
 */
use App\Providers\TaskProvider;
use Phalcon\Queue\Beanstalk;
class QueueTask extends TaskProvider
{
    protected $beanstalk;
    protected $connected = false; //连接状态

    //初始化
    public function initialize()
    {
        //连接Beanstalk
        $this->beanstalk = new Beanstalk(
            $this->config['beanstalk']
        );
        //选择对应模块
        $this->beanstalk->watch($this->module);

        $this->connected =& is_resource($this->beanstalk);
        echo $this->connected;
        echo 'Watching tube: '.$this->module."\n";
        $this->_connect();
    }

    //连接
    protected function _connect() {
        if (!$this->beanstalk) {
            return false;
        }
        return true;
    }

    //关闭
    public function close() {
        if ($this->connected) {
            return $this->beanstalk->disconnect();
        }
    }

    public function disconnect() {
        echo "disconnected";
        return $this->beanstalk->disconnect();
    }

    public function isConnected() {
        return $this->connected;
    }

    //主任务
    public function mainAction()
    {
        while(true) {
            echo 'Waiting for a job... STRG+C to abort.'."\n";
            // get latest job
            $job = $this->beanstalk->reserve();

            if(!$job) {
                // kind of serious error. yet to see this occur.
                echo 'Invalid job found. Not processing.'."\n";
            } else {
                // announce the job id being processed
                $job_id = $job->getId();
                echo 'Processing job '.$job_id."\n";

                //获取任务详情
                $jobInfo = $job->getBody();

                $result = null; //执行结果

                //处理任务
                try{

                    //处理任务
                    $this->console->handle(
                        array(
                            'task'   => $jobInfo['task'],
                            'action' => $jobInfo['action'],
                            'arguments' => $jobInfo['arguments'],
                        )
                    );
                    //成功
                    $result = true;

                }catch (\Exception $exception) {

                }

                //验证结果
                if($result) {
                    //处理成功、删除任务
                    $job->delete();

                    echo 'Success Job '.$job_id.'. Deleting.'."\n";
                } else {
                    //处理失败、1秒后重试
                    $job->bury(1000);

                    echo 'Failed Job '.$job_id.'. Burying.'."\n";
                }
            }
        }
    }
}