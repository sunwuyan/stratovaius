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

if(isset($_POST['submit'])){
	if(md5($_POST['oldpw']) == $vartem['setting']['basic']['password']['value']){
		if(trim($_POST['newpw']) == '' || trim($_POST['username']) == '') showadmin('�����벻��Ϊ�ա�',$_SERVER['HTTP_REFERER']);
		if($_POST['newpw'] != $_POST['renewpw']) showadmin('�����������벻һ����',$_SERVER['HTTP_REFERER']);
		$db->query("update {$mydbpre}setting set val = '".$_POST['username']."' where keyword = 'username' and type = 'basic' limit 1");
		$db->query("update {$mydbpre}setting set val = '".md5($_POST['newpw'])."' where keyword = 'password' and type = 'basic' limit 1");
		showadmin('���³ɹ���');
	}
	else showadmin('��ľ����벻��ȷ��',$_SERVER['HTTP_REFERER']);
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=gb2312" />
<title>����Ա�����޸�</title>
<link type="text/css" rel="stylesheet" href="./css/guest.css">
</style>
</head>

<body>
<form id="form1" name="form1" method="post" action="" onsubmit="return checkip();">
  <table border="0" cellpadding="0" cellspacing="1" class="admintb" align="center">
    <tr>
      <td colspan="2" class="header">����Ա�����޸�</td>
    </tr>	
    <tr>
      <td style="width:150px;">����Ա��</td>
      <td><input name="username" type="text" id="username" value="<?php echo $vartem['setting']['basic']['username']['value'];?>" /></td>
    </tr>
    <tr>
      <td>������</td>
      <td><input type="password" name="oldpw" /></td>
    </tr>
    <tr>
      <td>������</td>
      <td><input type="password" name="newpw" /></td>
    </tr>
    <tr>
      <td>ȷ��������</td>
      <td><input type="password" name="renewpw" /></td>
    </tr>
	<tr>
      <td colspan="2">
  <div align="center">
    <input type="submit" name="submit" value=" �� �� " />
  </div>
  	</td>
    </tr>
  </table>
</form>
</body>
</html>
