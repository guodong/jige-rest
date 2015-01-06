<?php
namespace Api\Letsgo;
use Pest\Api;
use Pest\Db\Collection;
use Pest\Request;
use Pest\Response;
use Pest\Db;
use Pest\Util;

class Order extends Api
{
    public $get = array(
    		'id' => '/^\S{24}$/',
    );
    
    public function get ()
    {
        $data = Request::getInstance()->getData();
        $c = new Collection('letsgo_order');
	    $d = $c->findOne('id=?', array($data['id']));
	    Response::sendSuccess($d);
    }

    public $post = array(
    		'ordername' => '/^\S{1,}$/',
    		'staffid' =>'/^\S{1,}$/',
    );
    
    public function post ()
    {
    	$data = Request::getInstance()->getData();
    	$c = new Collection('letsgo_order');
    	$temp = array(
    			"staffId" => $data["staffid"],
    			"orderName" => $data["ordername"],
    			"address" => $data["place"],
    			"remark" => $data["mark"],
    			"orderTime" =>time(),
    			"status" => "0",
    	);
    	$orderid = $c->save($temp);
    	if(!$orderid){
    		Response::sendFailure();
    		return;
    	}else{
    		$d = new Collection('letsgo_order_book');
    		$obj = json_decode($data["details"]);
    		for($i = 0;$i < count($obj);$i++){
                list($bookid, $count) = explode('|', $obj[$i]);
                $bookid = substr($bookid,6);
                $orderinfo = array(
                    'orderid' => $orderid,
                    'bookid' => $bookid,
                    'count' => $count,
                    'status' => '0',
                );
                $d->save($orderinfo);
            }
    		Response::sendSuccess($orderid);
    	}
    }
    
    public function all(){
    	$data = Request::getInstance()->getData();
    	$c = new Collection('letsgo_order');
    	$ret = $c->findAll("staffid = ?", array($data['staffid']));
    	if($ret){
    		Response::sendSuccess($ret);
    	}else{
    		Response::sendFailure();
    	}
    }
}
