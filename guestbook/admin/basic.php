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

if(isset($_POST['name'])){
	if(!preg_match('/^\d+$/',$_POST['eachpage'])) showadmin('ÿҳ��ʾ������������Ϊ����',$_SERVER['HTTP_REFERER']);
	if(!preg_match('/^\d+$/',$_POST['maxlen'])) showadmin('ÿ����������ַ�������Ϊ����',$_SERVER['HTTP_REFERER']);
	if(!preg_match('/^\d+$/',$_POST['minsec'])) showadmin('���Լ��ʱ�����Ϊ����',$_SERVER['HTTP_REFERER']);
	foreach ($_POST as $key => $value){
		$sql = "update {$mydbpre}setting set val = '".$value."' where keyword = '$key' and type = 'basic' limit 1";
		$db->query($sql);
	}
	showadmin('���³ɹ�',$_SERVER['HTTP_REFERER']);
}
//$arr = setting();
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=gb2312" />
<link type="text/css" rel="stylesheet" href="./css/guest.css">
<title>��������</title>
</head>

<body>
<form id="form1" name="form1" method="post" action="">
  <table width="98%" border="0" cellpadding="0" cellspacing="1" class="admintb">
    <tr>
      <td colspan="2" class="header">ϵͳ����</td>
    </tr>
    <tr>
      <td>���԰�����</td>
      <td><input name="name" type="text" id="name" value="<?php echo $vartem['setting']['basic']['name']['value'];?>" /></td>
    </tr>
    <tr>
      <td>���԰�URL </td>
      <td><input name="url" type="text" id="url"  value="<?php echo $vartem['setting']['basic']['url']['value'];?>" size="40" /></td>
    </tr>
    <tr>
      <td>Logo</td>
      <td><input name="logo" type="text" id="logo" size="45" value="<?php echo $vartem['setting']['basic']['logo']['value'];?>" /></td>
    </tr>
    <tr>
      <td>��վ������</td>
      <td><input name="icp" type="text" id="icp"  value="<?php echo $vartem['setting']['basic']['icp']['value'];?>" />
      ���û�У�������</td>
    </tr>
    <tr>
      <td>���߱༭��</td>
      <td>
        <input type="radio" name="fck" value="2" <?php if($vartem['setting']['basic']['fck']['value']==2) echo "checked";?>/>htmlerea�༭��(�Ƽ�)
      	<input type="radio" name="fck" value="1" <?php if($vartem['setting']['basic']['fck']['value']==1) echo "checked";?>/>FCK�༭��
        <input type="radio" name="fck" value="0" <?php if($vartem['setting']['basic']['fck']['value']==0) echo "checked";?>/>��ʹ��html�༭��          </td>
    </tr>
    <tr>
      <td>ÿҳ��ʾ��������</td>
      <td><input name="eachpage" type="text" size="10" id="eachpage" value="<?php echo $vartem['setting']['basic']['eachpage']['value'];?>" /></td>
    </tr>
    <tr>
      <td>������֤��</td>
      <td>
      	<input type="radio" name="rcode" value="1" <?php if($vartem['setting']['basic']['rcode']['value']==1) echo "checked";?>/>����(�Ƽ�)
        <input type="radio" name="rcode" value="0" <?php if($vartem['setting']['basic']['rcode']['value']==0) echo "checked";?>/>�ر�  </td>
    </tr>
    <tr>
      <td>ÿ����������ַ���</td>
      <td><input name="maxlen" type="text" id="maxlen" size="10" value="<?php echo $vartem['setting']['basic']['maxlen']['value'];?>" />
      ��(0Ϊ������) </td>
    </tr>
    <tr>
      <td>���Լ��ʱ��</td>
      <td><input name="minsec" type="text" id="minsec" value="<?php echo $vartem['setting']['basic']['minsec']['value'];?>" size="10" /> 
      ��(0Ϊ������) </td>
    </tr>
    <tr>
      <td>վ��email</td>
      <td><input name="email" type="text" id="email" value="<?php echo $vartem['setting']['basic']['email']['value'];?>"/></td>
    </tr>
    <tr>
      <td colspan="2">
	  <div style="text-align:center;"><input type="submit" id="submit" value=" �� �� " /></div>
	  </td>
    </tr>
  </table>
</form>
</body>
</html>
