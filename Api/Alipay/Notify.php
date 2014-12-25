<?php
namespace Api\Alipay;
use Pest\Api;
use Pest\Db\Collection;
use Pest\Request;
use Pest\Util;

class Notify extends Api
{

    public $post = array(
		"out_trade_no" => '/^\S{24}$/',
    );
    
    public function post ()
    {
    	$data = Request::getInstance()->getData();
    	$a = new Collection("alipay_notify");
    	$ret = $a->save($data);
    	if($ret){
    		//待支付、已支付、待确认|已打印、已领取
    		if("TRADE_FINISHED"==$data["trade_status"]||"TRADE_SUCCESS"==$data["trade_status"] ){
	    		$o = new Collection("printorder");
	    		$info = $o->findOne("id = ?",array($data["out_trade_no"]));
	    		if($info){
	    			if("待支付"==$info["status"]){
	    				$tmp = array(
	    						"status" => "已支付",
	    						"id" =>$data["out_trade_no"],
	    				);
	    				$ret = $o->save($tmp);
	    				if(!$ret){
	    					Util::logger("alipay更新状态异常:".json_decode($tmp));
	    				}
	    			}else if("已支付"==$info["status"]){

	    			}else{
	    				Util::logger("alipay状态异常:".json_decode($data));
	    			}
	    		}else{
	    			Util::logger("alipay错误的交易ID:".json_decode($data));
	    		}
    		}
    	}else{
    		Util::logger("alipay请求保存失败:".json_decode($data));
    	}
    	echo "success";
    }
}