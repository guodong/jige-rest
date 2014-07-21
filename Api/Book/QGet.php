<?php
namespace Api\Book;
use Pest\Api;
use Pest\Response;
use Pest\Db\Collection;
use Pest\Request;
//根据查询关键字获取图书详情
class QGet extends Api
{
	public $get = array(
			'q' => '/^\S{1,}$/',
	);

	public function get ()
	{
		$c = new Collection('bookinfo');
		$data = Request::getInstance()->getData();
		$data['q'] = '%'.$data['q'].'%';
		$bookinfo = $c->findOne('search LIKE ?',array($data['q']));
		if ($bookinfo){
			Response::sendSuccess($bookinfo);
		}else {
			Response::sendSuccess(array('result' => 0));
		}
	}
}