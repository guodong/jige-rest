<?php
namespace Api;
use Pest\Api;
use Pest\Db\Collection;
use Pest\Request;
use Pest\Response;
class Authorization extends Api
{
    public $get = array(
    		'code' => '/^\S{4,}$/',
    );
    
    public function get ()
    {
    	$data = Request::getInstance()->getData();
    	$c = new Collection('authorization');
    	$ret = $c->findOne("code = ? " , array($data["code"]));
    	if($ret){
    		Response::sendSuccess($ret);
    	}else{
    		Response::sendFailure(1000);
    	}
    }

    public $post = array(
    		'openid' => '/^\S{28}$/',
    );
    
    public function post ()
    {
    	$data = Request::getInstance()->getData();
    	$c = new Collection('authorization');
    	if(isset($data['time'])){
    		unset($data['time']);
    	}
    	$data['code'] = substr(strtoupper(md5(uniqid(mt_rand(), true))),1,6);
    	$data['time'] = time();
    	$ret = $c->save($data);
    	if($ret){
    		Response::sendSuccess($data['code']);
    	}else{
    		Response::sendFailure(1000);
    	}
    }
}
