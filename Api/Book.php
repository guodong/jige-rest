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
            'q' => '/^\S{1,}$/',
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
                $obj_info = json_decode($bookinfo);
                echo '11111';
                var_dump($obj_info);
                if (! isset($obj_info->code)) {
                    $this->doubanToDb($obj_info, 2);
                    Response::sendSuccess(json_decode($bookinfo));
                } else {
                    $bookinfo = $this->GetBookInfoFromDoubanV1($data['q']);
                    echo '22222';
                    var_dump($bookinfo);
                    if ($bookinfo != 'bad isbn') {
                        $obj_info = json_decode($bookinfo);
                        $this->doubanToDb($obj_info, 1);
                        Response::sendSuccess(json_decode($bookinfo));
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
            $_t = '$t';
            $_attr = 'db:attribute';
            $_name = '@name';
            $this->data = $data;
            $d = array(
                    'name' => $data->title->$_t,
                    'author' => $data->author[0]->name->$_t,
                    'press' => $this->getattr('publisher'),
                    'isbn' => $this->getattr('isbn13'),
                    'edition' => $this->getattr('pubdate'),
                    'fixedPrice' => $this->getattr('price'),
                    'version' => $version,
                    'doubanjson' => json_encode($data),
                    'bookStatus' => 'approve',
                    'discount' => 0
            );
            //print_r($d);die();
        }
        echo 'version:'.$version;
        var_dump($d);
        //if(null==$d->name||null==$d->isbn||null==$d->fixedPrice)
        //	return;
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
        $url = "https://api.douban.com/book/subject/isbn/{$isbn}?apikey=0c6f834296af9f37254e89c7c40edda5&alt=json";
        echo $ct = file_get_contents($url);
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
        $ct = file_get_contents($url);
        var_dump($ct);
        return $ct;
    }
}
