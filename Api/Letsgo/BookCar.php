<?php
namespace Api\Letsgo;
use Pest\Api;
use Pest\Db\Collection;
use Pest\Request;
use Pest\Response;
use Pest\Db;

class Bookcar extends Api
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
    	$sql = "SELECT * FROM bookinfo,letsgo_order_car WHERE bookinfo.id = letsgo_order_car.bookid AND letsgo_order_car.staffid = '".$data["staffid"]."'";
    	if("new" == $data["type"]){
    		$d = $c->findOne("bookid = ? AND staffid = ?",array($data['bookid'],$data['staffid']));
    		if($d){
    			$d =Db::sql($sql);
        		Response::sendSuccess($d);
    		}else{
    			$ret = $c->save($data);
    			if($ret){
    				$d =Db::sql($sql);
        			Response::sendSuccess($d);
    			}else{
    				Response::sendFailure();
    			}
    		}
    	}else if("delete" == $data["type"]){
    		$c->delete("bookid = ? AND staffid = ? ",array($data['bookid'],$data['staffid']));
    		$d =Db::sql($sql);
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