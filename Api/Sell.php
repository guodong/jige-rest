<?php
namespace Api;
use Pest\Api;
use Pest\Db\Collection;
use Pest\Request;
use Pest\Response;
class Sell extends Api
{
    public $get = array(
            'id' => '/^\d{24}$/'
    );
    
    public function get ()
    {
        $c = new Collection('sellinfo');
        $data = Request::getInstance()->getData();
        $d = $c->findOne('id=?', array($data['id']));
        Response::sendSuccess($d);
    }
    
    public $post = array(
    		'book_id' => '/^\d{24}$/',
    		'seller_id' => '/^\d{24}$/',
    		'price' =>'/^.{1,}/',
    );
    
    public function post ()
    {
    	$c = new Collection('sellinfo');
    	$data = Request::getInstance()->getData();
    	$data['time'] = time();
    	$data['status'] = 0;
    	$id = $c->save($data);
    	Response::sendSuccess($id);
    }
    
}