<?php
namespace Api;
use Pest\Api;
use Pest\Db\Collection;
use Pest\Request;
use Pest\Response;

class Wsell extends Api
{
    public $get = array(
            'openid' => '/^\S{28}$/'
    );

    public function get ()
    {
    	$data = Request::getInstance()->getData();
    	$u = new Collection('user');
    	$userinfo = $u->findOne('woid=?', array($data['openid']));
        $c = new Collection('sellinfo');
        $d = $c->findOne('id=?', array($userinfo['id']));
        Response::sendSuccess($d);
    }

    public $post = array(
    		'book_id' => '/^\S{24}$/',
    		'openid' => '/^\S{28}$/',
    		'price' =>'/^.{1,}/',
    );
    
    public function post ()
    {
    	$data = Request::getInstance()->getData();
    	$u = new Collection('user');
    	$userinfo = $u->findOne('woid=?', array($data['openid']));
    	unset($data['openid']);
    	if($userinfo){
	    	$c = new Collection('sellinfo');
	    	$data['seller_id'] = $userinfo['id'];
	    	$data['stime'] = time();
	    	$data['status'] = '0';
	    	$id = $c->save($data);
	    	if($id){
	    		Response::sendSuccess($id);
	    	}else{
	    		Response::sendFailure(1000);
	    	}
    	}else{
    		Response::sendFailure(1000);
    	}
    }
    
    public function all()
    {
        $data = Request::getData();
        $u = new Collection('user');
        $userinfo = $u->findOne('woid=?', array($data['openid']));
        if($userinfo){
	        $c = new Collection('sellinfo');
	        $data = Request::getInstance()->getData();
	        $d = $c->findAll('seller_id=?', array($userinfo['id']));
	        Response::sendSuccess($d);
        }else{
        	Response::sendFailure(1000);
        }
    }
}