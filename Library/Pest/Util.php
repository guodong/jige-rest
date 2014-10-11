<?php
namespace Pest;
class Util
{
    public static function object2array ($object)
    {
        if (is_object($object)) {
            foreach ($object as $key => $value) {
                $array[$key] = $value;
            }
        } else {
            $array = $object;
        }
        return $array;
    }
    
    public static function logger($content)
    {
    	file_put_contents(PATH_BASE."/public/log.html",date('Y-m-d H:i:s ').$content."<br/>",FILE_APPEND);
    }
}