<?php
namespace App\Models;
/**
 * Inmobi应用
 * author: flycorn
 * email: ym1992it@163.com
 * time: 16/12/7 上午11:58
 */
use Phalcon\Mvc\Model;
class InmobiApplications extends Model
{
    public $application_id;
    public $product_id;
    public $name;
    public $app_id;
    public $app_bundle;
    public $system;
    public $tag;

    public function initialize()
    {
        $this->setSource("inmobi_applications");
    }
}