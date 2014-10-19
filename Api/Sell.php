<?php
namespace Api;
use Pest\Api;
use Pest\Db\Collection;
use Pest\Request;
use Pest\Response;
use Pest\Db;
use Pest\Util;
class Sell extends Api
{
    public $get = array(
            'id' => '/^\S{24}$/'
    );
    
    public function get ()
    {
        $c = new Collection('sellinfo');
        $data = Request::getInstance()->getData();
        $d = $c->findOne('id=?', array($data['id']));
        Response::sendSuccess($d);
    }
    

    public $post = array(
    		'book_id' => '/^\S{24}$/',
    		'seller_id' => '/^\S{24}$/',
    		'price' =>'/^.{1,}/',
    );
    
    public function post ()
    {
    	$c = new Collection('sellinfo');
    	$data = Request::getInstance()->getData();
    	$data['stime'] = time();
    	$data['status'] = '0';
    	$id = $c->save($data);
    	if($id){
    		Response::sendSuccess($id);
    	}else{
    		Response::sendFailure(1000);
    	}
    }
    
    public function all()
    {
        $data = Request::getData();
        if($data['type']=='uid'){
        	$c = new Collection('sellinfo');
        	$data = Request::getInstance()->getData();
        	$d = $c->findAll('seller_id=?', array($data['q']));
        	Response::sendSuccess($d);
        }else if($data['type']=='latest'){
        	if(isset($data['start'])){
        		$sql = "SELECT bi.imgpath, si.id,si.book_id,si.seller_id,si.`status`,bi.fixedPrice AS `fixedprice`,bi.author,bi.press,bi.name,si.price,si.off,si.college,si.contact,si.`des`,si.pics,si.stime".
        				" FROM bookinfo AS bi ,sellinfo AS si WHERE bi.id = si.book_id ORDER BY stime DESC LIMIT ".$data['start'].",".$data['count'];
        	}else{
        		$sql = "SELECT bi.imgpath, si.id,si.book_id,si.seller_id,si.`status`,bi.fixedPrice AS `fixedprice`,bi.author,bi.press,bi.name,si.price,si.off,si.college,si.contact,si.`des`,si.pics,si.stime".
        				" FROM bookinfo AS bi ,sellinfo AS si WHERE bi.id = si.book_id ORDER BY stime DESC LIMIT 0,".$data['count'];
        	}
	        $ret = Db::sql($sql);
	        Response::sendSuccess($ret);
        }
        else{
	        $result = @split('#',$data['q']);
	        $params = array();
	        foreach($result as $r ){
	        	if($r != ""){
	        		$params[] = $r;
	        	}
	        }
	        $sql = "SELECT bi.imgpath, si.id,si.book_id,si.seller_id,si.`status`,bi.fixedPrice AS `fixedprice`,bi.author,bi.press,bi.name,si.price,si.off,si.college,si.contact,si.`des`,si.pics,si.stime".
	          " FROM bookinfo AS bi ,sellinfo AS si WHERE bi.id = si.book_id AND (";
	        $flag = 0;
	        for($i = 0;$i < count($params);$i++){
	        	if($flag == 0){
	        		$sql = $sql . 'bi.`search` LIKE \'%'.$params[$i].'%\'';
	        		$flag = 1;
	        	}else{
	        		$sql = $sql . ' OR bi.`search` LIKE \'%'.$params[$i].'%\'';
	        	}
	        }
	        $sql = $sql.')';
	        $ret = Db::sql($sql);
	        Response::sendSuccess($ret);
        }
    }
    
    public $put = array(
    		'id' => '/^\S{24}$/',
    );
    
    public function put()
    {
     	$c = new Collection('sellinfo');
     	$data = Request::getInstance()->getData();
     	$id = $c->save($data);
    	Response::sendSuccess($id);
    }
}