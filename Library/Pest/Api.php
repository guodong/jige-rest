<?php
namespace Pest;
class Api
{
    
    public function valid($fields)
    {
        foreach ($fields as $k=>$v){
            if (!preg_match($v, Request::getInstance()->getData($k))){
                return false;
            }
        }
        return true;
    }
    
}