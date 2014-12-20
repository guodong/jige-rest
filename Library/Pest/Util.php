<?php
namespace Pest;
class Util
{
    public static function object2array ($object)
    {
        if (is_object($object)) {
            foreach ($object as $key => $value) {
                $array[$key] = $value;
            }
        } else {
            $array = $object;
        }
        return $array;
    }
    
    public static function http_post($url,$data){
    	$info = array ();
    	$header = "Content-type: text/xml";
    	// 初始化会话。
    	$curl = curl_init ();
    	// 需要获取的URL地址，也可以在curl_init()函数中设置。
    	curl_setopt ( $curl, CURLOPT_URL, $url );
    	// 启用时会发送一个常规的POST请求，类型为：application/x-www-form-urlencoded，就像表单提交的一样。
    	curl_setopt ( $curl, CURLOPT_POST, 1 );
    	// 全部数据使用HTTP协议中的"POST"操作来发送。
    	curl_setopt ( $curl, CURLOPT_POSTFIELDS, $data );
    	// 设置cURL允许执行的最长秒数。
    	curl_setopt ( $curl, CURLOPT_TIMEOUT, 3 );
    	// 启用时会将头文件的信息作为数据流输出。
    	curl_setopt ( $curl, CURLOPT_HEADER, $header );
    	// 将curl_exec()获取的信息以文件流的形式返回，而不是直接输出。
    	curl_setopt ( $curl, CURLOPT_RETURNTRANSFER, 1 );
    	// 执行会话
    	$info = curl_exec ( $curl );
    	if (curl_errno ( $curl )) {
    		Util::logger('Errno' . curl_error ( $curl ));
    		curl_close ( $curl );
    		return false;
    	}
    	// 关闭会话
    	curl_close ( $curl );
    	// 返回数据
    	return $info;
    }
    
    public static function logger($content)
    {
    	file_put_contents(PATH_BASE."/public/log.html",date('Y-m-d H:i:s ').$content."<br/>",FILE_APPEND);
    }
}