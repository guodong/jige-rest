<?php
namespace Api\Letsgo;
use Pest\Api;
use Pest\Db\Collection;
use Pest\Request;
use Pest\Response;

class BookCar extends Api
{
    public $get = array(
    		'id' => '/^\S{24}$/',
    );
    
    public function get ()
    {
        $data = Request::getInstance()->getData();
        $c = new Collection('letsgo_order_car');
	    $d = $c->findOne('id=?', array($data['id']));
	    Response::sendSuccess($d);
    }

    public $post = array(
    		'bookid' => '/^\S{24}$/',
    		'staffid' => '/^\S{1,}$/',
    );
    
    public function post ()
    {
    	$data = Request::getInstance()->getData();
    	$c = new Collection('letsgo_order_car');
    	if("new" == $data["type"]){
    		$d = $c->findOne("bookid = ? AND staffid = ?",array($data['bookid'],$data['staffid']));
    		if($d){
    			$d = $c->findAll('staffid = ', $data["staffid"]);
        		Response::sendSuccess($d);
    		}else{
    			$ret = $c->save($data);
    			if($ret){
    				$d = $c->findAll('staffid = ', $data["staffid"]);
        			Response::sendSuccess($d);
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