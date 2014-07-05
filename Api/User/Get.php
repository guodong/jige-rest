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
        $cuser = Collection::get('user');
        $user = $cuser->findOne(array('_id'=> new \MongoId(Request::getInstance()->getData('id'))));
        Response::send(array('id'=>(string)$user['_id'], 'realname'=>$user['realname'], 'email'=>$user['email']));
    }
}