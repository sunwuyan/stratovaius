<?php 
/*********************************************
*
* �������� php���������Բ�
* ��  ʾ�� http://www.phpfans.net/guestbook/
*
* �� �ߣ� �Ҳ�����
* Email�� deng5765@163.com
* �� ַ�� http://www.phpfans.net
* �� ��:  http://www.phpfans.net/space/?2
*
* �汾�� v2.0
* ������ http://www.phpfans.net/guestbook/
* ���⽨�飺 http://www.phpfans.net/bbs/forumdisplay.php?fid=24&page=1
* ������־�� http://www.phpfans.net/bbs/viewthread.php?tid=6345&extra=page%3D1
* php������վ��http://www.phpfans.net
* php��������̳�� http://www.phpfans.net/bbs/
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