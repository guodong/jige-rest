<?php
namespace Api\Book;
use Pest\Api;
use Pest\Response;
use Pest\Db\Collection;
use Pest\Request;
//根据ISBN号获取图书详情
class Get extends Api
{	
	public $get = array(
			'q' => '/^\S{1,}$/',
			'type' => '/^\S{1,)$/',
	);
	
	public function get ()
	{
		$c = new Collection('bookinfo');
		$data = Request::getInstance()->getData();
		if("ISBN" == $data['type'])
		{
			$bookinfo = $c->findOne('isbn = ?',array($data['q']));
		}else{
			$data['q'] = '%'.$data['q'].'%';
			$bookinfo = $c->findAll('search LIKE ? ',array($data['q']));
		}
		
		if ($bookinfo){
			Response::sendSuccess($bookinfo);
		}else if("ISBN" ==$data['type']){
			$bookinfo =  $this ->GetBookInfoFromDoubanV2($data['q']);
			if($bookinfo){
				Response::sendSuccess($bookinfo);
			}else{
				$bookinfo =  $this ->GetBookInfoFromDoubanV1($data['q']);
				if($bookinfo){
					Response::sendSuccess($bookinfo);
				}else{
					Response::sendSuccess(array('result' => 0));
				}
			}	
		}else{
			Response::sendSuccess(array('result' => 0));
		}
	}
	
	private function GetBookInfoFromDoubanV1($isbn)
	{
	
	}
	
	private function GetBookInfoFromDoubanV2($isbn)
	{
	
	}
}