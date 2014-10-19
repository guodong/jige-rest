<?php
namespace Pest\Db;
use Pest\Db;
use Pest\Util;

class Collection
{

    private $name;

    public function __construct ($name)
    {
        $this->name = $name;
    }

    public static function get ($name)
    {
        $this->name = $name;
        $db = Db::getInstance()->getDb();
        return $db->$name;
    }

    public function findOne ($condition, $args = NULL)
    {
        $db = Db::getInstance()->getDb();
        if (null === $args) {
            $args = $condition;
            $condition = 'id=?';
        }
        $sql = "SELECT * FROM `{$this->name}` WHERE {$condition}";
        $r = $db->prepare($sql);
        $r->execute($args);
        return $r->fetch(\PDO::FETCH_ASSOC);
    }

    public function findAll ($condition, $args)
    {
        $db = Db::getInstance()->getDb();
        $sql = "SELECT * FROM `{$this->name}` WHERE {$condition}";
        $r = $db->prepare($sql);
        if (! $args)
            $r->execute();
        else
            $r->execute($args);
        $rows = array();
        while ($row = $r->fetch(\PDO::FETCH_ASSOC)) {
            array_push($rows, $row);
        }
        return $rows;
    }

    public function save ($data)
    {
        $colunms = $this->getColumns();
        if (is_object($data)){
            $data = Util::object2array($data);
        }
        foreach ($data as $key => $value) {
            if (null === $value || ! in_array($key, $colunms))
                unset($data[$key]);
        }
        if ((isset($data['id']))) {
            $id = $data['id'];
            unset($data['id']);
            return $this->update('where id = ?', 
                    array(
                            $id
                    ), $data);
        } else {
            return $this->insert($data);
        }
    }

    public function update ($condition, $args, $data)
    {
        $karr = array();
        $pmarr = array();
        
        foreach ($data as $field => $v) {
            $karr[] = $field . " = ?";
            $pmarr[] = $v;
        }
        $kstr = implode(', ', $karr);
        
        $pmarr = array_merge($pmarr, $args);
        $sql = "UPDATE " . $this->name . " SET " . $kstr . " " . $condition;
        
        $db = Db::getInstance()->getDb();
        $r = $db->prepare($sql);
        if ($r->execute($pmarr)) {
        	return (string) $args[0];
        } else {
        	return false;
        }
    }

    public function insert ($data)
    {
        $pmarr = array();
        foreach ($data as $k => $v) {
            $pmarr[] = $v;
        }
        $id = new \MongoId(null);
        array_push($pmarr, (string) $id);
        
        $keys = array_keys($data);
        array_push($keys, 'id');
        $fields = implode(', ', $keys);
        
        $sigarr = array();
        foreach ($keys as $v) {
            $sigarr[] = "?";
        }
        $sigstr = implode(', ', $sigarr);
        
        $vals = array_values($data);
        $values = implode(', ', $vals);
        $sql = "INSERT INTO " . $this->name . " ($fields) VALUES ($sigstr)";
        $db = Db::getInstance()->getDb();
        $insert = $db->prepare($sql);
        if ($insert->execute($pmarr)) {
            return (string) $id;
        } else {
        	Util::logger($sql."\n".json_encode($insert->errorInfo()));
            return false;
        }
    }

    public function delete ($condition, $args = NULL)
    {
        $db = Db::getInstance()->getDb();
        $sql = "DELETE FROM `{$this->name}` WHERE {$condition}";
        $r = $db->prepare($sql);
        if (! $args)
            $r->execute();
        else
            $r->execute($args);
        return true;
    }

    private function getColumns ()
    {
        $dbh = Db::getInstance()->getDb();
        $q = $dbh->prepare("DESCRIBE " . $this->name);
        $q->execute();
        return $q->fetchAll(\PDO::FETCH_COLUMN);
    }
}