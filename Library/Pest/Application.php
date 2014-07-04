<?php
namespace Rest;
class Application
{
	public function autoload($class)
	{
		$incpaths = get_include_path();
		$arr = explode(PATH_SEPARATOR, $incpaths);
		
		$p = str_replace("\\", DIRECTORY_SEPARATOR, $class) . '.php';
		foreach ($arr as $v){
			$path = $v . $p;
			if (file_exists($path)){
				return require_once $path;
				break;
			}
		}
		return  false;
	}
	
	public function __construct($config)
	{
		spl_autoload_register(array(__CLASS__, 'autoload'));
	}
	
	public function run()
	{
		$request = new Request();
		$response = new Response();
		header('Content-type: application/json');
		$arr = explode('/', $request->getUri());
		$last = array_pop($arr);
		$method = $request->getMethod();
		if ($this->isPlural($last)){
			$last = substr($last, 0, -1);
			$method = 'all';
		}
		array_push($arr, ucfirst($last));
		$uri = implode('\\', $arr);
		$api_str = 'Api' . $uri;
		$api = new $api_str($request, $response);
		
		if (!method_exists($api, $method)){
			$response->end(405);
		}
		$pm = '_'.$method;
		if(property_exists($api, $pm)){
			if (!$api->valid($api->$pm)){
				$response->end($response::INVALID_PARAMS);
			}
		}
		ob_start();
		$api->$method();
		$response->send();
	}
}