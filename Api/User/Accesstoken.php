<?php
namespace Api\User;
use Pest\Api;
use Pest\Db\Collection;
use Pest\Request;
use Pest\Response;

class Accesstoken extends Api
{

    public $post = array(
            'email' => '/^.{1,}$/',
            'password' => '/^.{1,}$/'
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
            Response::sendSuccess($user);
        } else {
            Response::sendSuccess(array(
                    'id' => 0
            ));
        }
    }
}