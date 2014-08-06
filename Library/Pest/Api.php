<?php
namespace Pest;
class Api
{
    
    public function valid($fields)
    {
        foreach ($fields as $k=>$v){
            if (!preg_match($v, Request::getData($k))){
                Response::send(array('result'=>1, 'msg'=>$k.' invalid'));
                return false;
            }
        }
        return true;
    }
    
}