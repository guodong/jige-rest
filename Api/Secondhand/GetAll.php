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
		$c = new Collection('sellinfo');
		$data = Request::getInstance()->getData();
		if($data['id'] == "000000000000000000000000"){
			$Saleinfo = $c->findAll("1 = 1");
		}
		else {
			$Saleinfo = $c->findAll('sellerid = ?',array($data['id']));
		}
		if ($Saleinfo){
			Response::sendSuccess($Saleinfo);
		}else {
			Response::sendSuccess(array('result' => 0));
		}
	}
}