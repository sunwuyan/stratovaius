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

if(isset($_POST['submitex'])){
	$str = '';
	$tables = array(0 => "{$mydbpre}setting", 1 => "{$mydbpre}post", 2 => "{$mydbpre}reply", 3 => "{$mydbpre}ban");
	//$sql = "show tables";
	//$query = $db->query($sql);
	//while($row = $db->fetch_array($query,MYSQL_NUM)){
	for($n=0;$n<count($tables);$n++){

		$sql2 = "show full columns from {$tables[$n]}";
		$query2 = $db->query($sql2);
		while($fieldrow = $db->fetch_array($query2)) {
			$tablefields[] = $fieldrow;
		}
		//print_r($tablefields);exit;

		$str .= "DROP TABLE IF EXISTS `{$tables[$n]}`;\r\n";

		$createtable = $db->query("SHOW CREATE TABLE {$tables[$n]}", 'SILENT');
		$create = $db->fetch_row($createtable);
		$str .= $create[1].";\r\n";

		$sql1 = "select * from {$tables[$n]}";
		$query1 = $db->query($sql1);
		$numfield = $db->num_fields($query1);

		while($row1 = $db->fetch_array($query1,MYSQL_NUM)){
			$str .= "insert into {$tables[$n]} values(";
			for($i = 0;$i<$numfield;$i++){
				if(preg_match('/char|text/i',$tablefields[$i]['Type']) && trim($row1[$i]) != ''){
					$row1[$i] = '0x'.bin2hex($row1[$i]);
					$str .= $row1[$i];
				}
				else{
					$row1[$i] = stripslashes($row1[$i]);
					$row1[$i] = addslashes($row1[$i]);
					$str .= "'".$row1[$i]."'";
				}

				if($i != $numfield-1) $str .= ",";
			}
			$str .= ");\r\n";

		}
		unset($tablefields);

	}
	$fp = fopen('data/'.date('Ymd').'_'.time().'.sql','wb');
	fwrite($fp,$str);
	fclose($fp);
	showadmin('数据备份完成。备份文件在 data 文件夹里。');
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=gb2312" />
<title>数据导入</title>
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
    <td class="header">数据备份</td>
  </tr>
  <tr>
    <td height="30">
	<div align="center">
    <input type="submit" name="submitex" value="点击开始备份" />
	</div>
	</td>
    </tr>
</table>  
  
</form>
</body>
</html>
