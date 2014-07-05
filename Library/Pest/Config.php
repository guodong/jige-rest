<?php
namespace Pest;
class Config implements Singleton
{
	private static $data = array();
	
	private static $instance = NULL;
	
	public static function getInstance()
	{
	    if (self::$instance === null){
	        self::$instance = new self();
	    }
	    return self::$instance;
	}
	
	public static function set($key, $value)
	{
		self::$data[$key] = $value;
		return self::$instance;
	}
	
	public static function get($key)
	{
		return self::$data[$key];
	}
}