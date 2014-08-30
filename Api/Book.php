<?php
namespace Api;
use Pest\Api;
use Pest\Db\Collection;
use Pest\Request;
use Pest\Response;
class Book extends Api
{
    private $data;
    public $get = array(
            'q' => '/^.{1,}$/',
            'type' => '/^\S{1,}$/'
    );
    
    public function get ()
    {
        $c = new Collection('bookinfo');
        $data = Request::getInstance()->getData();
        if ("ISBN" == $data['type']) {
            preg_match_all('/\d+/', $data['q'], $d);
            $data['q'] = $d[0][0];
            $bookinfo = $c->findOne('isbn = ?',
                    array(
                            $data['q']
                    ));
        }else if("id" == $data['type']){
            $c = new Collection('bookinfo');
            $bookinfo = $c->findOne('id=?', array($data['q']));
        }else{
            $ret = @split('#',$data['q']);
            $sql = '';
            for($i = 0;$i < count($ret);$i++){
                if($ret[$i] != ''){
                    if($sql == '')
                        $sql = $sql . '`search` LIKE \'%'.$ret[$i].'%\'';
                    else
                        $sql = $sql . ' AND `search` LIKE \'%'.$ret[$i].'%\'';
                }
            }
            $bookinfo = $c->findAll($sql,null);
        }
        
        if ($bookinfo) {
            Response::sendSuccess($bookinfo);
        } else
            if ("ISBN" == $data['type']) {
                $bookinfo = $this->GetBookInfoFromDoubanV2($data['q']);
                if ($bookinfo) {
                    $result = $this->doubanToDb(json_decode($bookinfo), 2);
                    Response::sendSuccess($result);
                } else {
                    $bookinfo = $this->GetBookInfoFromDoubanV1($data['q']);
                    
                    if ($bookinfo) {
                        $d = new \stdClass();
                        preg_match('#<span property="v:itemreviewed">(.*)</span>#', $bookinfo, $match);
                        $d->name = $match[1];
                        preg_match('#<a class="" href="/search/.*">(.*)</a>#', $bookinfo, $match);
                        $d->author = $match[1];
                        preg_match('#<span class="pl">出版社:</span> (.*)<br/>#', $bookinfo, $match);
                        $d->press = $match[1];
                        preg_match('#<span class="pl">ISBN:</span> (.*)<br/>#', $bookinfo, $match);
                        $d->isbn = $match[1];
                        preg_match('#<span class="pl">出版年:</span> (.*)<br/>#', $bookinfo, $match);
                        $d->edition = $match[1];
                        preg_match('#<span class="pl">定价:</span> (.*)<br/>#', $bookinfo, $match);
                        $d->fixedPrice = $match[1];
                        $obj_info = $d;
                        $this->doubanToDb($obj_info, 1);
                        Response::sendSuccess($obj_info);
                    } else {
                        Response::sendSuccess(
                        array(
                        'result' => 0
                        ));
                    }
                }
            } else {
                Response::sendSuccess(
                array(
                'result' => 0
                ));
            }
    }
    
    private function doubanToDb ($data, $version = 2)
    {
        if ($version === 2){
            $d = array(
                    'name' => $data->title,
                    'author' => $data->author[0],
                    'press' => $data->publisher,
                    'isbn' => $data->isbn13,
                    'edition' => $data->pubdate,
                    'fixedPrice' => $data->price,
                    'version' => $version,
                    'doubanjson' => json_encode($data),
                    'bookStatus' => 'approve',
                    'discount' => 0
            );
        }else {
            $d = $data;
            $d['version'] = $version;
            $d['bookStatus'] = 'approve';
            $d['discount'] = 0;
        }
        
        $c = new Collection('bookinfo');
        $c->save($d);
        return $c->findOne('isbn=?',array($d['isbn']));
    }
    
    private function getAttr($attr){
        $_t = '$t';
        $_attr = 'db:attribute';
        $_name = '@name';
            $attrs = $this->data->$_attr;
            foreach ($attrs as $a){
                if ($a->$_name === $attr){
                    return $a->$_t;
                }
            }
        
    }
    
    private function GetBookInfoFromDoubanV1 ($isbn)
    {
        $url = "http://book.douban.com/isbn/{$isbn}";
        $ct = @file_get_contents($url);
        return $ct;
        
        //     	$d = array(
    
        //     	        'name' => (string)$data->title,
        //     	        'author' => (string)$data->author->name[0],
        //     	        'press' => $data->db->,
        //     	        'isbn' => $data->isbn13,
        //     	        'edition' => $data->pubdate,
        //     	        'fixedPrice' => $data->price,
        //     	        'version' => $version,
        //     	        'doubanjson' => json_encode($data),
        //     	        'bookStatus' => 'approve',
        //     	        'discount' => 0
        //     	)
    }
    
    private function GetBookInfoFromDoubanV2 ($isbn)
    {
        $url = "https://api.douban.com/v2/book/isbn/:{$isbn}?apikey=0c6f834296af9f37254e89c7c40edda5";
        $ct = @file_get_contents($url);
        return $ct;
    }
    
    public $post = array(
    		'isbn' => '/^\S{1,}$/',
    		'fixedPrice' => '/^\S{1,}$/',
    		'name' => '/^\S{1,}$/',
    		'press' => '/^\S{1,}$/',
    );
    
    public function post ()
    {
    	$c = new Collection('bookinfo');
    	$data = Request::getInstance()->getData();
    	$data['bookStatus'] = '0';
    	$id = $c->save($data);
    	if(!$id){
    		Response::sendSuccess($id);
    	}else{
    		Response::sendFailure(1000);
    	}
    }
}
