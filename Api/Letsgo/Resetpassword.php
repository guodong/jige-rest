<?php
namespace Api\Letsgo;
use Pest\Api;
use Pest\Db\Collection;
use Pest\Request;
use Pest\Response;

class Resetpassword extends Api
{
    public $post = array(
    		'staffid' => '/^\S{1,}$/',
    		'tel' => '/^\S{1,}$/',
    		'password'=>'/^\S{1,}$/',
    );
    
    public function post ()
    {
    	$data = Request::getInstance()->getData();
    	$c = new Collection('letsgo_staff');
    	$ret = $c->findOne("staffid = ? AND telephone = ?",array($data['staffid'],$data['tel']));
    	if($ret){
    		$ret['password'] = $data['password'];
    		$p = $c->save($ret);
    		if($p){
    			Response::sendSuccess($p);
    		}else{
    			Response::sendFailure();
    		}
    	}else{
    		Response::sendFailure(1006);
    	}
    }
}