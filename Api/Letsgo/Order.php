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
        $sql="SELECT b.`name`,b.author,b.press,b.edition,b.fixedPrice,o.count FROM letsgo_order_book AS o ,bookinfo AS b".
          " WHERE o.bookid = b.id AND o.orderid = '".$data['id']."'";
        $d = Db::sql($sql);
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
    	//如果已有订单，则表明重复下单，给用户提示需要修改
    	$ret = $c -> findOne("staffid = ? AND ordername = ? AND place = ? AND mark = ? ",array(
    			$data["staffid"],
    			$data["ordername"],
    			$data["place"],
    			$data["mark"],
    	));
    	if($ret){
    		Response::sendFailure(1005);
    		return;
    	}
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
