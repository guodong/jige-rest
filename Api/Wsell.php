<?php
namespace Api;
use Pest\Api;
use Pest\Db\Collection;
use Pest\Request;
use Pest\Response;
use Pest\Db;
use Pest\Util;

class Wsell extends Api
{
    public $get = array(
            'openid' => '/^\S{28}$/',
    		'type' =>'/^\S{3,}$/'
    );

    public function get ()
    {
    	$data = Request::getInstance()->getData();
    	if("openid" == $data['type']){
	    	$u = new Collection('user');
	    	$userinfo = $u->findOne('woid=?', array($data['openid']));
	        $c = new Collection('sellinfo');
	        $d = $c->findOne('id=?', array($userinfo['id']));
	        Response::sendSuccess($d);
    	}
        else if("sellinfoid"==$data['type']){
        	$sql = "SELECT b.`name`,b.author,b.press,b.fixedPrice,b.imgpath,b.isbn,s.price FROM bookinfo AS b ,sellinfo AS s WHERE b.id = s.book_id AND ".
          	"s.id = '".$data['sellinfoid']."'";
        	$ret = Db::sql($sql);
        	Response::sendSuccess($ret);
        }
    }

    public $post = array(
    		//'book_id' => '/^\S{24}$/',
    		//'openid' => '/^\S{28}$/',
    		'price' =>'/^.{1,}/',
    );
    
    public function post ()
    {
    	$data = Request::getInstance()->getData();
    	if("update" == $data['type']){
    		if(isset($data['sellinfoid'])){
    			$newdata = array();
    			$newdata['id'] = $data['sellinfoid'];
    			$newdata['price'] = $data['price'];
    			$newdata['status'] = $data['status'];
    			$s = new Collection('sellinfo');
    			$olddata = $s->findOne('id = ?',array($data['sellinfoid']));
    			if($s){
    				$newdata['off'] =round($newdata['price']*$olddata['off']/$olddata['price'],1);
    				$id = $s->save($newdata);
    				if($id){
    					Response::sendSuccess($id);
    				}else{
    					Response::sendFailure(1000);
    				}
    			}else{
    				Response::sendFailure(1002);
    			}

    		}else{
    			Response::sendFailure(1000);
    		}
    	}else{
	    	$u = new Collection('user');
	    	$userinfo = $u->findOne('woid=?', array($data['openid']));
	    	unset($data['openid']);
	    	if($userinfo){
	    		$b = new Collection('bookinfo');
	    		$bookinfo = $b->findOne('id = ?',array($data['book_id']));
		    	$c = new Collection('sellinfo');
		    	$data['seller_id'] = $userinfo['id'];
		    	$data['college'] = $userinfo['college'].$userinfo['campus'];
		    	$data['contact'] = $userinfo['tel'];
		    	$data['stime'] = time();
		    	$data['status'] = '0';
		    	$data['off'] = round($data['price']*10/$bookinfo['fixedPrice'],1);
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
    }
    
    public function all()
    {
        $data = Request::getData();
        $u = new Collection('user');
        $userinfo = $u->findOne('woid=?', array($data['openid']));
        if($userinfo){
	        $sql = "SELECT si.id AS 'sellinfoid',u.woid AS 'openid',bi.`name`,bi.author,bi.press,bi.fixedPrice,si.price,si.college,si.contact,si.off,u.nickname,u.college AS ucollege,u.campus AS ucampus,".
	 	        "bi.imgpath FROM bookinfo AS bi ,sellinfo AS si ,`user` AS u WHERE bi.id = si.book_id AND si.status='0' AND si.seller_id = u.id AND u.woid='".$data['openid']."'";
	        $ret = Db::sql($sql);
	        Response::sendSuccess($ret);
        }else{
        	Response::sendFailure(1000);
        }
    }
}