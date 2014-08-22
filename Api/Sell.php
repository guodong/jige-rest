<?php
namespace Api;
use Pest\Api;
use Pest\Db\Collection;
use Pest\Request;
use Pest\Response;
class Sell extends Api
{
    public $get = array(
            'id' => '/^\d{24}$/'
    );
    
    public function get ()
    {
        $c = new Collection('sellinfo');
        $data = Request::getInstance()->getData();
        $d = $c->findOne('id=?', array($data['id']));
        Response::send($d);
    }
    
}