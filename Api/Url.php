<?php
namespace Api;
use Pest\Api;
use Pest\Request;
use Pest\Db\Collection;
use Pest\Response;

class Url extends Api
{
    public $post = array(
        'keyword' => '/^\.{2,}$/',
        'title' => '/^\.{1,}$/',
        'url' => '/^\S{6,}$/',
        'staffid' => '/^\.{3,}$/',
    );

    public function post ()
    {
        $data = Request::getInstance()->getData();
        $c = new Collection('url');
        $id = $c->save($data);
        $data = $c->findOne('id=?', array($id));
        Response::sendSuccess($data);
    }

    public $get = array(
            'id' => '/^\S{24}$/'
    );

    public function get ()
    {
        $c = new Collection('url');
        $data = Request::getInstance()->getData();
        $user = $c->findOne('id = ?', 
                array(
                        $data['id']
                ));
        if ($user) {
            Response::sendSuccess(
                    $user
            );
        } else {
            Response::sendSuccess(
                    array(
                            'result' => 0
                    ));
        }
    }
    
    public function all()
    {
        $data = Request::getInstance()->getData();
        $c = new Collection('url');
        if(isset($data["keyword"])){
            $ret = $c->findAll('keyword = ?', array($data['keyword']));
        }else{
            $ret = $c->findAll('1 = 1 GROUP BY keyword', null);
        }
        Response::sendSuccess($ret);
    }

}