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
}