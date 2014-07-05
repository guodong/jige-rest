<?php
namespace Api\user;
use Pest\Api;
class Login extends Api
{
	public $post = array(
			'email' => '/^(\w)+(\.\w+)*@(\w)+((\.\w+)+)$/',
			'password' => '/^\S{6,}$/'
	);
	
	public function post ()
    {
        echo 1;
    }
}