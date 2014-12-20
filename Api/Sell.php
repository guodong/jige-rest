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
            //'id' => '/^\S{24}$/'
    );
    
    public function get ()
    {
        $data = Request::getInstance()->getData();
        if(!isset($data['college'])||!isset($data['campus'])){
        	$area = " ";
        }else{
        	$area = " AND u.college = '".$data['college']."' AND u.campus = '".$data['campus']."'";
        }
        if($data['type'] == 'bookid'){
        	$sql = "SELECT si.price AS price,u.tel AS tel,u.nickname AS nickname,u.college AS college,u.campus AS campus,si.off AS off 	FROM sellinfo AS si ,".
        	"`user` AS u WHERE si.status='0'  ".$area." AND  si.seller_id = u.id AND si.book_id = '".$data['bookid']."' ORDER BY si.stime DESC";
        	$ret = Db::sql($sql);
	        Response::sendSuccess($ret);
        }else{
        	$c = new Collection('sellinfo');
	        $d = $c->findOne('id=?', array($data['id']));
	        Response::sendSuccess($d);
        }
    }

    public $post = array(
    		/*'book_id' => '/^\S{24}$/',
    		'seller_id' => '/^\S{24}$/',
    		'price' =>'/^.{1,}/',*/
    );
    
    public function post ()
    {
    	$data = Request::getInstance()->getData();
    	$u = new Collection('user');
    	$userinfo = $u->findOne('id=?', array($data['seller_id']));
    	if($userinfo){
    		$data['college'] = $userinfo['college'].$userinfo['campus'];
    		$data['contact'] = $userinfo['tel'];
	    	$b = new Collection('bookinfo');
	    	$bookinfo = $b->findOne('id = ?',array($data['book_id']));
	    	$data['off'] = round($data['price']*10/$bookinfo['fixedPrice'],1);
	    	if(!isset($data['status']))
	    	{
	    		$data['stime'] = time();
	    		$data['status'] = '0';
	    	}
	    	$c = new Collection('sellinfo');
	    	$id = $c->save($data);
	    	if($id){
	    		Response::sendSuccess($id);
	    	}else{
	    		Response::sendFailure(1000);
	    	}
    	}else{
    		Response::sendFailure(1000);
    	}
    }
    
    public function all()
    {
        $data = Request::getData();
        if(!isset($data['college'])||!isset($data['campus'])){
        	$area = " ";
        }else{
        	$area = " AND u.college = '".$data['college']."' AND u.campus = '".$data['campus']."'";
        }
        if($data['type']=='uid'){
        	$c = new Collection('sellinfo');
        	$data = Request::getInstance()->getData();
        	$d = $c->findAll('seller_id=?', array($data['q']));
        	Response::sendSuccess($d);
        }else if($data['type']=='latest'){
        	if(!isset($data['count'])){
        		$data['count'] = '10';
        	}
        	if(isset($data['start'])){
        		$sql = "SELECT bi.imgpath,u.nickname, si.id,si.book_id,si.seller_id,si.`status`,bi.fixedPrice AS `fixedprice`,bi.author,bi.press,bi.name,si.price,si.off,u.college,u.campus,u.tel,si.`des`,si.pics,si.stime".
        				" FROM bookinfo AS bi ,sellinfo AS si ,user AS u WHERE si.status='0' ".$area." AND bi.id = si.book_id AND si.seller_id = u.id ORDER BY stime DESC LIMIT ".$data['start'].",".$data['count'];
        	}else{
        		$sql = "SELECT bi.imgpath,u.nickname, si.id,si.book_id,si.seller_id,si.`status`,bi.fixedPrice AS `fixedprice`,bi.author,bi.press,bi.name,si.price,si.off,u.college,u.campus,u.tel,si.`des`,si.pics,si.stime".
        				" FROM bookinfo AS bi ,sellinfo AS si ,user AS u WHERE si.status='0'  ".$area." AND bi.id = si.book_id AND si.seller_id = u.id ORDER BY stime DESC LIMIT 0,".$data['count'];
        	}
        	$ret = Db::sql($sql);
        	Response::sendSuccess($ret);
        }else if($data['type']=='group'){
        	if(!isset($data['count'])){
        		$data['count'] = '100';
        	}
			if(isset($data['q'])){
				$result = @split('#',$data['q']);
				$params = array();
				foreach($result as $r ){
					if($r != ""){
						$params[] = $r;
					}
				}
				$sql = "SELECT bi.id AS bookid,bi.`name` AS `name`,bi.author AS author,bi.press AS press,bi.fixedPrice AS fixedPrice,bi.imgpath AS imgpath,Count(si.book_id) AS count FROM bookinfo AS bi ,".
						"sellinfo AS si ,user AS u WHERE  si.seller_id = u.id AND si.status='0'  ".$area." AND si.book_id = bi.id AND (";
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
				
				if(isset($data['start'])){
					$sql = $sql." GROUP BY si.book_id ORDER BY bi.id ASC LIMIT ".$data['start'].",".$data['count'];
				}else{
					$sql = $sql." GROUP BY si.book_id ORDER BY bi.id ASC LIMIT 0,".$data['count'];
				}
			}else{
	        	if(isset($data['start'])){
	        		$sql = "SELECT bi.id AS bookid,bi.`name` AS `name`,bi.author AS author,bi.press AS press,bi.fixedPrice AS fixedPrice,bi.imgpath AS imgpath,Count(si.book_id) AS count FROM bookinfo AS bi ,".
							"sellinfo AS si ,user AS u WHERE si.seller_id = u.id AND  si.status='0'  ".$area." AND si.book_id = bi.id GROUP BY si.book_id ORDER BY Count(si.book_id) DESC LIMIT ".$data['start'].",".$data['count'];
	        	}else{
	        		$sql = "SELECT bi.id AS bookid,bi.`name` AS `name`,bi.author AS author,bi.press AS press,bi.fixedPrice AS fixedPrice,bi.imgpath AS imgpath,Count(si.book_id) AS count FROM bookinfo AS bi ,".
							"sellinfo AS si ,user AS u WHERE si.seller_id = u.id AND  si.status='0'  ".$area." AND si.book_id = bi.id GROUP BY si.book_id ORDER BY Count(si.book_id) DESC LIMIT 0,".$data['count'];
	        	}
			}
	        $ret = Db::sql($sql);
	         Response::sendSuccess($ret);
        }else{
        	if(!isset($data['q'])||$data['q'] == ""||$data['q'] == null){
        		$sql = "SELECT bi.imgpath,u.nickname, si.id,si.book_id,si.seller_id,si.`status`,bi.fixedPrice AS `fixedprice`,bi.author,bi.press,bi.name,si.price,si.off,u.college,u.campus,u.tel,si.`des`,si.pics,si.stime".
		          " FROM bookinfo AS bi ,sellinfo AS si ,user AS u WHERE si.status='0'  ".$area." AND bi.id = si.book_id AND si.seller_id = u.id";
        	}else{
		        $result = @split('#',$data['q']);
		        $params = array();
		        foreach($result as $r ){
		        	if($r != ""){
		        		$params[] = $r;
		        	}
		        }

		        $sql = "SELECT bi.imgpath,u.nickname, si.id,si.book_id,si.seller_id,si.`status`,bi.fixedPrice AS `fixedprice`,bi.author,bi.press,bi.name,si.price,si.off,u.college,u.campus,u.tel,si.`des`,si.pics,si.stime".
		          " FROM bookinfo AS bi ,sellinfo AS si ,user AS u WHERE si.status='0'  ".$area." AND bi.id = si.book_id AND si.seller_id = u.id AND (";
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
        	}
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