<?php
namespace Api;
use Pest\Db\Collection;
use Pest\Db;
use Pest\Api;
use Pest\Response;
use Pest\Request;
use Pest\Util;

class PrintFile extends Api
{

    public $post = array(
            
    );

    public function post ()
    {
    	$data = Request::getInstance()->getData();
    	if("file" == $data["type"]){
	    	$error = "";
	    	$msg = "";
	    	$fileElementName = 'fileToUpload';
	    	if(!empty($_FILES[$fileElementName]['error']))
	    	{
	    		switch($_FILES[$fileElementName]['error'])
	    		{
	    			case '1':
	    				$error = 'The uploaded file exceeds the upload_max_filesize directive in php.ini';
	    				break;
	    			case '2':
	    				$error = 'The uploaded file exceeds the MAX_FILE_SIZE directive that was specified in the HTML form';
	    				break;
	    			case '3':
	    				$error = 'The uploaded file was only partially uploaded';
	    				break;
	    			case '4':
	    				$error = 'No file was uploaded.';
	    				break;
	    	
	    			case '6':
	    				$error = 'Missing a temporary folder';
	    				break;
	    			case '7':
	    				$error = 'Failed to write file to disk';
	    				break;
	    			case '8':
	    				$error = 'File upload stopped by extension';
	    				break;
	    			case '999':
	    			default:
	    				$error = 'No error code avaiable';
	    				Response::sendFailure(1000);
	    		}
	    	}elseif(empty($_FILES['fileToUpload']['tmp_name']) || $_FILES['fileToUpload']['tmp_name'] == 'none'){
	    		$error = 'No file was uploaded..';
	    		Response::sendFailure(1000);
	    	}else	{
	    		$msg .= " File Name: " . $_FILES['fileToUpload']['name'] . ", ";
	    		$msg .= " File Size: " . @filesize($_FILES['fileToUpload']['tmp_name']);
	    		//copy file
	    		$uploads_dir =API_ROOT.'uploads/';
	    		$tmp_name = $_FILES["fileToUpload"]["name"];
	    		$id = substr(strtoupper(md5(uniqid(mt_rand(), true))),1,24);
	    		$name = $id.$tmp_name;
	    		$ret = move_uploaded_file($_FILES['fileToUpload']["tmp_name"], "$uploads_dir"."$name");
	    		//for security reason, we force to remove all uploaded file
	    		@unlink($_FILES['fileToUpload']);
	    		//文件拷贝成功，开始写数据库
	    		if($ret){
		    		Response::sendSuccess(array(
		    			"id" => $id,
		    			"filepath" =>$uploads_dir.$name,
		    		));
	    		}else{
	    			Response::sendFailure(1000);
	    		}
	    	}
    	}else if("update" == $data["type"]){
    		if(empty($data["id"])){
    			Response::sendFailure(1000);
    			return;
    		}else{
    			$tmpdata = array(
    					"status" => $data["status"],
    					"id" =>$data["id"]
    			);
    			$c = new Collection('printorder');
    			$ret = $c->save($tmpdata);
    			if($ret){
    				Response::sendSuccess($ret);
    			}else{
    				Response::sendFailure(1000);
    			}
    		}
    	}else if("qrcode" == $data["type"]){
    		if(empty($data["scene_id"])){
    			Response::sendFailure(1000);
    			return;
    		}else{
    			$sql = "UPDATE printorder SET openid = '".$data["openid"]."' WHERE scene_id = '".$data["scene_id"]."'";
    			$ret = Db::sql($sql);
    			Util::logger($ret);
    			if($ret){
    				Response::sendSuccess($ret);
    			}else{
    				Response::sendFailure(1000);
    			}
    		}
    	}else{
    		$tmpdata = array(
    				"pagesize" => $data['pagesize'],
    				"pagecolor" => $data['pagecolor'],
    				"pagecount" => $data['pagecount'],
    				"gettime" => $data['gettime'],
    				"mark" => $data['mark'],
    				"tel" => $data['tel'],
    				"isduplex" => $data['isduplex'],
    				"filename" => $data['uploadfilename'],
    				"fileid" => $data['uploadfileid'],
    				"filepath" => $data['uploadfilepath'],
    				"createtime" =>time(),
    		);
    		$qrcodeurl = SAE_ROOT."outjson/GetQRCodeTicket.php";
    		$qrcode = file_get_contents($qrcodeurl);
    		$obj = json_decode($qrcode);
    		Util::logger($obj->{'scene_id'});
    		if("0" !=($obj->{'result'})){
    			Util::logger("sae api返回异常".$qrcode);
    			Response::sendFailure(1000);
    			return;
    		}else{
    			$tmpdata['qrcodeid'] = $obj->{'scene_id'};
    		}
    		$c = new Collection('printorder');
    		$ret = $c->save($tmpdata);
    		if($ret){
    			Response::sendSuccess(array(
    				"ticket" => $obj->{'ticket'},
    			));
    		}else{
    			Response::sendFailure(1008);
    		}
    	}
    }
    
    public $get = array(
    		
    );
    
    public function get ()
    {
    	$data = Request::getInstance()->getData();
    	$c = new Collection('printorder');
    	if("shop" == $data["type"]){
    		$s = new Collection('printshop');
    		$ret = $s->findOne("username = ?" , array($data["q"]));
    		if($ret){
    			$ret = $c->findAll("shopname = ?", array($ret["displayname"]));
    			Response::sendSuccess($ret);
    		}else{
    			Response::sendFailure(1000);
    		}
    	}else{
	    	$ret = $c->findAll("tel = ?", array($data["tel"]));
	    	Response::sendSuccess($ret);
    	}
    }
}
