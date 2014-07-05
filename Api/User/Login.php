<?php
namespace Api\user;
use Pest\Api;
use Pest\Response;
use Pest\Db\Collection;
use Pest\Request;

class Login extends Api
{

    public $post = array(
            'email' => '/^(\w)+(\.\w+)*@(\w)+((\.\w+)+)$/',
            'password' => '/^\S{6,}$/'
    );

    public function post ()
    {
        $cuser = Collection::get('user');
        $user = $cuser->findOne(
                array(
                        'email' => Request::getInstance()->getData('email'),
                        'password' => md5(Request::getInstance()->getData('password'))
                ));
        if ($user) {
            $data = array(
                    'id' => (string) $user['_id'],
                    'email' => $user['email'],
                    'realname' => $user['realname'],
                    'role' => $user['role']
            );
            Response::send($data);
        } else {
            Response::send(array('result'=>1, 'msg'=>'no user'));
        }
    }
}