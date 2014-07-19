<?php
namespace Api\User;
use Pest\Api;
use Pest\Response;
use Pest\Db\Collection;
use Pest\Request;

class Get extends Api
{

    public $get = array(
            'id' => '/^\S{1}$/',
    );

    public function get ()
    {
        $c = new Collection('user');
        //$u = $c->findOne('id=? and uid=?', array(1,2));
        //var_dump($u);
        
        $u = $c->save(array('tel'=>121233));
        var_dump($u);
        return Response::send(array('id'=>'12312'));
        
        $cuser = Collection::get('user');
        $user = $cuser->findOne(array('_id'=> new \MongoId(Request::getInstance()->getData('id'))));
        Response::send(array('id'=>(string)$user['_id'], 'realname'=>$user['realname'], 'email'=>$user['email']));
    }
}