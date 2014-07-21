<?php
namespace Api\Secondhand;
use Pest\Api;
use Pest\Response;
use Pest\Db\Collection;
use Pest\Request;
//获取某个用户的全部交易信息
class GetAll extends Api
{
	public $get = array(
			'id' => '/^\S{24}$/',
	);

	public function get ()
	{
		$c = new Collection('');
		$data = Request::getInstance()->getData();
		$Saleinfo = $c->find('id = ?',array($data['id']));
		if ($Saleinfo){
			Response::sendSuccess($Saleinfo);
		}else {
			Response::sendSuccess(array('result' => 0));
		}
	}
}