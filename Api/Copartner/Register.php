<?php
namespace Api\Copartner;
use Pest\Api;
use Pest\Request;
use Pest\Db\Collection;
use Pest\Response;
class Register extends Api
{
    public $post = array(
            'name' => '/^\S{1,}$/',
            'password' => '/^\S{6,}$/'
    );
    
    public function post()
    {
        $data = Request::getInstance()->getData();
        $data['password'] = md5($data['password']);
        $c = new Collection('copartner');
        $id = $c->save($data);
        $_SESSION['uid'] = $id;
        Response::sendSuccess(array('id'=>$id));
    }
}