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

if(!defined('IN_GB'))
	exit('Access denied!');

if(isset($_POST['submit'])){
	if(isset($_POST['b_id'])){
		foreach($_POST['b_id'] as $val){
			$db->query("delete from {$mydbpre}ban where b_id = ".$val);
			unset($_POST['b_val'][$val]);
		}
	}
	if(isset($_POST['b_reval'])){
		foreach($_POST['b_reval'] as $key => $value){
			$db->query("update {$mydbpre}ban set b_reval = '".$value."' where b_id = ".$key);
		}
	}
	if($_POST['new_reval'] != ''){
		$db->query("insert into {$mydbpre}ban(b_val,b_reval,b_type) values('','".$_POST['new_reval']."',2)");
	}
	showadmin('更新成功',$_SERVER['HTTP_REFERER']);
}
$query = $db->query("select * from {$mydbpre}ban where b_type = 2");
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=gb2312" />
<title>限制ip</title>
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
function checkip(){
	var obj = document.form1.new_reval;
	var ip = obj.value;
	if(obj.value != ''){
		var iparr = ip.split(".");
		if(iparr.length != 4){
			alert("你输入的ip不正确");
			obj.select();
			return false;
		}
		for(var i=0;i<iparr.length;i++){
			if((isNaN(iparr[i]) && iparr[i] != '*') || (!isNaN(iparr[i]) && (iparr[i] < 0 || iparr[i] > 255))){
				alert("你输入的ip不正确");
				obj.select();
				return false;
			}
		}
	}
	return true;
}
</script>
</head>

<body>
<form id="form1" name="form1" method="post" action="" onsubmit="return checkip();">
  <table border="0" cellpadding="0" cellspacing="1" class="admintb">
    <tr>
      <td style="background-color:#DDDDDD; width:50px;"><input type="checkbox" name="checkbox" value="checkbox" onclick="selectall(this)" />删</td>
	  <td class="header">限制ip</td>
    </tr>
<?php while($row = $db->fetch_array($query)){?>	
    <tr>
      <td><input type="checkbox" name="b_id[]" value="<?php echo $row['b_id'];?>" /></td>
      <td><input type="text" name="b_reval[<?php echo $row['b_id'];?>]" value="<?php echo $row['b_reval'];?>" /></td>
    </tr>
<?php }?>	
    <tr>
      <td>新增</td>
      <td><input type="text" name="new_reval" /> 提示：如：192.168.1.1 如果没有规定范围可以用*代替，如：192.*.168.1</td>
    </tr>
	<tr>
      <td colspan="2">
	  <div style="text-align:center;"><input type="submit" name="submit" value=" 提 交 " /></div>
	  </td>
    </tr>
  </table>
</form>
</body>
</html>