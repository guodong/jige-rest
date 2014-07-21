<?php
namespace Api\Book;
use Pest\Api;
use Pest\Response;
use Pest\Db\Collection;
use Pest\Request;
//根据ISBN号获取图书详情
class IsbnGet extends Api
{	
	public $get = array(
			'isbn' => '/^\S{10,}$/',
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