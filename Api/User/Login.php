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

    public $get = array()

    ;

    public function post ()
    {
        $c = new Collection('user');
        $data = Request::getInstance()->getData();
        $user = $c->findOne('email=? and password=?', array($data['email'], md5($data['password'])));
        if ($user){
            $_SESSION['uid'] = $user['id'];
            Response::sendSuccess(array('id'=>$_SESSION['uid']));
        }else {
            Response::sendSuccess(array('id'=>1));
        }
        
    }

    public function get ()
    {
        $rt = isset($_SESSION['uid'])?1:0;
        Response::sendSuccess(array(
                'result' => $rt
        ));
    }
}