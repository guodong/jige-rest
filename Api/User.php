<?php
namespace Api;
use Pest\Api;
class User extends Api
{
	public function loginApi()
	{
		
		$args = array(
			'username' => '',
			'password' => '',
		);
		Api::handle($args);
	}
}