<?php
namespace Api\Book;
use Pest\Api;
use Pest\Response;
use Pest\Db\Collection;
use Pest\Request;
// 根据ISBN号获取图书详情
class Get extends Api
{

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
        } else {
            $data['q'] = '%' . $data['q'] . '%';
            $bookinfo = $c->findAll('search LIKE ? ', 
                    array(
                            $data['q']
                    ));
        }
        
        if (!$bookinfo) {
            Response::sendSuccess($bookinfo);
        } else 
            if ("ISBN" == $data['type']) {
                $bookinfo = $this->GetBookInfoFromDoubanV1($data['q']);die();
                $obj_info = json_decode($bookinfo);
                if (! isset($obj_info->code)) {
                    $this->doubanToDb($obj_info, '2');
                    Response::sendSuccess(json_decode($bookinfo));
                } else {
                    $bookinfo = $this->GetBookInfoFromDoubanV1($data['q']);
                    $obj_info = json_decode($bookinfo);
                    if (! isset($obj_info->code)) {
                        $this->doubanToDb($obj_info, '1');
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

    private function doubanToDb ($data, $version = '2')
    {
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
        $c = new Collection('bookinfo');
        $c->save($d);
    }

    private function GetBookInfoFromDoubanV1 ($isbn)
    {
    	$url = "https://api.douban.com/book/subject/isbn/{$isbn}?apikey=0c6f834296af9f37254e89c7c40edda5";
    	$ct = file_get_contents($url);
    	$str = simplexml_load_string($ct);
    	$data = $str->entry;
    	var_dump($str);
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
        return $ct;
    }
}