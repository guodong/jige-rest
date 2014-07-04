<?php
namespace Pest;
class Request
{

	private $method = 'get';

	private $data = array();

	private $uri;

	public function __construct ()
	{
		$this->method = strtolower($_SERVER['REQUEST_METHOD']);
		$uri = $_SERVER['REQUEST_URI'];
		$arr = explode('?', $uri);
		$this->uri = $arr[0];
		switch ($this->method) {
			case 'get':
				$this->data = $_GET;
				break;
			case 'post':
				$this->data = $_POST;
				break;
			case 'put':
				parse_str(file_get_contents('php://input'), $put_vars);
				$this->data = $put_vars;
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
		$this->data = $data;
		return $this;
	}

	public function getData($key = NULL, $clean = TRUE)
	{
		if ($key){
			if (is_array($key)){
				$da = array();
				foreach ($key as $v){
					if ($clean){
						if (isset($this->data[$v]) && !empty($this->data[$v])){
							$da[$v] = $this->data[$v];
						}
					}else {
						$da[$v] = isset($this->data[$v])?$this->data[$v]:null;
					}
				}
				return $da;
			}
			return isset($this->data[$key])?$this->data[$key]:null;
		}
		return $this->data;
	}

	public function getUri()
	{
		return $this->uri;
	}
}