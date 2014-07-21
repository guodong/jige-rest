<?php
namespace Api\Secondhand;
use Pest\Api;
use Pest\Request;
use Pest\Db\Collection;
use Pest\Response;
//新增交易信息
class NewSale extends Api
{
	public $post = array(
			'productid' => '/^\S{24}$/',
			'price' => '/^\S{1,}$/',
			'sellerid' => '/^\S{24}$/',
			'type' =>'/^\S{1,}$/',
	);

	public function post()
	{
		$data = Request::getInstance()->getData();
		$c = new Collection('saletype');
		$saletype = $c->findOne('type = ?',array($data['type']));
		if ($saletype & isset($saletype['id'])){
			$saleinfo = array();
			$saleinfo['productid'] = $data['productid'];
			$saleinfo['seller'] = $data['sellerid'];
			$saleinfo['price'] = $data['price'];
			$saleinfo['type'] = $saletype['id'];
			$c = new Collection('saleinfo');
			$id = $c->save($saleinfo);
			Response::sendSuccess(array('id'=>$id));
		}else {
			Response::sendFailure(1001);
		}
	}
}