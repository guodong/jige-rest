<?php
namespace Api\Oldproduct;
use Pest\Api;
use Pest\Db\Collection;
use Pest\Request;
use Pest\Response;
use Pest\Db;

class Sell extends Api
{
    public $get = array(
            'id' => '/^\S{24}$/'
    );
    
    public function get ()
    {
        $data = Request::getInstance()->getData();
        $c = new Collection('oldproduct');
        //如果没有指定，则只返回尚未出售的
        if(!isset($data["status"])){
        	$data["status"] = "0";
        }
        	$productinfo = $c->findAll('id = ? AND status = ?',
        			array(
        					$data['id'],
        					$data["status"]
        			));
        Response::sendSuccess($productinfo);
    }
    
    public $post = array(
    		//'title' => '/^\S{1,}$/',
    		//'seller_id' => '/^\S{24}$/',
    		//'price' =>'/^.{1,}/',
    );
    
    public function post ()
    {
    	$data = Request::getInstance()->getData();
    	if(isset($data["openid"])){
    		$u = new Collection('user');
    		$user = $u->findOne("woid = ?",array($data["openid"]));
    		if ($user) {
    			$data["seller_id"] = $user["id"];
    			$data["college"] = $user["college"];
    			$data["campus"] = $user["campus"];
    		}else{
    			Response::sendFailure(1003);
    			exit();
    		}
        } 
    	$c = new Collection('oldproduct');
    	if(!isset($data['status']))
    	{
    		$data['stime'] = time();
    		$data['status'] = '0';
    	}
    	$id = $c->save($data);
    	if($id){
    		Response::sendSuccess($id);
    	}else{
    		Response::sendFailure(1000);
    	}
    }
    
    public function all()
    {
        $data = Request::getInstance()->getData();
        $c = new Collection('oldproduct');
        if(!isset($data['count']))
        {
        	$data['count'] = "10";//默认返回10个
        }
        if($data['type']=='uid'){
        	if(!isset($data['status'])){
        		$sql = "SELECT o.id,o.title,o.content,o.imgpath,o.`status`,o.price,u.tel,u.college,u.campus,o.stime,o.otime FROM oldproduct AS o ,`user` AS u".
          			" WHERE o.status = 0 AND o.seller_id = u.id AND o.seller_id = '".$data['q']."'";
        	}
        	else{
        		$sql = "SELECT o.id,o.title,o.content,o.imgpath,o.`status`,o.price,u.tel,u.college,u.campus,o.stime,o.otime FROM oldproduct AS o ,`user` AS u".
          			" WHERE o.seller_id = u.id AND o.seller_id = '".$data['q']."'";
        	}
        	$ret = Db::sql($sql);
        	Response::sendSuccess($ret);
        }else if($data['type']=='openid'){
        	if(!isset($data['status'])){
        		$sql = "SELECT o.id,o.title,o.content,o.imgpath,o.`status`,o.price,u.tel,u.college,u.campus,o.stime,o.otime FROM oldproduct AS o ,`user` AS u".
          			" WHERE o.status = 0 AND o.seller_id = u.id AND u.woid = '".$data['q']."'";
        	}
        	else{
        		$sql = "SELECT o.id,o.title,o.content,o.imgpath,o.`status`,o.price,u.tel,u.college,u.campus,o.stime,o.otime FROM oldproduct AS o ,`user` AS u".
        			" WHERE o.seller_id = u.id AND u.woid = '".$data['q']."'";
        	}
        	$ret = Db::sql($sql);
        	Response::sendSuccess($ret);
        }else if($data['type']=='latest'){
        	if(isset($data['start'])){
        		$sql = "SELECT o.id,o.title, o.content, o.imgpath, u.nickname, u.campus, u.college, u.tel, o.price, o.stime FROM oldproduct AS o ,".
          		" `user` AS u WHERE o.status = 0  AND o.seller_id = u.id ORDER BY o.stime DESC LIMIT ".$data['start'].", ".$data['count'];
        	}else{
        		$sql = "SELECT o.id,o.title, o.content, o.imgpath, u.nickname, u.campus, u.college, u.tel, o.price, o.stime FROM oldproduct AS o ,".
          		" `user` AS u WHERE o.status = 0  AND o.seller_id = u.id ORDER BY o.stime DESC LIMIT 0, ".$data['count'];
        	}
        	$ret = Db::sql($sql);
        	Response::sendSuccess($ret);
        }else{
	        $result = @split('#',$data['q']);
	        $params = array();
	        foreach($result as $r ){
	        	if($r != ""){
	        		$params[] = $r;
	        	}
	        }
	        $sql = "SELECT o.id,o.title, o.content, o.imgpath, u.nickname, u.campus, u.college, u.tel, o.price, o.stime FROM oldproduct AS o ,".
          		" `user` AS u WHERE o.status = 0  AND o.seller_id = u.id AND (";
	        $flag = 0;
	        for($i = 0;$i < count($params);$i++){
	        	if($flag == 0){
	        		$sql = $sql . 'o.`title` LIKE \'%'.$params[$i].'%\'';
	        		$flag = 1;
	        	}else{
	        		$sql = $sql . ' OR o.`title` LIKE \'%'.$params[$i].'%\'';
	        	}
	        }
	        $sql = $sql.') ORDER BY o.stime DESC ';
	        if(isset($data['start'])){
	        	$sql = $sql." LIMIT ".$data['start'].", ".$data['count'];
	        }else{
	        	$sql = $sql." LIMIT 0, ".$data['count'];
	        }
	        $ret = Db::sql($sql);
	        Response::sendSuccess($ret);
        }
    }
}