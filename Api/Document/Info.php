<?php
namespace Api\Document;
use Pest\Api;
use Pest\Db\Collection;
use Pest\Request;
use Pest\Response;
use Pest\Db;

class Info extends Api
{
    public $get = array(
            'id' => '/^\S{24}$/'
    );
    
    public function get ()
    {
        $data = Request::getInstance()->getData();
        $c = new Collection('document');
	    $d = $c->findOne('id=?', array($data['id']));
	    Response::sendSuccess($d);
    }

    public $post = array(
    		/*'title' => '/^\.{1,}$/',
    		'staffid' => '/^\S{3}$/',
    		'images' =>'/^.{1,}/',
    		'filepath' => '/^.{1,}/',*/
    );
    
    public function post ()
    {
    	$data = Request::getInstance()->getData();
    	if("update" == $data['type'] ){
    		if(empty($data['id'])){
    			Response::sendFailure(1000);
    		}else{
    			$d = new Collection('document');
    			$document = $d->save($data);
    			if($document){
    				Response::sendSuccess($document); 
    			}else{
    				Response::sendFailure(1000);
    			}
    		}
    	}else{
    		//insert
    		if(!isset($data['title'])||!isset($data['images'])||!isset($data['filepath'])||!isset($data['staffid'])){
    			Response::sendFailure(1004);
    		}
    		
    		if(!isset($data['stime'])){
    			$data['stime'] = time();
    		}
    		
    		if(!isset($data['previewcount'])){
    			$data['previewcount'] = 0;
    		}
    		
    		if(!isset($data['printcount'])){
    			$data['printcount'] = 0;
    		}
    		
    		if(!isset($data['pagesize'])){
    			$data['pagesize'] = 0;
    		}
    		
    		if(!isset($data['status'])){
    			$data['status'] = '0';
    		}
    		
    		$d = new Collection('document');
    		$document = $d->save($data);
    		if($document){
    			Response::sendSuccess($document);
    		}else{
    			Response::sendFailure(1000);
    		}
    	}
    }
    
    public function all()
    {
        $data = Request::getData();
        $area = "";
        if(isset($data['college'])){
        	$area = $area ." AND  d.college = '".$data['college']."' ";
        }
        if(isset($data['class'])){
        	$area = $area ." AND d.class = '".$data['class']."' ";
        }
        
        if($data['type']=='uid'){
        	$c = new Collection('document');
        	$data = Request::getInstance()->getData();
        	$d = $c->findAll('staffid =?', array($data['uid']));
        	Response::sendSuccess($d);
        }else if($data['type']=='latest'){
        	if(!isset($data['count'])){
        		$data['count'] = '5';
        	}
        	if(isset($data['q'])){
        		$result = @split('#',$data['q']);
        		$params = array();
        		foreach($result as $r ){
        			if($r != ""){
        				$params[] = $r;
        			}
        		}
        		$sql = "SELECT * FROM document AS d WHERE status='0'  ".$area." AND  (";
        		$flag = 0;
        		for($i = 0;$i < count($params);$i++){
        			if($flag == 0){
        				$sql = $sql . 'title LIKE \'%'.$params[$i].'%\'';
        				$flag = 1;
        			}else{
        				$sql = $sql . ' OR title LIKE \'%'.$params[$i].'%\'';
        			}
        		}
        		$sql = $sql.')';
        		if(isset($data['start'])){
        			$sql = $sql." ORDER BY stime DESC LIMIT ".$data['start'].",".$data['count'];
        		}else{
        			$sql = $sql." ORDER BY stime DESC LIMIT 0,".$data['count'];
        		}
        	}else{
        		if(isset($data['start'])){
        			$sql = "SELECT * FROM document AS d  WHERE status = '0' ".$area."  ORDER BY stime DESC LIMIT ".$data['start'].",".$data['count'];
        		}else{
        			$sql = "SELECT * FROM document AS d  WHERE status = '0' ".$area."  ORDER BY stime DESC LIMIT 0,".$data['count'];
        		}
        	}
        	$ret = Db::sql($sql);
        	Response::sendSuccess($ret);
        }else if($data['type']=='popular'){
        	if(!isset($data['count'])){
        		$data['count'] = '5';
        	}
        	if(isset($data['start'])){
        		$sql = "SELECT * FROM document AS d  WHERE status = '0' ".$area."  ORDER BY previewcount DESC LIMIT ".$data['start'].",".$data['count'];
        	}else{
        		$sql = "SELECT * FROM document AS d  WHERE status = '0' ".$area."  ORDER BY previewcount DESC LIMIT 0,".$data['count'];
        	}
        	$ret = Db::sql($sql);
        	Response::sendSuccess($ret);
        }else if($data['type']=='class'){
        	if(!isset($data['count'])){
        		$data['count'] = 20;
        	}
			if(isset($data['q'])){
				$result = @split('#',$data['q']);
				$params = array();
				foreach($result as $r ){
					if($r != ""){
						$params[] = $r;
					}
				}
				$sql = "SELECT college,class,Count(id) AS count FROM document AS d WHERE status='0'  ".$area." AND  (";
				$flag = 0;
				for($i = 0;$i < count($params);$i++){
					if($flag == 0){
						$sql = $sql . 'title LIKE \'%'.$params[$i].'%\'';
						$flag = 1;
					}else{
						$sql = $sql . ' OR title LIKE \'%'.$params[$i].'%\'';
					}
				}
				$sql = $sql.')';
				
				if(isset($data['start'])){
					$sql = $sql." GROUP BY college,class ORDER BY count DESC LIMIT ".$data['start'].",".$data['count'];
				}else{
					$sql = $sql." GROUP BY college,class ORDER BY count DESC LIMIT 0,".$data['count'];
				}
			}else{
	        	if(isset($data['start'])){
	        		$sql = "SELECT college,class,Count(id) AS count FROM document AS d WHERE status='0' GROUP BY college,class ORDER BY count DESC LIMIT ".$data['start'].",".$data['count'];
	        	}else{
	        		$sql = "SELECT college,class,Count(id) AS count FROM document AS d WHERE status='0' GROUP BY college,class ORDER BY count DESC LIMIT 0,".$data['count'];
	        	}
			}
	        $ret = Db::sql($sql);
	         Response::sendSuccess($ret);
        }else{
        	
        }
    }
}