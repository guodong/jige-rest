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

	private $status = 200;

	private $data;

	public function setStatus ($status)
	{
		$this->status = $status;
		return $this;
	}

	public function setData ($data)
	{
		$this->data = $data;
		return $this;
	}

	public function write ($data)
	{
		$this->data .= $data;
	}

	public function send ()
	{
		header('HTTP/1.1 ' . $this->status . ' ' . $this->codes[$this->status]);
		if (is_array($this->data) || is_object($this->data)) {
			if (isset($this->data['_id'])){
				//$this->data['id'] = (string)$this->data['_id'];
				//unset($this->data['_id']);
			}
			echo json_encode($this->data);
		} else {
			echo $this->data;
		}
		ob_flush();
	}

	public function end($status = 200)
	{
		header('HTTP/1.1 ' . $status . ' ' . $this->codes[$status]);
		ob_flush();
		exit();
	}
}