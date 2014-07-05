<?php
namespace Pest;
use Pest\Singleton;
class Db implements Singleton
{
    private $client;
    
    private $db;
    
	protected static $instance = NULL;
	
	public static function getInstance()
	{
		if (null === self::$instance){
			self::$instance = new self();
		}
		return self::$instance;
	}
    
    public function __construct()
    {
        $this->client = new \MongoClient();
        $dbname = Config::get('db');
        $dbname = $dbname['name'];
        $this->db = $this->client->$dbname;
    }
    
    public function getDb()
    {
        return $this->db;
    }
}