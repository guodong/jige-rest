<?php
namespace Api\User;
use Pest\Api;
use Pest\Response;
use Pest\Db\Collection;
use Pest\Request;
//根据ID获取某个用户的详细信息
class Get extends Api
{

    public $get = array(
            'id' => '/^\S{24}$/',
    );

    public function get ()
    {
        $c = new Collection('user');
        $data = Request::getInstance()->getData();
        $user = $c->findOne('id = ?',array($data['id']));
        if ($user){
        	Response::sendSuccess(array(
	        	'id' => $user["id"],
	        	'name' => $user["name"],
	        	'tel' => $user["tel"],
	        	'realname' => $user["realname"],
	        	'nickname' => $user["nickname"],
	        	'role' =>$user["role"],
	        	'is_verified' => $user["is_verified"],
	        	'social' => $user["social"],
	        	'campus_id' => $user["campus_id"],
	        	'config' => $user["config"]
        	));
        }else {
        	Response::sendSuccess(array('result'=>0));
        }
    }
}