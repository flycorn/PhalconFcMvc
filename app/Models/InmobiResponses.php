<?php
namespace App\Models;
/**
 * Inmobi响应
 * author: flycorn
 * email: ym1992it@163.com
 * time: 16/12/7 上午11:58
 */
use Phalcon\Mvc\Model;
class InmobiResponses extends Model
{
    public function initialize()
    {
        $this->setSource("inmobi_responses");
    }
}