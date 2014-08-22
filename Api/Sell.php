<?php
namespace Api;
use Pest\Api;
use Pest\Db\Collection;
use Pest\Request;
use Pest\Response;
use Pest\Db;
class Sell extends Api
{
    public $get = array(
            'id' => '/^\d{24}$/'
    );
    
    public function get ()
    {
        $c = new Collection('sellinfo');
        $data = Request::getInstance()->getData();
        $d = $c->findOne('id=?', array($data['id']));
        Response::sendSuccess($d);
    }
    

    public $post = array(
    		'book_id' => '/^\d{24}$/',
    		'seller_id' => '/^\d{24}$/',
    		'price' =>'/^.{1,}/',
    );
    
    public function post ()
    {
    	$c = new Collection('sellinfo');
    	$data = Request::getInstance()->getData();
    	$data['time'] = time();
    	$data['status'] = 0;
    	$id = $c->save($data);
    	Response::sendSuccess($id);
    }
    
    public function all()
    {
        $data = Request::getData();
        if($data['type']=='uid'){
        	$c = new Collection('sellinfo');
        	$data = Request::getInstance()->getData();
        	$d = $c->findAll('seller_id=?', array($data['q']));
        	Response::sendSuccess($d);
        }else{
	        $c = new Collection('sellinfo');
	        $result = @split('#',$data['q']);
	        $params = array();
	        foreach($result as $r ){
	        	if($r != ""){
	        		$params[] = $r;
	        	}
	        }
	        $sql = "SELECT si.id,si.book_id,si.seller_id,si.`status`,si.price,si.campus_id,si.contact,si.college_id,si.`describe`,si.pics,si.time".
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
    		//'id' => '/^\S{24}$/',
    );
    
    public function put()
    {
     	$c = new Collection('sellinfo');
     	$data = Request::getInstance()->getData();
     	$id = $c->update('where id=?', array($data['id']), $data);
    	Response::sendSuccess(array());
    }
}