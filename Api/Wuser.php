<?php
namespace Api;
use Pest\Api;
use Pest\Request;
use Pest\Db\Collection;
use Pest\Response;
use Pest\Db;
use Pest\Util;

class Wuser extends Api
{

    public $post = array(
           // 'openid' => '/^.{28}$/',
    		//'type' =>'/^\S{3,}$/',
    );

    public function post ()
    {
        $data = Request::getInstance()->getData();
        if("new" == $data['type']){
        	$data['woid'] = $data['openid'];
        	$data['regtime'] = time();
        	$data['role'] = 'normal';
        	$data['is_verified'] = 0;
        	unset($data['openid']);
        	$c = new Collection('user');
        	$ret = $c->findOne('woid = ?',array($data['woid']));
        	if($ret){
        		Response::sendFailure(1005);
        		return;
        	}
        	$id = $c->save($data);
        	if(!$id){
        		Response::sendFailure(1000);
        		return;
        	}
        	$data = $c->findOne('id=?', array($id));
        	if($data){
        		Response::sendSuccess($data);
        		return;
        	}else{
        		Response::sendFailure(1000);
        		return;
        	}
        }else if("update" == $data['type']){
        	$data['woid'] = $data['openid'];
        	unset($data['openid']);
        	$c = new Collection('user');
        	$ret = $c->findOne('woid = ?',array($data['woid']));
        	if($ret){
	        	$data['id'] = $ret['id'];
	        	$id = $c->save($data);
	        	$newdata = $c->findOne('id=?', array($id));
	        	Response::sendSuccess($newdata);
        	}else{
        		Response::sendFailure(1003);
        		return;
        	}
        }else if("subscribe" == $data['type']){
        	$data['woid'] = $data['openid'];
        	unset($data['openid']);
        	$c = new Collection('user');
        	$ret = $c->findOne('woid = ?',array($data['woid']));
        	if($ret){
	        	$data['id'] = $ret['id'];
	        	if(empty($ret['config'])){
	        		$id = $c->save($data);
	        		$newdata = $c->findOne('id=?', array($id));
	        		Response::sendSuccess($newdata);
	        	}else{
	        		Response::sendSuccess($newdata);
	        	}
        	}else{
        		Response::sendFailure(1003);
        		return;
        	}
        }else{
        	Response::sendFailure(1000);
        	return;
        }
    }

    public $get = array(
            'openid' => '/^\S{28}$/'
    );

    public function get ()
    {
        $c = new Collection('user');
        $data = Request::getInstance()->getData();
        $user = $c->findOne('woid = ?', 
                array(
                        $data['openid']
                ));
        if ($user) {
        	if("count" == $data["type"]){
        		if(!isset($data["status"])){
	        		$sql = "SELECT COUNT(id) AS count FROM sellinfo WHERE status = 0 AND seller_id = '".$user["id"]."'";
	        		$ret1 = Db::sql($sql);
	        		$sql = "SELECT COUNT(id) AS count FROM oldproduct WHERE status = 0 AND seller_id = '".$user["id"]."'";
	        		$ret2 = Db::sql($sql);
        		}else{
        			$sql = "SELECT COUNT(id) AS count FROM sellinfo WHERE seller_id = '".$user["id"]."'";
        			$ret1 = Db::sql($sql);
        			$sql = "SELECT COUNT(id) AS count FROM oldproduct WHERE seller_id = '".$user["id"]."'";
        			$ret2 = Db::sql($sql);
        		}
        		Response::sendSuccess(
	        		array(
		        		"oldbook" =>$ret1[0]["count"],
		        		"oldproduct" => $ret2[0]["count"]
	        		)
        		);
        	}else{
            	Response::sendSuccess(
                    array(
                            'id' => $user["id"],
                            'name' => $user["name"],
                            'tel' => $user["tel"],
                            'realname' => $user["realname"],
                            'nickname' => $user["nickname"],
                            'role' => $user["role"],
                            'is_verified' => $user["is_verified"],
                            'social' => $user["social"],
                            'college' => $user["college"],
                            'campus' => $user["campus"],
                            'config' => $user["config"]
                    ));
        	}
        } else {
            Response::sendSuccess(
                    array(
                            'result' => 0
                    ));
        }
    }
}