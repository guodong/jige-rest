<?php
namespace Api;
use Pest\Api;
use Pest\Response;
class Test extends Api
{
    public function get(){
        Response::sendSuccess(array(3));
    }
}