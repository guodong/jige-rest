<?php
namespace Api\Letsgo;
use Pest\Api;
use Pest\Db\Collection;
use Pest\Request;
use Pest\Response;

class BookCar extends Api
{
    public $get = array(
    		
    );
    
    public function get ()
    {
        $data = Request::getInstance()->getData();
        $c = new Collection('letsgo_order_car');
	    $d = $c->findOne('id=?', array($data['id']));
	    Response::sendSuccess($d);
    }

    public $post = array(
    		
    );
    
    public function post ()
    {
    	$data = Request::getInstance()->getData();
    	$c = new Collection('letsgo_order_car');
    	if("new" == $data["type"]){
    		$d = $c->findOne("bookid = ? AND staffid = ?",array($data['bookid'],$data['staffid']));
    		if($d){
    			Response::sendSuccess($d["id"]);
    		}else{
    			$ret = $c->save($data);
    			if($ret){
    				Response::sendSuccess($ret);
    			}else{
    				Response::sendFailure();
    			}
    		}
    	}else if("delete" == $data["type"]){
    		$c->delete("bookid = ? AND staffid = ? ",array($data['bookid'],$data['staffid']));
    		$d = $c->findAll('staffid = ', $data["staffid"]);
        	Response::sendSuccess($d);
    	}
    }
    
    public function all()
    {
        $data = Request::getInstance()->getData();
        $c = new Collection('letsgo_order_car');
        $d = $c->findAll('staffid = ', $data["staffid"]);
        Response::sendSuccess($d);
    }
}