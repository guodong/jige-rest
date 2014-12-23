<?php
namespace Api;
use Pest\Api;
use Pest\Request;
use Pest\Response;
use PDO;

class Taotao extends Api
{
    public $get = array(
            'seller_id' => '/^\S{4,}$/'
    );
    
    public function get ()
    {
        $data = Request::getInstance()->getData();
        $tt_host = "202.102.86.233";
        $tt_dbname = "letsgo";
        $tt_username = "letsgo";
        $tt_password = "letsgo246810";
        try {
        	$db = new PDO(
        			'mysql:host=' . $tt_host . ';dbname=' .
        			$tt_dbname, $tt_username,
        			$tt_password,
        			array(
        					PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8"
        			));
        } catch (\PDOException $e) {
        	Response::sendFailure(1000);
        }
        $sql = "SELECT SUM(`price`) FROM tt_sellinfo WHERE sellerid = '".$data['seller_id']."' AND issold ='1'  GROUP BY NULL";
        $rs = $db->query($sql);
        $number1 = $rs->fetchColumn();//已出售的金额总和
        if(!$number1){
        	$number1 = 0;
        }else{
        	$number1 = round($number1, 1);
        }
        
        $sql = "SELECT SUM(`count`) FROM tt_tikuan WHERE sellerid ='".$data['seller_id']."' GROUP BY NULL";
        $rs = $db->query($sql);
        $number2 = $rs->fetchColumn();//已提现总额
        if(!$number2){
        	$number2 = 0;
        }else{
        	$number2 = round($number2, 1);
        }
        
        $db = null;
	    Response::sendSuccess(array(
		    "soldprice" => $number1,
		    "getprice" => $number2
	    ));

    }
}