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
	$pattern = $replacement = array();
	foreach ($_POST['css_val'] as $key => $value){
		$pattern[] = '/\{'.preg_quote($value['name'],'/').'\}/';
		$replacement[] = $value['val'];
		$sql = "update {$mydbpre}setting set val = '{$value['val']}' where id = '{$key}'";
		$db->query($sql);
	}
	$cssContent = read_file('templates/css.html');
	$cssContent = preg_replace($pattern, $replacement,$cssContent);
	writeFile('cache/guest.css',$cssContent);
	showadmin('更新成功',referer());
}
$cssTypeArr = array(
	'fontfamily' => '字体设置',
	'fontsize' => '字体设置',
	'colorfont' => '字体颜色设置',
	'colorborder' => '边框颜色设置',
	'colorbg' => '背景颜色设置',
	'dimensions' => '宽度设置'
);
$query = $db->query("select * from {$mydbpre}setting where type = 'css' order by expansion2 desc, keyword");
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=gb2312" />
<title>字体颜色设置</title>
<link type="text/css" rel="stylesheet" href="./css/guest.css">
<script src="javascript/Jphp.js" language="javascript"></script>
<script language="javascript">
function selectall(o){
	var obj = document.getElementsByTagName('input');
	for(var i=0;i<obj.length;i++){
		if(obj[i].type == 'checkbox'){
			obj[i].checked = o.checked;
		}
	}
}

function changeStyle(objfrom,objto,type){
	switch(type){
		case 'color':
			if(!preg_match(/^#([\da-fA-F]{6}|[\da-fA-F]{3})$/,objfrom.value)){
				alert('颜色代码不正确。');
				objfrom.value = strtoupper(objto.style.background);
				objfrom.select();
				return;
			}
			objto.style.background = objfrom.value;
			break;
		case 'font':
			if(!preg_match(/^\d+(\.\d+)?(px|em|ex|pt|pc|in|mm|cm)?$/i,objfrom.value)){
				alert('字体大小不对。');
				objfrom.value = objto.style.fontSize;
				objfrom.select();
				return;
			}
			objto.style.fontSize = objfrom.value;
			break;
	}
}
</script>
</head>

<body>
<form id="form1" name="form1" method="post" action="">
  <table border="0" cellpadding="0" cellspacing="1" class="admintb">
    <tr>
	  <td colspan="2" class="header">文字及颜色设置</td>
    </tr>
<?php 
while($row = $db->fetch_array($query)){
	if(!isset($csstype)){
		$csstype = $cssTypeArr[$row['expansion2']];
		echo "    <tr>\n      <td colspan=2 style=\"background-color:#eeeeee;\"><b>{$csstype}</b></td>\n    </tr>\n";
	}
	elseif($csstype != $cssTypeArr[$row['expansion2']]){
		$csstype = $cssTypeArr[$row['expansion2']];
		echo "    <tr>\n      <td colspan=2 style=\"background-color:#eeeeee;\"><b>{$csstype}</b></td>\n    </tr>\n";
	}
?>	
    <tr>
      <td><?php echo $row['expansion'];?>：<?php echo $row['keyword'];?></td>
      <td>
      <input name="css_val[<?php echo $row['id'];?>][val]" type="text" value="<?php echo $row['val'];?>" size="35" <?php if(strstr($row['expansion2'],'color')){?>onchange="changeStyle(this,document.form1.style_<?php echo $row['id'];?>,'color');" <?php }elseif($row['expansion2'] == 'fontsize'){?>onchange="changeStyle(this,document.getElementById('style_<?php echo $row['id'];?>'),'font');" <?php }?> style="margin-right:5px;float:left; clear:none;"/>
      <input type="hidden" name="css_val[<?php echo $row['id'];?>][name]" value="<?php echo $row['keyword'];?>" />
      <?php if(strstr($row['expansion2'],'color')){?>
      <input type="button" name="style_<?php echo $row['id'];?>" style="background: <?php echo $row['val'];?>; width: 20px;border: 1px solid #6699CC;" disabled>
      <?php }
      elseif($row['expansion2'] == 'fontsize'){?>
      <div id="style_<?php echo $row['id'];?>" style="padding:1px; float:left; clear:none;border: 1px solid #6699CC; font-size:<?php echo $row['val'];?>">php爱好者</div>
      <?}?>
      </td>
    </tr>
<?php }?>
	<tr>
      <td colspan="3">
  <div align="center">
    <input type="submit" name="submit" value=" 提 交 " />
  </div>  	  </td>
    </tr>
  </table>
</form>
</body>
</html>
