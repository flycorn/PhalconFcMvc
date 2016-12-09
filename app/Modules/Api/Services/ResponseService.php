<?php
namespace App\Modules\Api\Services;
/**
 * 响应服务
 * author: flycorn
 * email: ym1992it@163.com
 * time: 16/12/6 下午3:14
 */
class ResponseService
{
    public function __construct()
    {
        $this->response = new \Phalcon\Http\Response();
    }

    //响应状态
    public $statusCode = '200';

    /**
     * 获取响应状态
     * @return string
     */
    public function getStatusCode()
    {
        return $this -> statusCode;
    }

    /**
     * 设置响应状态
     * @param $statusCode
     * @return $this
     */
    public function setStatusCode($statusCode)
    {
        $this -> statusCode = $statusCode;
        return $this;
    }

    /**
     * 错误响应
     * @param $message
     * @return \Phalcon\Http\Response
     */
    public function responseError($message)
    {
        return $this -> response([
            'status' => 'failed',
            'errors' => [
                'status_code' => $this -> getStatusCode(),
                'message' => $message,
            ]
        ]);
    }

    /**
     * 成功响应
     * @param $message
     * @param array $data
     * @return \Phalcon\Http\Response
     */
    public function responseSuccess($message, $data = [])
    {
        return $this -> response([
            'status' => 'successful',
            'correct' => [
                'status_code' => $this -> getStatusCode(),
                'message' => $message,
                'data' => $data,
            ]
        ]);
    }

    /**
     * 响应
     * @param $data
     * @return \Phalcon\Http\Response
     */
    public function response($data)
    {
        $this->response->setContent(json_encode($data));
        return $this->response;
    }
}