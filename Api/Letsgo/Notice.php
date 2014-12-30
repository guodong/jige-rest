<?php
namespace Api\Letsgo;
use Pest\Api;
use Pest\Db\Collection;
use Pest\Request;
use Pest\Response;

class Notice extends Api
{
    public $get = array(
    		
    );
    
    public function get ()
    {
        $data = Request::getInstance()->getData();
        $c = new Collection('letsgo_notice');
	    $d = $c->findOne('id=?', array($data['id']));
	    Response::sendSuccess($d);
    }

    public $post = array(
    );
    
    public function post ()
    {
    }
    
    public function all()
    {
        $data = Request::getInstance()->getData();
        $c = new Collection('letsgo_notice');
        $d = $c->findAll('ORDER BY releaseTime DESC', null);
        Response::sendSuccess($d);
    }
}