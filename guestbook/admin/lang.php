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
	if(isset($_POST['val'])){
		foreach($_POST['val'] as $key => $value){
			$db->query("update {$mydbpre}setting set val = '".$value."' where id = '{$key}'");
		}
	
		$sql = "select * from {$mydbpre}setting where type = 'lang' and expansion = '".$_POST['langtype']."' order by keyword asc";
		$query = $db->query($sql);
		$langu = array();
		while($row = $db->fetch_array($query)){
			$langu[$row['keyword']] = $row['val'];
		}	
		
		switch ($_POST['langtype']){
			case 0:
				$expArr = var_export($langu,true);
				$expStr = "<?php\n\$langArr = ".$expArr.";\n?>";
				$expFileUrl = 'cache/lang_zh.php';
				break;
			case 1:
				$expArr = var_export($langu,true);
				$expStr = "<?php\n\$mearr = ".$expArr.";\n?>";
				$expFileUrl = 'cache/message_zh.php';
				break;
			case 2:
				$jslangarr = array();
				foreach($langu as $jskey => $jsvalue){
					$jslangarr[] = '"'.str_replace('"','\"',$jskey).'"'.":".'"'.str_replace('"','\"',$jsvalue).'"';
				}
				$jslangarr = array_reverse($jslangarr);
				$jsstr = implode(",\n	",$jslangarr);
				$expStr = "var langjs = {\n	".$jsstr."\n};";
				$expFileUrl = 'cache/lang.js';
				break;
			default: exit;	
		}
		writeFile($expFileUrl,$expStr);
	}
	showadmin('更新成功',$_SERVER['HTTP_REFERER']);
}/*
elseif(isset($_POST['submit2'])){
	if(isset($_POST['val'])){
		foreach($_POST['val'] as $key => $value){
			$db->query("update {$mydbpre}setting set val = '".$value."' where id = '{$key}'");
		}
	}
	showadmin('更新成功',$_SERVER['HTTP_REFERER']);
}*/

$query = $db->query("select * from {$mydbpre}setting where type = 'lang' and expansion = 0 order by val asc");

$query1 = $db->query("select * from {$mydbpre}setting where type = 'lang' and expansion = 1 order by val asc");

$query2 = $db->query("select * from {$mydbpre}setting where type = 'lang' and expansion = 2 order by val asc");
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=gb2312" />
<title>界面语言修改</title>
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
  <table class="admintb" border="0" cellpadding="0" cellspacing="1"  style="float:left; clear:none; width:30%;">
<form id="form1" name="form1" method="post" action="">
    <tr>
      <td class="header">
	  界面语言
	  </td>
    </tr>
<?php while($row = $db->fetch_array($query)){?>	
    <tr>
      <td><input type="text" name="val[<?php echo $row['id'];?>]" value="<?php echo $row['val'];?>" style="width:95%;" /></td>
    </tr>
<?php }?>	
    <tr>
      <td>
	  <div style="text-align:center;">
    <input type="submit" name="submit" value=" 修 改 " />
    <input name="langtype" type="hidden" id="langtype" value="0" />
	  </div>
	  </td>
    </tr>
</form>
  </table>
  
  <table class="admintb" border="0" cellpadding="0" cellspacing="1" style="float:left; clear:none; width:30%;">
<form id="form2" name="form2" method="post" action="">
    <tr>
      <td class="header">
	  信息提示
	  </td>
    </tr>
<?php while($row = $db->fetch_array($query1)){?>	
    <tr>
      <td>
      <textarea name="val[<?php echo $row['id'];?>]" style="width:95%;"><?php echo $row['val'];?></textarea>
	  </td>
    </tr>
<?php }?>	
    <tr>
      <td>
	  <div style="text-align:center;">
    <input type="submit" name="submit" value=" 修 改 " />
    <input name="langtype" type="hidden" id="langtype" value="1" />
	  </div>
	  </td>
    </tr>
</form>
  </table>
  
  <table class="admintb" border="0" cellpadding="0" cellspacing="1" style="float:left; clear:none; width:30%;">
<form id="form3" name="form3" method="post" action="">
    <tr>
      <td class="header">
	  弹出框提示
	  </td>
    </tr>
<?php while($row = $db->fetch_array($query2)){?>	
    <tr>
      <td>
      <textarea name="val[<?php echo $row['id'];?>]" style="width:95%;"><?php echo $row['val'];?></textarea>
	  </td>
    </tr>
<?php }?>	
    <tr>
      <td>
	  <div style="text-align:center;">
    <input type="submit" name="submit" value=" 修 改 " />
    <input name="langtype" type="hidden" id="langtype" value="2" />
	  </div>
	  </td>
    </tr>
</form>
  </table>
</body>
</html>
