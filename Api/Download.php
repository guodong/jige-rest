<?php
namespace Api;
use Pest\Api;
use Pest\Db\Collection;
use Pest\Request;
use Pest\Response;
use Pest\Db;

class download extends Api
{
    public $get = array(
            'id' => '/^\S{24}$/'
    );
    
    public function get ()
    {
        $data = Request::getInstance()->getData();
        $p = new Collection('printorder');
        $ret = $p->findOne("id = ?",array($data["id"]));
        if(!$ret){
        	Response::sendFailure(1006);
        }else{
        	$file_name = $ret["filename"];
        	$file_dir = $ret["filepath"];
        	if (!file_exists($file_dir)) { //判断文件是否存在
        		Response::sendFailure(1007);
        	} else {
        		$file = fopen($file_dir,"r"); //打开文件
        		// 输入文件头
        		Header("Content-type: application/octet-stream");
        		Header("Accept-Ranges: bytes");
        		Header("Accept-Length: ".filesize($file_dir));
        		Header("Content-Disposition: attachment; filename=" . $file_name);
        		// 输出文件内容
        		echo fread($file,filesize($file_dir));
        		fclose($file);
        		exit;
        	}
        }
    }
}