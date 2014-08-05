<?php
namespace Api;
use Pest\Api;

class User extends Api
{

    public $post = array(
            'email' => '/^(\w)+(\.\w+)*@(\w)+((\.\w+)+)$/',
            'password' => '/^\S{6,}$/',
            'captcha' => '/^\d{4}$/'
    );

    public function post ()
    {
        $data = Request::getInstance()->getData();
        $data['password'] = md5($data['password']);
        $c = new Collection('user');
        $id = $c->save($data);
        $_SESSION['uid'] = $id;
        Response::sendSuccess(array(
                'id' => $id
        ));
    }

    public $get = array(
            'id' => '/^\S{24}$/'
    );

    public function get ()
    {
        $c = new Collection('user');
        $data = Request::getInstance()->getData();
        $user = $c->findOne('id = ?', array(
                $data['id']
        ));
        if ($user) {
            Response::sendSuccess(
                    array(
                            'id' => $user["id"],
                            'name' => $user["name"],
                            'tel' => $user["tel"],
                            'realname' => $user["realname"],
                            'nickname' => $user["nickname"],
                            'role' => $user["role"],
                            'is_verified' => $user["is_verified"],
                            'social' => $user["social"],
                            'campus_id' => $user["campus_id"],
                            'config' => $user["config"]
                    ));
        } else {
            Response::sendSuccess(array(
                    'result' => 0
            ));
        }
    }
}