<?php
namespace App\Models;
/**
 * Inmobi广告事件
 * author: flycorn
 * email: ym1992it@163.com
 * time: 16/12/7 上午11:58
 */
use Phalcon\Mvc\Model;
class InmobiAdEvents extends Model
{
    public $event_id;
    public $ad_id;
    public $ad_token;
    public $type;
    public $url_type;
    public $url;
    public function initialize()
    {
        $this->setSource("inmobi_ad_events");
    }
}