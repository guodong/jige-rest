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
        $id = $c->save($data);
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