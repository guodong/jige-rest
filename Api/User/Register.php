<?php
namespace Api\User;
use Pest\Api;
use Pest\Request;
use Pest\Db\Collection;
use Pest\Response;
//新用户注册
class Register extends Api
{
    public $post = array(
            'email' => '/^(\w)+(\.\w+)*@(\w)+((\.\w+)+)$/',
            'password' => '/^\S{6,}$/',
            'captcha' => '/^\d{4}$/'
    );
    
    public function post()
    {
        $data = Request::getInstance()->getData();
        $data['password'] = md5($data['password']);
        $c = new Collection('user');
        $id = $c->save($data);
        $_SESSION['uid'] = $id;
        Response::sendSuccess(array('id'=>$id));
    }
}