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

$page = checkPage();
$starnum = ($page-1)*$vartem['setting']['basic']['eachpage']['value'];

$sql = "
	select p.*, r.r_content, r.r_rname, r_time 
	from {$mydbpre}post p left join {$mydbpre}reply r 
	on p.p_id = r.p_id order by p_rank desc,p.p_id desc 
	limit {$starnum},{$vartem['setting']['basic']['eachpage']['value']}
"; 
$query = $db->query($sql);

while($row = $db->fetch_array($query)){
	$vartem['msg'][] = messageList($row);
}

$psql = "select count(*) from {$mydbpre}post";
$pa = new pagination($vartem['setting']['basic']['eachpage']['value'],$page,9,2,0,$psql);
$vartem['pageformat'] = $pa->pageFormat();

$fanso->display('list.html');
?>