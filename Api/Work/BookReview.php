<?php
namespace Api\Work;
use Pest\Api;
use Pest\Db\Collection;
use Pest\Request;
use Pest\Response;

class BookReview extends Api
{
    public function get ()
    {
        $data = Request::getInstance()->getData();
        $c = new Collection('bookinfo');
        $bookinfo = $c->findOne('isreview = ? AND bookStatus = ?', array('0','0'));
        if ($bookinfo) {
        	$newdata['id'] = $bookinfo['id'];
        	$newdata['isreview'] = '1';
        	$id = $c->save($newdata);
        	if($id){
        		Response::sendSuccess($bookinfo);
        	}else{
        		Response::sendFailure(1000);
        	}
        } else{
        	Response::sendFailure(1002);
        }
    }

    public $post = array(
    		'id' => '/^\S{24}$/',
    );
    
    public function post ()
    {
    	$c = new Collection('bookinfo');
    	$data = Request::getInstance()->getData();
    	$data['bookStatus'] = '1';
    	$data['isreview'] = '0';
    	$id = $c->save($data);
    	if($id){
    		Response::sendSuccess($id);
    	}else{
    		Response::sendFailure(1000);
    	}
    }
}
