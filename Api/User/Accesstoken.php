<?php
namespace Api\User;
use Pest\Api;

class Accesstoken extends Api
{

    public $post = array(
            'email' => '/^(\w)+(\.\w+)*@(\w)+((\.\w+)+)$/',
            'password' => '/^\S{6,}$/'
    );

    public function post ()
    {
        $c = new Collection('user');
        $data = Request::getInstance()->getData();
        $user = $c->findOne('email=? and password=?', 
                array(
                        $data['email'],
                        md5($data['password'])
                ));
        if ($user) {
            $_SESSION['uid'] = $user['id'];
            Response::sendSuccess(array(
                    'id' => $_SESSION['uid']
            ));
        } else {
            Response::sendSuccess(array(
                    'id' => 0
            ));
        }
    }
}