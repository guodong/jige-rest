<?php
namespace Api;
use Pest\Db\Collection;
use Pest\Api;
use Pest\Response;
use Pest\Request;

class Printprice extends Api
{
    public $get = array(
    		"shopid" =>'/^\S{24}$/',
    );
    
    public function get ()
    {
    	$data = Request::getInstance()->getData();
		$c = new Collection("Printprice");
		$p = $c->findOne("id = ?",array($data["shopid"]));
		if(!empty($p["p1"])&&0!=$p["p1"]){
			$ret["A4"]["黑白"]["单面"] = $p["p1"];
		}
		if(!empty($p["p2"])&&0!=$p["p2"]){
			$ret["A4"]["黑白"]["双面"] = $p["p2"];
		}
		if(!empty($p["p3"])&&0!=$p["p3"]){
			$ret["A4"]["彩色"]["单面"] = $p["p3"];
		}
		if(!empty($p["p4"])&&0!=$p["p4"]){
			$ret["A4"]["彩色"]["双面"] = $p["p4"];
		}
   	    if(!empty($p["p5"])&&0!=$p["p5"]){
			$ret["A3"]["黑白"]["单面"] = $p["p5"];
		}
		if(!empty($p["p6"])&&0!=$p["p6"]){
			$ret["A3"]["黑白"]["双面"] = $p["p6"];
		}
		if(!empty($p["p7"])&&0!=$p["p7"]){
			$ret["A3"]["彩色"]["单面"] = $p["p7"];
		}
		if(!empty($p["p8"])&&0!=$p["p8"]){
			$ret["A3"]["彩色"]["双面"] = $p["p8"];
		}
		
		if($ret){
			Response::sendSuccess($ret);
		}else{
			Response::sendFailure();
		}
		
    }

}
