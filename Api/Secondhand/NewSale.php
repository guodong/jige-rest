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
			'bookid' => '/^\S{24}$/',
			'price' => '/^\S{1,}$/',
			'sellerid' => '/^\S{24}$/',
	);

	public function post()
	{
		$data = Request::getInstance()->getData();
		$c = new Collection('');//�ȴ���ݱ�
		$id = $c->save($data);
		$_SESSION['uid'] = $id;
		Response::sendSuccess(array('id'=>$id));
	}
}