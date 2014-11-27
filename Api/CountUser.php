<?php
namespace Api;
use Pest\Api;
use Pest\Request;
use Pest\Db;
use Pest\Response;

class CountUser extends Api
{
    public $get = array(
           // 'id' => '/^\S{24}$/'
    );

    public function get ()
    {
        $data = Request::getInstance()->getData();
        $sql = "SELECT Count(`user`.id) AS count,`user`.college, `user`.campus FROM `user`  WHERE `user`.college IS NOT NULL GROUP BY  `user`.college, `user`.campus";
        $ret = Db::sql($sql);
        Response::sendSuccess($ret);
    }
}