<?php
namespace App\Models;
/**
 * Inmobi设备
 * author: flycorn
 * email: ym1992it@163.com
 * time: 16/12/7 上午11:58
 */
use Phalcon\Mvc\Model;
class InmobiDevices extends Model
{
    public function initialize()
    {
        $this->setSource("inmobi_devices");
    }
}