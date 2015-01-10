<?php
namespace Api\Letsgo;
use Pest\Api;
use Pest\Db\Collection;
use Pest\Request;
use Pest\Response;

class User extends Api
{
    public $get = array(
    		'staffid' => '/^\S{1,}$/',
    );
    
    public function get ()
    {
        $data = Request::getInstance()->getData();
        $c = new Collection('letsgo_staff');
	    $d = $c->findOne('staffid=?', array($data['staffid']));
	    Response::sendSuccess($d);
    }

    public $post = array(
    		//'staffid' => '/^\S{1,}$/',
    );
    
    public function post ()
    {
    	$data = Request::getInstance()->getData();
    	$c = new Collection('letsgo_staff');
    	$maxid = $c->findOne("1 = ? ORDER BY staffid DESC",array("1"));
    	$data["staffid"] = intval($maxid["staffid"])+1;
    	$data["roleType"] ="level0";
    	$date["registerTime"] =  date('Y-m-d H:i:s',time());
    	$data["level"] ="0.4";
    	$ret = $c->save($data);
    	if($ret){
        	Response::sendSuccess(array(
	        	"name" =>$data["name"],
	        	"staffid" =>$data["staffid"],
        	));
    	}else{
    		Response::sendFailure();
    	}
    }
}
