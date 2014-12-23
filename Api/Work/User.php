<?php
namespace Api\Work;
use Pest\Api;
use Pest\Request;
use Pest\Db\Collection;
use Pest\Response;

class User extends Api
{
    public $post = array(
            'username' => '/^\S{1,}$/',
            'password' => '/^\S{6,}$/',
    		'type' =>'/^\S{3,}$/'
    );

    public function post ()
    {
        $data = Request::getInstance()->getData();
        $c = new Collection('work_user');
        if('new' == $data['type']){
	        $data['password'] = md5($data['password']);
	        $data['level'] = '1';
	        $data['lasttime'] = time();
	        $ret = $c->findOne('username = ?',array($data['username']));
	        if($ret){
	        	Response::sendFailure(1005);
	        	return;
	        }
	        unset($data['type']);
	        $id = $c->save($data);
	        if($id){
		        $data = $c->findOne('id=?', array($id));
		        Response::sendSuccess($data);
	        }else{
	        	Response::sendFailure(1000);
	        }
        }else if('update' == $data['type']){
        	$ret = $c->findOne('username = ? AND password = ?',array($data['username'],md5($data['password'])));
        	if($ret){
        		unset($data['type']);
        		$id = $c->save($data);
        		$data = $c->findOne('id=?', array($id));
	        	Response::sendSuccess($data);
        		return;
        	}else{
        		Response::sendFailure(1000);
        	}
        }else{
        	Response::sendFailure(1001);
        }
    }
    
    public $get = array(
    		'username' => '/^\S{1,}$/',
    		'type' =>'/^\S{3,}$/'
    );

    public function get ()
    {
    	$data = Request::getInstance()->getData();
    	$c = new Collection('work_user');
    	if('access'  == $data['type']){
    		$user = $c->findOne('username = ? AND password = ?',
    				array(
    						$data['username'],
    						md5($data['password']),
    				));
    		if ($user) {
    			//记录用户登录时间
    			$time = array();
    			$time['id'] = $user["id"];
    			$time['lasttime'] = time();
    			$c->save($time);
    			Response::sendSuccess(
    					array(
	                            'id' => $user["id"],
	                            'username' => $user["username"],
	                            'realname' => $user["realname"],
	                            'email' => $user["email"],
	                            'lasttime' => $user["lasttime"],
	                            'tel' => $user["tel"],
	                            'group' => $user["level"]
	                    ));
    		}else{
    			Response::sendFailure(1000);
    		}
    	}else if('info' == $data['type']){
	        $user = $c->findOne('id = ? AND username = ?', 
	                array(
	                        $data['id'],
	                		$data['username'],
	                ));
	        if ($user) {
	            Response::sendSuccess(
	                    array(
	                            'id' => $user["id"],
	                            'username' => $user["username"],
	                            'realname' => $user["realname"],
	                            'email' => $user["email"],
	                            'lasttime' => $user["lasttime"],
	                            'tel' => $user["tel"],
	                            'group' => $user["group"]
	                    ));
	        } else {
	            Response::sendSuccess(
	                    array(
	                            'result' => 0
	                    ));
	        }
    	}else{
    		Response::sendFailure(1001);
    	}
    }
}