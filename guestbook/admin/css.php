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
	showadmin('���³ɹ�',referer());
}
$cssTypeArr = array(
	'fontfamily' => '��������',
	'fontsize' => '��������',
	'colorfont' => '������ɫ����',
	'colorborder' => '�߿���ɫ����',
	'colorbg' => '������ɫ����',
	'dimensions' => '�������'
);
$query = $db->query("select * from {$mydbpre}setting where type = 'css' order by expansion2 desc, keyword");
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=gb2312" />
<title>������ɫ����</title>
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
				alert('��ɫ���벻��ȷ��');
				objfrom.value = strtoupper(objto.style.background);
				objfrom.select();
				return;
			}
			objto.style.background = objfrom.value;
			break;
		case 'font':
			if(!preg_match(/^\d+(\.\d+)?(px|em|ex|pt|pc|in|mm|cm)?$/i,objfrom.value)){
				alert('�����С���ԡ�');
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
	  <td colspan="2" class="header">���ּ���ɫ����</td>
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
      <td><?php echo $row['expansion'];?>��<?php echo $row['keyword'];?></td>
      <td>
      <input name="css_val[<?php echo $row['id'];?>][val]" type="text" value="<?php echo $row['val'];?>" size="35" <?php if(strstr($row['expansion2'],'color')){?>onchange="changeStyle(this,document.form1.style_<?php echo $row['id'];?>,'color');" <?php }elseif($row['expansion2'] == 'fontsize'){?>onchange="changeStyle(this,document.getElementById('style_<?php echo $row['id'];?>'),'font');" <?php }?> style="margin-right:5px;float:left; clear:none;"/>
      <input type="hidden" name="css_val[<?php echo $row['id'];?>][name]" value="<?php echo $row['keyword'];?>" />
      <?php if(strstr($row['expansion2'],'color')){?>
      <input type="button" name="style_<?php echo $row['id'];?>" style="background: <?php echo $row['val'];?>; width: 20px;border: 1px solid #6699CC;" disabled>
      <?php }
      elseif($row['expansion2'] == 'fontsize'){?>
      <div id="style_<?php echo $row['id'];?>" style="padding:1px; float:left; clear:none;border: 1px solid #6699CC; font-size:<?php echo $row['val'];?>">php������</div>
      <?}?>
      </td>
    </tr>
<?php }?>
	<tr>
      <td colspan="3">
  <div align="center">
    <input type="submit" name="submit" value=" �� �� " />
  </div>  	  </td>
    </tr>
  </table>
</form>
</body>
</html>
