<?php
namespace Pest;
class Request
{

	private $method = 'get';

	private static $data = array();

	private $uri;
	
	private static $instance = NULL;
	
	public static function getInstance()
	{
	    if (self::$instance === null){
	        self::$instance = new self();
	    }
	    return self::$instance;
	}

	public function __construct ()
	{
		$this->method = strtolower($_SERVER['REQUEST_METHOD']);
		$uri = $_SERVER['REQUEST_URI'];
		$arr = explode('?', $uri);
		$this->uri = $arr[0];
		switch ($this->method) {
			case 'get':
				self::$data = $_GET;
				break;
			case 'post':
				self::$data = $_POST;
				break;
			case 'put':
			case 'delete':
				parse_str(file_get_contents('php://input'), $put_vars);
				self::$data = $put_vars;
				break;
		}
	}

	public function setMethod ($method)
	{
		$this->method = $method;
		return $this;
	}

	public function getMethod()
	{
		return $this->method;
	}

	public function setData ($data)
	{
		self::$data = $data;
		return $this;
	}
	
	public function appendData($key, $value)
	{
	    self::$data[$key] = $value;
	    return $this;
	}

	public static function getData($key = NULL, $clean = TRUE)
	{
		if ($key){
			if (is_array($key)){
				$da = array();
				foreach ($key as $v){
					if ($clean){
						if (isset(self::$data[$v]) && !empty(self::$data[$v])){
							$da[$v] = self::$data[$v];
						}
					}else {
						$da[$v] = isset(self::$data[$v])?self::$data[$v]:null;
					}
				}
				return $da;
			}
			return isset(self::$data[$key])?self::$data[$key]:null;
		}
		return self::$data;
	}

	public function getUri()
	{
		return $this->uri;
	}
}