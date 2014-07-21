<?php
namespace Api\Secondhand;
use Pest\Api;
use Pest\Request;
use Pest\Db\Collection;
use Pest\Response;
//新增二手书交易信息
//待确认，如果首先通过ISBN号确定教材存在，如果不存在则直接插入，即内部调用$HOME/book/get?isbn=?接口
class NewBook extends Api
{
	public $post = array(
			'isbn' => '/^\S{10,}$/',
			'price' => '/^\S{1,}$/',
			'sellerid' => '/^\S{24}$/',
	);

	public function post()
	{
		$data = Request::getInstance()->getData();
		$c = new Collection('bookinfo');
		$saletype = $c->findOne('isbn = ?',array($data['isbn']));
		if ($saletype & isset($saletype['id'])){
			$saleinfo = array();
			$saleinfo['productid'] = $saletype['id'];
			$saleinfo['seller'] = $data['sellerid'];
			$saleinfo['price'] = $data['price'];
			$saleinfo['type'] = '1';//需要和数据库信息对应
			$c = new Collection('saleinfo');
			$id = $c->save($saleinfo);
			Response::sendSuccess(array('id'=>$id));
		}else {
			Response::sendFailure(1002);
		}
	}
}