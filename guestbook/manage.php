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

checkAdminLogin($islogin,false);

$vartem['setting']['basic']['title']['value'] = $vartem['setting']['basic']['name']['value'] . '_' . $vartem['lang']['manage'];

if(isset($_POST['submit'])){
	switch($_POST['action']){
		case 'reply':
			$sql = "replace into {$mydbpre}reply values(null,".$_POST['p_id'].",'".$_POST['r_rcontent']."','".$_POST['r_rname']."',now())";
			$db->query($sql);
			$msg = $mearr['m2'];
			break;
		case 'del':
			$db->query("delete from {$mydbpre}post where p_id = ".$_POST['p_id']);
			$db->query("delete from {$mydbpre}reply where p_id = ".$_POST['p_id']);
			$msg = $mearr['m3'];
			break;
		case 'up':
			if(isset($_POST['up'])){
				$db->query("update {$mydbpre}post set p_rank = ".$_POST['up']." where p_id = ".$_POST['p_id']);
			}
			$msg = $mearr['m11'];
			break;
	}	
	showmessage($msg,referer());
}
$vartem['action'] = $_GET['action'];
$vartem['p_id'] = intval($_GET['p_id']);

if($vartem['action'] == 're'){
	$vartem['re']['name'] = $vartem['setting']['basic']['username']['value'];
	$vartem['re']['content'] = '';
	
	$query = $db->query("select r_rname, r_content from {$mydbpre}reply where p_id = ".$vartem['p_id']);
	if($db->num_rows($query)){
		$row = $db->fetch_array($query);
		$vartem['re']['name'] = $row['r_rname'];
		$vartem['re']['content'] = $row['r_content'];
	}
}
$vartem['referer'] = referer();

$fanso->display('manage.html');
?>