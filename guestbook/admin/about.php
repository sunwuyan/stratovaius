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

if(!defined('IN_GB'))
	exit('Access denied!');

if(isset($_GET['versioninfor']))
	getUpdateInfo();
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=gbk" />
<link type="text/css" rel="stylesheet" href="./css/guest.css">
<script language="javascript" src="javascript/Jphp.js"></script>
<script language="javascript" src="javascript/guest.js"></script>
<title>��̨��ҳ</title>
</head>

<body>
<table border="0" cellpadding="0" cellspacing="1" class="admintb">
    <tr>
      <td colspan="2" class="header">ϵͳ��Ϣ</td>
    </tr>
    <tr>
      <td width="40%">���԰�汾</td>
      <td><?php echo "php���������԰� ".VERSION;?> [<a href="http://www.phpfans.net/bbs/viewthread.php?tid=6345&amp;extra=page%3D1" target="_blank">�鿴���°汾</a>]</td>
    </tr>
    <tr>
      <td>����ϵͳ��PHP </td>
      <td><?php echo PHP_OS."/PHP".PHP_VERSION;?></td>
    </tr>
    <tr>
      <td>MySQL�汾</td>
      <td><?php echo "MySQL�汾".$db->version();?></td>
    </tr>
    <tr>
      <td>���������汾</td>
      <td><?php $arr = explode(' ',$_SERVER['SERVER_SOFTWARE']); echo $arr[0];?></td>
    </tr>
    <tr class="td">
      <td>������</td>
      <td id="versionTd">
	  <font color="#0000FF">���ڼ���°汾...</font>[<a href="javascript:getUpdateInfo();">���¼��</a>]
	  </td>
    </tr>
    <tr id="updataMsgTr" style="display:none;">
      <td>������Ϣ</td>
      <td id="updataMsgTd">&nbsp;</td>
    </tr>
</table>
<br />
<table width="98%" border="0" cellpadding="0" cellspacing="1" class="admintb">
    <tr>
      <td colspan="2" class="header">�������԰�</td>
    </tr>
    <tr>
      <td width="40%">��Ȩ����</td>
      <td><a href="http://www.phpfans.net/" target="_blank">php������</a> ���ߣ�<a href="http://www.phpfans.net/space/?2">�Ҳ�����</a></td>
    </tr>
    <tr class="td">
      <td>��ʾ</td>
      <td><a href="http://www.phpfans.net/guestbook/" target="_blank">http://www.phpfans.net/guestbook/</a></td>
    </tr>
    <tr>
      <td>������־</td>
      <td><a href="http://www.phpfans.net/bbs/viewthread.php?tid=6345&amp;extra=page%3D1" target="_blank">�鿴������־</a></td>
    </tr>
    <tr>
      <td>�����뽨��</td>
      <td><a href="http://www.phpfans.net/bbs/forumdisplay.php?fid=24&amp;page=1" target="_blank">��������뽨��</a> </td>
    </tr>
    <tr>
      <td>PHPfans��ҳ</td>
      <td><a href="http://www.phpfans.net/" target="_blank">http://www.phpfans.net/</a></td>
    </tr>
    <tr>
      <td>PHPfans��̳</td>
      <td><a href="http://www.phpfans.net/bbs/" target="_blank">http://www.phpfans.net/bbs/</a></td>
    </tr>
</table>
</body>
</html>