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
	//print_r($_POST);exit;
	if(isset($_POST['id'])){
		foreach($_POST['id'] as $val){
			$db->query("delete from {$mydbpre}setting where id = '{$val}'");
			unset($_POST['val'][$val]);
		}
	}
	if(isset($_POST['val'])){
		foreach($_POST['val'] as $key => $value){
			$db->query("update {$mydbpre}setting set keyword = '".$value['keyword']."',val = '".$value['val']."', expansion = '".$value['expansion']."', expansion2 = '".intval($value['expansion2'])."' where id = '{$key}'");
		}
	}
	if($_POST['new_keyword'] != ''){
		$db->query("insert into {$mydbpre}setting (keyword, val, expansion, expansion2, type) values('".$_POST['new_keyword']."','".$_POST['new_val']."','".$_POST['expansion']."','".intval($_POST['expansion2'])."', 'topbar')");
	}
	showadmin('���³ɹ�',$_SERVER['HTTP_REFERER']);
}
elseif(isset($_POST['submit2'])){
	//print_r($_POST);exit;
	if(isset($_POST['id'])){
		foreach($_POST['id'] as $val){
			$db->query("delete from {$mydbpre}setting where id = '{$val}'");
			unset($_POST['val'][$val]);
		}
	}
	if(isset($_POST['val'])){
		foreach($_POST['val'] as $key => $value){
			$db->query("update {$mydbpre}setting set keyword = '".$value['keyword']."',val = '".$value['val']."', expansion = '".$value['expansion']."', expansion2 = '".intval($value['expansion2'])."' where id = '{$key}'");
		}
	}
	if($_POST['new_keyword'] != ''){
		$db->query("insert into {$mydbpre}setting (keyword, val, expansion, expansion2, type) values('".$_POST['new_keyword']."','".$_POST['new_val']."','".$_POST['expansion']."','".intval($_POST['expansion2'])."', 'bottombar')");
	}
	showadmin('���³ɹ�',$_SERVER['HTTP_REFERER']);
}	

$queryTop = $db->query("select * from {$mydbpre}setting where type = 'topbar' order by expansion2 asc");

$queryBottom = $db->query("select * from {$mydbpre}setting where type = 'bottombar' order by expansion2 asc");
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=gb2312" />
<title>��������</title>
<link type="text/css" rel="stylesheet" href="./css/guest.css">
<script language="javascript">
function selectall(o,scope){
	var objScope = document;
	if(arguments[1])
		var objScope = scope;
	var obj = objScope.getElementsByTagName('input');
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
      <td colspan="5" class="header">��������</td>
    </tr>
    <tr>
      <td width="9%"><input type="checkbox" name="checkbox" value="checkbox" onclick="selectall(this,document.form1)" />ɾ</td>
	  <td width="25%">����</td>
      <td width="40%">����</td>
      <td width="14%">Ŀ��</td>
      <td width="12%">��ʾ˳��</td>
    </tr>
<?php while($row = $db->fetch_array($queryTop)){?>	
    <tr>
      <td><input type="checkbox" name="id[]" value="<?php echo $row['id'];?>" /></td>
      <td><input type="text" name="val[<?php echo $row['id'];?>][keyword]" value="<?php echo $row['keyword'];?>" /></td>
      <td><input name="val[<?php echo $row['id'];?>][val]" type="text" value="<?php echo $row['val'];?>" size="40" /></td>
      <td>
	  <select name="val[<?php echo $row['id'];?>][expansion]">
        <option value="_self" <?php if($row['expansion'] == '_self') echo 'selected';?>>_self</option>
        <option value="_blank" <?php if($row['expansion'] == '_blank') echo 'selected';?>>_blank</option>
        <option value="_parent" <?php if($row['expansion'] == '_parent') echo 'selected';?>>_parent</option>
        <option value="_top" <?php if($row['expansion'] == '_top') echo 'selected';?>>_top</option>
      </select>
	 </td>
      <td><input name="val[<?php echo $row['id'];?>][expansion2]" type="text" size="5" value="<?php echo $row['expansion2'];?>" /></td>
    </tr>
<?php }?>	
    <tr>
      <td>����</td>
      <td><input name="new_keyword" type="text" id="new_keyword" /></td>
      <td><input name="new_val" type="text" id="new_val" size="40" /></td>
      <td>
	  <select name="expansion">
        <option value="_seft" selected="selected">_seft</option>
        <option value="_blank">_blank</option>
        <option value="_parent">_parent</option>
        <option value="_top">_top</option>
      </select>
      </td>
      <td><input name="expansion2" type="text" value="0" size="5" /></td>
    </tr>
	<tr>
      <td colspan="5">
  <div align="center">
    <input type="submit" name="submit" value=" �� �� " />
  </div>
  	</td>
    </tr>
  </table>
</form>
<br>
<form id="form2" name="form2" method="post" action="">
  <table border="0" cellpadding="0" cellspacing="1" class="admintb">
    <tr>
      <td colspan="5" class="header">�ײ�����</td>
    </tr>
    <tr>
      <td width="9%"><input type="checkbox" name="checkbox" value="checkbox" onclick="selectall(this,document.form2)" />ɾ</td>
	  <td width="25%">����</td>
      <td width="40%">����</td>
      <td width="14%">Ŀ��</td>
      <td width="12%">��ʾ˳��</td>
    </tr>
<?php while($row = $db->fetch_array($queryBottom)){?>	
    <tr>
      <td><input type="checkbox" name="id[]" value="<?php echo $row['id'];?>" /></td>
      <td><input type="text" name="val[<?php echo $row['id'];?>][keyword]" value="<?php echo $row['keyword'];?>" /></td>
      <td><input name="val[<?php echo $row['id'];?>][val]" type="text" value="<?php echo $row['val'];?>" size="40" /></td>
      <td>
	  <select name="val[<?php echo $row['id'];?>][expansion]">
        <option value="_self" <?php if($row['expansion'] == '_self') echo 'selected';?>>_self</option>
        <option value="_blank" <?php if($row['expansion'] == '_blank') echo 'selected';?>>_blank</option>
        <option value="_parent" <?php if($row['expansion'] == '_parent') echo 'selected';?>>_parent</option>
        <option value="_top" <?php if($row['expansion'] == '_top') echo 'selected';?>>_top</option>
      </select>
	  </td>
      <td><input name="val[<?php echo $row['id'];?>][expansion2]" type="text" size="5" value="<?php echo $row['expansion2'];?>" /></td>
    </tr>
<?php }?>	
    <tr>
      <td>����</td>
      <td><input name="new_keyword" type="text" id="new_keyword" /></td>
      <td><input name="new_val" type="text" id="new_val" size="40" /></td>
      <td>
	  <select name="expansion">
        <option value="_seft" selected="selected">_seft</option>
        <option value="_blank">_blank</option>
        <option value="_parent">_parent</option>
        <option value="_top">_top</option>
      </select></td>
      <td><input name="expansion2" type="text" value="0" size="5" /></td>
    </tr>
	<tr>
      <td colspan="5">
  <div align="center">
    <input type="submit" name="submit2" value=" �� �� " />
  </div>
  	</td>
    </tr>
  </table>
</form>
</body>
</html>