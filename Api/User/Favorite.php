<?php
namespace Api\User;
use Pest\Api;
use Pest\Db\Collection;
use Pest\Request;
use Pest\Response;

class Favorite extends Api
{

    public $post = array(
            'user_id' => '/^\S{24}$/',
            'sell_id' => '/^\S{24}$/'
    );

    public function post ()
    {
        $c = new Collection('favorite');
        $data = Request::getInstance()->getData();
        $id = $c->save($data);
        Response::sendSuccess(array(
                'id' => $id
        ));
    }
    
    public $get = array(
    	'id' => '/^\S{24}$/'
    );
    
    public function all()
    {
        $c = new Collection('favorite');
        $data = $c->findAll('user_id = ?', array(Request::getData('user_id')));
        Response::sendSuccess($data);
    }
    
    public $delete = array(
    	'id' => '/^\S{24}$/'
    );
    
    public function delete()
    {
        $c = new Collection('favorite');
        $c->delete('id=?', array(Request::getData('id')));
        Response::sendSuccess(array());
    }
}