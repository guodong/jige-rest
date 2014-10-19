<?php
namespace Api\Work;
use Pest\Api;
use Pest\Db\Collection;
use Pest\Request;
use Pest\Response;
use Pest\Db;

class BookReviewReset extends Api
{
    public function post ()
    {
    	$sql ="UPDATE `bookinfo` SET `isreview` = '0' WHERE `isreview` = 1";
		$ret = Db::sqlexec($sql);
		Response::sendSuccess(array(
			"result" =>$ret 
		));
    }
}
