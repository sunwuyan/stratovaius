<?php 
/*********************************************
*
* 程序名： php爱好者留言簿
* 演  示： http://www.phpfans.net/guestbook/
*
* 作 者： 我不是鱼
* Email： deng5765@163.com
* 网 址： http://www.phpfans.net
* 博 客:  http://www.phpfans.net/space/?2
*
* 版本： v2.0
* 帮助： http://www.phpfans.net/guestbook/
* 问题建议： http://www.phpfans.net/bbs/forumdisplay.php?fid=24&page=1
* 更新日志： http://www.phpfans.net/bbs/viewthread.php?tid=6345&extra=page%3D1
* php爱好者站：http://www.phpfans.net
* php爱好者论坛： http://www.phpfans.net/bbs/
*
*********************************************/

function getUpdateInfo(){
	header('Content-type: text/html;charset=GBK');

	$host = 'http://www.phpfans.net/guestbook/version.php';
	//$host = 'http://localhost/phpfans/gb2/version.php';
	$variables = 'host='.urlencode('http://'.$_SERVER['HTTP_HOST'].dirname($_SERVER['PHP_SELF']));
	$infoArr = @file($host.'?'.$variables);
	echo "infoArr = new Array();";
	if($infoArr && count($infoArr) && floatval($infoArr[0])){
		foreach ($infoArr as $val){
			echo 'infoArr[infoArr.length] = \''.str_replace("'","\'",addslashes(trim($val))).'\';';
		}
	}
	exit;
}
?>