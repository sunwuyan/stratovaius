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

$dir = './data/';
if(isset($_POST['d_id'])){
	foreach($_POST['d_id'] as $val){
		unlink($dir.$val);
	}
	showadmin('删除成功。',$_SERVER['HTTP_REFERER']);
}

if(isset($_GET['infile'])){
	$dataurl = $dir.$_GET['infile'];
	$str = file_get_contents($dataurl);

	$arr = explode(";\r\n",$str);


	for($i=0;$i<count($arr);$i++){
		if(trim($arr[$i]) != ''){
			$db->query($arr[$i]);
		}
	}
	showadmin('资料恢复完成。');
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
    <td height="22" colspan="5" class="header">数据恢复</td>
  </tr>
  <tr>
    <td width="10%"><input type="checkbox" name="checkbox" value="checkbox" onclick="selectall(this)" />删</td>
    <td width="33%">文件名</td>
    <td width="26%">时间</td>
    <td width="19%">尺寸</td>
    <td width="12%">操作</td>
  </tr>
<?php
$mydir=dir($dir);
while($file=$mydir->read()){
	if($file != "." && $file != ".." && preg_match('/^\d{8}_\d{10}\.sql$/',$file))
	{
?> 
  <tr>
    <td><input type="checkbox" name="d_id[]" value="<?php echo $file;?>" /></td>
    <td><?php echo $file;?></td>
    <td>
    <?php 
    $arr1 = explode('.',$file);
    $arr2 = explode('_',$arr1[0]);
    echo date('Y-m-d H:i',$arr2[1]);
    ?>    </td>
    <td>
	<?php 
	if(filesize($dir.$file) < 1000000) echo sprintf('%.1f',filesize($dir.$file)/1000).' KB';
	else echo sprintf('%.3f',filesize($dir.$file)/1000000).' MB';
	?> </td>
    <td><a href="admincp.php?act=import&infile=<?php echo $file;?>">导入</a></td>
  </tr>
<?php 
	}
}
$mydir->close();
?>
 <tr>
    <td colspan="5">
  <div align="center">
    <input type="submit" name="submit" value=" 提 交 " />
  </div>
  	</td>
  </tr>  
</table>
</form>
</body>
</html>
