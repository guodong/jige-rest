<?php
namespace Api\Letsgo;
use Pest\Api;
use Pest\Db\Collection;
use Pest\Request;
use Pest\Response;

class Login extends Api
{
    public $post = array(
    		'staffid' => '/^\S{1,}$/',
    		'password'=>'/^\S{1,}$/',
    );
    
    public function post ()
    {
    	$data = Request::getInstance()->getData();
    	$c = new Collection('letsgo_staff');
    	$ret = $c->findOne("(staffid = ? OR telephone = ?  OR email = ? ) AND password = ?",array($data['staffid'],$data['staffid'],$data['staffid'],$data['password']));
    	if($ret){
        	Response::sendSuccess($ret);
    	}else{
    		Response::sendFailure();
    	}
    }
}