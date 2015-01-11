<?php
namespace Api;
use Pest\Db\Collection;
use Pest\Api;
use Pest\Response;
use Pest\Request;
use Pest\Util;

class Printshop extends Api
{

    public $post = array(
    		"openid" =>'/^\S{28}$/',
    );

    public function post ()
    {
    	$data = Request::getInstance()->getData();
    	$c = new Collection("printshop");
    	$ret = $c->findOne("username = ? OR displayname = ?",array($data['username'],$data['displayname']));
    	if($ret){
    		Response::sendFailure(1005);
    		return;
    	}
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
    );
    
    public function get ()
    {
    	$data = Request::getInstance()->getData();
    	$c = new Collection("printshop");
    	if("id" == $data['type']){
    		$p = $c->findOne("id = ?",array($data["shopid"]));
    	}else{
			$p = $c->findOne("openid = ?",array($data["openid"]));
    	}
    	Response::sendSuccess($p);
    }

    public function all(){
    	$s = new Collection("user");
    	$school = $s->findAll(" role = ? ORDER BY college", array("s_print"));
    	if($school){
	    	$c = new Collection("printshop");
	    	$ret = array();
	    	for($i = 0;$i < count($school);$i++){
                Util::logger("openid:".$school[$i]["woid"]);
                $shopinfo = $c->findOne("openid = ?",array($school[$i]["woid"]));
	    		if($shopinfo){
	    			$ret[$school[$i]["college"]][$shopinfo["displayname"]] =$shopinfo["openid"];
	    		}
	    	}
	    	Response::sendSuccess($ret);
    	}else{
    		Response::sendFailure();
    	}
    }
}
