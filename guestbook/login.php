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

define('IN_GB', TRUE);
require_once('include/common.inc.php');
require_once('lang/message_zh.php');

$vartem['referer'] = referer();
if($islogin){
	if(!isset($_GET['logout']))
		showmessage($mearr['m12'], $vartem['referer']);
	else{
		setcookie('islogin','',time()-60*20);
		$vartem['referer'] = !strstr($vartem['referer'],'admincp.php')? $vartem['referer'] : 'index.php';
		showmessage($mearr['m18'], $vartem['referer']);
	}
}

if(preg_match('/admincp\.php\?\w+=\w+/i',$vartem['referer'])){
	$fanso->include_header = false;
	$fanso->include_footer = false;
}

if(isset($_POST['submit'])){
	if($vartem['setting']['basic']['username']['value'] == $_POST['username'] && $vartem['setting']['basic']['password']['value'] == md5($_POST['password'])){
		setcookie('islogin',1,time()+60*20);	
		showmessage($mearr['m16'],$vartem['referer']);
	}
	else showmessage($mearr['m17'],'login.php?referer='.referer(true));
}
$fanso->display('login.html');
?>