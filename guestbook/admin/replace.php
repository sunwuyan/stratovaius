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
	if(isset($_POST['b_id'])){
		foreach($_POST['b_id'] as $val){
			$db->query("delete from {$mydbpre}ban where b_id = ".$val);
			unset($_POST['b_val'][$val]);
		}
	}
	if(isset($_POST['b_val'])){
		foreach($_POST['b_val'] as $key => $value){
			$db->query("update {$mydbpre}ban set b_val = '".$value."',b_reval = '".$_POST['b_reval'][$key]."' where b_id = ".$key);
		}
	}
	if($_POST['new_val'] != ''){
		$db->query("insert into {$mydbpre}ban(b_val,b_reval) values('".$_POST['new_val']."','".$_POST['new_reval']."')");
	}
	showadmin('���³ɹ�',$_SERVER['HTTP_REFERER']);
}
$query = $db->query("select * from {$mydbpre}ban where b_type = 0");
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=gb2312" />
<title>������</title>
<link type="text/css" rel="stylesheet" href="./css/guest.css">
<script language="javascript">
function selectall(o){
	var obj = document.getElementsByTagName('input');
	for(var i=0;i<obj.length;i++){
		if(obj[i].type == 'checkbox'){
			obj[i].checked = o.checked;
		}
	}
}
</script>
</head>

<body>
<form id="form1" name="form1" method="post" action="">
  <table border="0" cellpadding="0" cellspacing="1" class="admintb">
    <tr>
      <td style="background-color:#DDDDDD; width:50px;"><input type="checkbox" name="checkbox" value="checkbox" onclick="selectall(this)" />ɾ</td>
	  <td class="header">�������</td>
      <td class="header">�滻Ϊ</td>
    </tr>
<?php while($row = $db->fetch_array($query)){?>	
    <tr>
      <td><input type="checkbox" name="b_id[]" value="<?php echo $row['b_id'];?>" /></td>
      <td><input name="b_val[<?php echo $row['b_id'];?>]" type="text" value="<?php echo $row['b_val'];?>" size="35" /></td>
      <td><input name="b_reval[<?php echo $row['b_id'];?>]" type="text" value="<?php echo $row['b_reval'];?>" size="35" /></td>
    </tr>
<?php }?>	
    <tr>
      <td>����</td>
      <td><input name="new_val" type="text" size="35" /></td>
      <td><input name="new_reval" type="text" size="35" /></td>
    </tr>
	<tr>
      <td colspan="3">
  <div align="center">
    <input type="submit" name="submit" value=" �� �� " />
  </div>
  	  </td>
    </tr>
  </table>
</form>
</body>
</html>
