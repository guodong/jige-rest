<?php
namespace Api;
use Pest\Db\Collection;
use Pest\Db;
use Pest\Api;
use Pest\Response;
use Pest\Request;
use Pest\Util;

class Printprice extends Api
{

    public $post = array(
            
    );

    public function post ()
    {
    	$data = Request::getInstance()->getData();
    }
    
    public $get = array(
    		"shopid" =>'/^\S{24}$/',
    );
    
    public function get ()
    {
    	$data = Request::getInstance()->getData();
		$c = new Collection("Printprice");
		$p = $c->findOne("id = ?",array($data["shopid"]));
		$ret = array(
				"A4" => array(
						"黑白" => array(
								"单面" => $p["p1"],
								"双面" => $p["p2"],
    					),
						"彩色" => array(
								"单面" => $p["p3"],
								"双面" => $p["p4"],
    					),
				),
				"A3" => array(
						"黑白" => array(
								"单面" => $p["p5"],
								"双面" => $p["p6"],
    					),
						"彩色" => array(
								"单面" => $p["p7"],
								"双面" => $p["p8"],
    					),
				),
		);
		Response::sendSuccess($ret);
    }

}
