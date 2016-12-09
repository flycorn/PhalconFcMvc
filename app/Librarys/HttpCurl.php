<?php
namespace App\Librarys;
/**
 * HttpCurl处理类
 * author: flycorn
 * email: ym1992it@163.com
 * time: 16/12/6 下午4:11
 */
class HttpCurl
{
    private $handle = null;

    public static function isAvailable()
    {
        return extension_loaded('curl');
    }

    public function __construct()
    {
        //检测是否安装该扩展
        if (!self::isAvailable()) {
            throw new \Exception('CURL extension is not loaded');
        }
        //初始化
        $this->handle = curl_init();
        if (!is_resource($this->handle)) {
            throw new \Exception(curl_error($this->handle), 'curl');
        }
        //初始化参数
        $this->initOptions();
    }

    public function __destruct()
    {
        curl_close($this->handle);
    }

    public function __clone()
    {
        $request = new self;
        $request->handle = curl_copy_handle($this->handle);
        return $request;
    }

    //初始化参数
    private function initOptions()
    {
        $this->setOptions([
            CURLOPT_RETURNTRANSFER  => true,
            CURLOPT_AUTOREFERER     => true,
            CURLOPT_FOLLOWLOCATION  => true,
            CURLOPT_MAXREDIRS       => 20,
            CURLOPT_HEADER          => false,
            CURLOPT_PROTOCOLS       => CURLPROTO_HTTP | CURLPROTO_HTTPS,
            CURLOPT_REDIR_PROTOCOLS => CURLPROTO_HTTP | CURLPROTO_HTTPS,
            CURLOPT_USERAGENT       => '',
            CURLOPT_CONNECTTIMEOUT  => 30,
            CURLOPT_TIMEOUT         => 30,
        ]);
    }

    /**
     * 设置单个参数
     * @param $option
     * @param $value
     * @return bool
     */
    public function setOption($option, $value)
    {
        return curl_setopt($this->handle, $option, $value);
    }

    /**
     * 设置多个参数
     * @param $options
     * @return bool
     */
    public function setOptions($options)
    {
        return curl_setopt_array($this->handle, $options);
    }

    /**
     * 设置超时时间
     * @param $timeout
     */
    public function setTimeout($timeout)
    {
        $this->setOption(CURLOPT_TIMEOUT, $timeout);
    }

    /**
     * 设置连接时间
     * @param $timeout
     */
    public function setConnectTimeout($timeout)
    {
        $this->setOption(CURLOPT_CONNECTTIMEOUT, $timeout);
    }

    /**
     * 发送请求
     * @param array $customHeader
     * @return Response
     */
    protected function send(array $customHeader = [])
    {
        $header = [];
        if (!empty($customHeader)) {
            $header = $customHeader;
        }
        $header = array_unique($header, SORT_STRING);
        $this->setOption(CURLOPT_HTTPHEADER, $this->headerOption($header));
        $content = curl_exec($this->handle);
        //获取状态码
        $httpCode = curl_getinfo($this->handle, CURLINFO_HTTP_CODE);
        if ($errno = curl_errno($this->handle)) {
            return [
                'httpCode' => $httpCode,
                'status' => 0,
                'msg' => curl_error($this->handle),
                'content' => '',
            ];
        }

        return [
            'httpCode' => $httpCode,
            'status' => 1,
            'msg' => '请求成功!',
            'content' => $content,
        ];
    }

    /**
     * 请求头参数转换
     * @param $header
     * @return array
     */
    protected function headerOption($header)
    {
        $data = [];
        if(!empty($header)){
            foreach ($header as $k => $v)
            {
                $data[] = $k.': '.$v;
            }
        }
        return $data;
    }

    //初始化post参数值
    protected function initPostFields($params, $isJson = false, $useEncoding = false)
    {
        if($useEncoding){
            if (is_array($params)) {
                $useEncodingBool = true;
                foreach ($params as $param) {
                    if (is_string($param) && preg_match('/^@/', $param)) {
                        $useEncodingBool = false;
                        break;
                    }
                }
                if ($useEncodingBool) {
                    $params = http_build_query($params);
                }
            }
        }
        if (!empty($params)) {
            $this->setOption(CURLOPT_POSTFIELDS, !$isJson ? $params : json_encode($params));
        }
    }

    /**
     * GET请求
     * @param string $uri
     * @param array $params
     * @param array $customHeader
     * @return Response
     */
    public function get($uri, $customHeader = [])
    {
        $this->setOptions([
            CURLOPT_URL           => $uri,
            CURLOPT_HTTPGET       => true,
            CURLOPT_CUSTOMREQUEST => 'GET',
        ]);
        return $this->send($customHeader);
    }

    /**
     * POST请求
     * @param $uri
     * @param array $params
     * @param bool $useEncoding
     * @param array $customHeader
     * @return Response
     */
    public function post($uri, $params = [], $customHeader = [], $isJson = false, $useEncoding = false)
    {
        $this->setOptions([
            CURLOPT_URL           => $uri,
            CURLOPT_POST          => true,
            CURLOPT_CUSTOMREQUEST => 'POST',
        ]);
        $this->initPostFields($params, $isJson, $useEncoding);
        return $this->send($customHeader);
    }

}