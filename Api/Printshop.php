<?php
namespace Api;
use Pest\Db\Collection;
use Pest\Api;
use Pest\Response;
use Pest\Request;

class Printshop extends Api
{

    public $post = array(
    		"openid" =>'/^\S{28}$/',
    );

    public function post ()
    {
    	$data = Request::getInstance()->getData();
    	$c = new Collection("printshop");
    	$ret = $c ->findOne("openid = ?",array($data["openid"]));
    	if($ret){
    		//已经存在，就是更新
    		$data["id"] = $ret["id"];
    		$c->save($data);
    	}else{
    		//尚未存在，就是新建
    		$c->save($data);
    	}
    }
    
    public $get = array(
    		"openid" =>'/^\S{28}$/',
    );
    
    public function get ()
    {
    	$data = Request::getInstance()->getData();
		$c = new Collection("printshop");
		$p = $c->findOne("openid = ?",array($data["openid"]));
		Response::sendSuccess($p);
    }

    public function all(){
    	$c = new Collection("printshop");
    	$p = $c->findAll("1 =1",null);
    	Response::sendSuccess($p);
    }
}
