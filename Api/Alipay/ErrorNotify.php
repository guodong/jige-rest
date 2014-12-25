<?php
namespace Api\Alipay;
use Pest\Api;
use Pest\Db\Collection;
use Pest\Request;
use Pest\Util;

class ErrorNotify extends Api
{

    public $post = array(

    );
    
    public function post ()
    {
    	$data = Request::getInstance()->getData();
    	$a = new Collection("alipay_errornotify");
    	$ret = $a->save($data);
    	if(!$ret){
    		Util::logger("alipay异常通知保存失败:".json_decode($data));
    	}
    	echo "success";
    }
}