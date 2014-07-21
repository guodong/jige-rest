<?php
namespace Api\Secondhand;
use Pest\Api;
use Pest\Response;
use Pest\Db\Collection;
use Pest\Request;
//根据ID获取交易信息详情
class Get extends Api
{	
	public $get = array(
			'id' => '/^\S{24}$/',
	);
	
	public function get ()
	{
		$c = new Collection('');
		$data = Request::getInstance()->getData();
		$Saleinfo = $c->findOne('id = ?',array($data['id']));
		if ($Saleinfo){
			Response::sendSuccess($Saleinfo);
		}else {
			Response::sendSuccess(array('result' => 0));
		}
	}
}