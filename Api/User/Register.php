<?php
namespace Api\User;
use Pest\Api;
use Pest\Request;
use Pest\Db\Collection;
use Pest\Response;
class Register extends Api
{
    public $_post = array(
            'email' => '/^(\w)+(\.\w+)*@(\w)+((\.\w+)+)$/',
            'password' => '/^\S{6,}$/',
            'realname' => '/^.{1,15}$/',
    );
    
    public function post()
    {
        $data = Request::getInstance()->getData();
        $data['password'] = md5($data['password']);
        Collection::get('user')->insert($data);
        Response::send(array('result'=>0));
    }
}