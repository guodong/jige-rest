<?php
namespace Api\User;
use Pest\Api;
use Pest\Response;

class Get extends Api
{

    public $get = array(
            'id' => '/^\d*$/'
    );

    public function get ()
    {
        $data = array(
                'realname' => "郭栋",
                'gender' => '男'
        );
        Response::getInstance()->send($data);
    }
}