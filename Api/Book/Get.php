<?php
namespace Api\Book;
use Pest\Api;
use Pest\Response;
use Pest\Db\Collection;
use Pest\Request;

class Get extends Api
{	
	public $get = array(
			
	);
	
	public function get ()
	{
		$c = new Collection('bookinfo');
		$data = Request::getInstance()->getData();
		$bookinfo = $c->findOne('isbn = ?',array($data['isbn']));
		if ($bookinfo){
			Response::sendSuccess($bookinfo);
		}else {
			Response::sendSuccess(array('result' => 0));
		}
	}
}