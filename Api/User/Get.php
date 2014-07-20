<?php
namespace Api\User;
use Pest\Api;
use Pest\Response;
use Pest\Db\Collection;
use Pest\Request;

class Get extends Api
{

    public $get = array(
            'id' => '/^\S{24}$/',
    );

    public function get ()
    {
        $c = new Collection('user');
        $data = Request::getInstance()->getData();
        $user = $c->findOne('id=?', array($data['id']));
        if ($user){
        	Response::sendSuccess(array(
	        	'id' => $user["id"],
	        	'name' => $user["name"],
	        	'tel' => $user["tel"]
        	));
        }else {
        	Response::sendSuccess(array('result'=>0));
        }
    }
}