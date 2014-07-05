<?php
namespace Pest\Db;
use Pest\Db;
class Collection
{
    public static function get($name)
    {
        $db = Db::getInstance()->getDb();
        return $db->$name;
    }
}