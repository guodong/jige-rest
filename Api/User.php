<?php
namespace Api;
use Pest\Api;
use Pest\Request;
use Pest\Db\Collection;
use Pest\Response;

class User extends Api
{

    public $post = array(
            'email' => '/^(\w)+(\.\w+)*@(\w)+((\.\w+)+)$/',
            'password' => '/^\S{6,}$/'
    );

    public function post ()
    {
        $data = Request::getInstance()->getData();
        $data['password'] = md5($data['password']);
        $data['regtime'] = time();
        $data['role'] = 'normal';
        $data['is_verified'] = 0;
        $c = new Collection('user');
        $ret = $c->findOne('email = ?',array($data['email']));
        if($ret){
        	Response::sendFailure(1005);
        	return;
        }
        $id = $c->save($data);
        $data = $c->findOne('id=?', array($id));
        Response::sendSuccess($data);
    }
    
    public $put = array(
            'id' => '/^\S{24}$/',
            //'nickname' => '/.{1,}/',
    );
   
   public function put()
   {
   		$data = Request::getInstance()->getData();
   		$c = new Collection('user');
   		$ret = $c->findOne('id=?'  ,
   				array(
                        $data['id']
                ));
   		if($ret)	{
   			$id = $c->save($data);
   			if($id){
	   			Response::sendSuccess(array(
	                'id' => $id
		        ));
   			}else{
   				Response::sendFailure(1004);
   			}
   		}else{
   			Response::sendFailure(1003);
   		}  		
   }

    public $get = array(
            'id' => '/^\S{24}$/'
    );

    public function get ()
    {
        $c = new Collection('user');
        $data = Request::getInstance()->getData();
        $user = $c->findOne('id = ?', 
                array(
                        $data['id']
                ));
        if ($user) {
            Response::sendSuccess($user);
        } else {
            Response::sendSuccess(
                    array(
                            'result' => 0
                    ));
        }
    }


}
