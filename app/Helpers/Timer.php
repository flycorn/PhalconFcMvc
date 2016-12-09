<?php
namespace App\Helpers;
/**
 * 时间处理工具
 * author: flycorn
 * email: ym1992it@163.com
 * time: 16/12/6 上午10:55
 */
class Timer
{
    /**
     * 格式化时间
     * @param $time '时间戳'
     * @return bool|string
     */
    public static function formatDate($time=null)
    {
        $date = is_null($time) ? date('Y-m-d H:i:s', time()) : date('Y-m-d H:i:s', $time);
        return $date;
    }

    /**
     * 计算时间差
     * 使用方法：
     * $time_diff = Tools::timeDiff('2014-06-07', '2015-06-09');
     * var_dump($time_diff);
     * @param $start
     * @param $end
     * @param string $type
     * @return float
     */
    public static function timeDiff($start, $end, $type = 'day')
    {
        switch ($type) {
            case 'second':
                $second = 1;
                break;
            case 'minute':
                $second = 60;
                break;
            case 'hour':
                $second = 60 * 60;
                break;
            default:
                $second = 60 * 60 * 24;
        }
        $time = floor((strtotime($end) - strtotime($start)) / $second);
        return $time;
    }

}
