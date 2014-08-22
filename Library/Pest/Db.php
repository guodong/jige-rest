<?php
namespace Pest;
use Pest\Singleton;
use PDO;

class Db implements Singleton
{

    const DB_DRIVER_PDO = "Pdo";

    const DB_DRIVER_MONGO = "Mongo";

    private $client;

    private $db;

    private $driver;

    protected static $instance = NULL;

    public static function getInstance ()
    {
        if (null === self::$instance) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    public function __construct ()
    {
        $dbConfig = Config::get('db');
        $this->driver = $dbConfig['driver'];
        switch ($this->driver) {
            case self::DB_DRIVER_MONGO:
                $this->client = new \MongoClient();
                $dbname = Config::get('db');
                $dbname = $dbname['name'];
                $this->db = $this->client->$dbname;
                break;
            case self::DB_DRIVER_PDO:
                $this->connMysql($dbConfig);
                break;
            default:
                die("not supported db driver!");
                break;
        }
    }

    public function getDb ()
    {
        return $this->db;
    }

    private function connMysql ($configs)
    {
        
        try {
            $dbh = new PDO(
                    'mysql:host=' . $configs['host'] . ';dbname=' .
                             $configs['dbname'], $configs['username'], 
                            $configs['password'], 
                            array(
                                    PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8"
                            ));
            $this->db = $dbh;
        } catch (\PDOException $e) {
            die("Error!: " . $e->getMessage() . "<br/>");
        }
    }
    
    public static function sql($sql, $params)
    {
        $sth = $this->db->prepare($sql);
        $sth->execute($params);
        return $sth->fetchAll();
    }
}