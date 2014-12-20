<?php
namespace Pest;
class Response
{
	const INVALID_PARAMS = 400;
	private $codes = array(
			100 => 'Continue',
			101 => 'Switching Protocols',
			200 => 'OK',
			201 => 'Created',
			202 => 'Accepted',
			203 => 'Non-Authoritative Information',
			204 => 'No Content',
			205 => 'Reset Content',
			206 => 'Partial Content',
			300 => 'Multiple Choices',
			301 => 'Moved Permanently',
			302 => 'Found',
			303 => 'See Other',
			304 => 'Not Modified',
			305 => 'Use Proxy',
			306 => '(Unused)',
			307 => 'Temporary Redirect',
			400 => 'Bad Request',
			401 => 'Unauthorized',
			402 => 'Payment Required',
			403 => 'Forbidden',
			404 => 'Not Found',
			405 => 'Method Not Allowed',
			406 => 'Not Acceptable',
			407 => 'Proxy Authentication Required',
			408 => 'Request Timeout',
			409 => 'Conflict',
			410 => 'Gone',
			411 => 'Length Required',
			412 => 'Precondition Failed',
			413 => 'Request Entity Too Large',
			414 => 'Request-URI Too Long',
			415 => 'Unsupported Media Type',
			416 => 'Requested Range Not Satisfiable',
			417 => 'Expectation Failed',
			500 => 'Internal Server Error',
			501 => 'Not Implemented',
			502 => 'Bad Gateway',
			503 => 'Service Unavailable',
			504 => 'Gateway Timeout',
			505 => 'HTTP Version Not Supported'
	);
	private $jigeErrorCodes = array(
			1000 => 'Undefined Error',
			1001 => 'Invalid Type',
			1002 => 'Book is inexistent',
			1003 => 'Invalid Uid',
			1004 => 'Invalid Parameter',
			1005 => 'Repeat',
			1006 => 'Inexistent',
			1007 => 'File is inexistent',
			1008 => 'Save Error',
	);

	private $status = 200;
	
	private static $instance = NULL;
	
	public static function getInstance()
	{
	    if (self::$instance === null){
	        self::$instance = new self();
	    }
	    return self::$instance;
	}

	public function setStatus ($status)
	{
		$this->status = $status;
		return $this;
	}

	public static function send ($data, $status = 200, $is_raw = false)
	{
		$res = self::getInstance();
		$res->status = $status;
		header('HTTP/1.1 ' . $res->status . ' ' . $res->codes[$res->status]);
		header('Content-type: application/json');
		if ($is_raw){
		    echo $data;
		    return ;
		}
		if (is_array($data) || is_object($data)) {
			echo json_encode($data);
		} else {
			echo $data;
		}
	}

	public static  function end($status = 200)
	{
	    $res = self::getInstance();
		header('HTTP/1.1 ' . $status . ' ' . $res->codes[$status]);
		ob_flush();
		exit();
	}
	
	public static function sendSuccess($data)
	{
	    self::send(array('result'=>0, 'data'=>$data));
	}
	
	public static function sendFailure($result = 1000)
	{
		$res = self::getInstance();
	    self::send(array('result'=>$result, 'msg'=>$res->jigeErrorCodes[$result]));
	}
}