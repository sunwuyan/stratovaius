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

if(!isset($vartem['setting']['basic']['avatars']['value']))
	$avatarsDir = 'images/avatars';
else 
	$avatarsDir = $vartem['setting']['basic']['avatars']['value'];	
if(!is_dir($avatarsDir))
	mkdir($avatarsdir,0777);
if(!preg_match('/\/$/',$avatarsDir))
	$avatarsDir .= '/';	

if(isset($_POST['submit'])){
	$error = false;
	if(!is_dir($_POST['adir'])){
		if(!mkdir($avatarsdir,0777)){
			$error = true;
		}
	}
	if(!$error)	{
		$db->query("update {$mydbpre}setting set val = '{$_POST['adir']}' where keyword = 'avatars' and type = 'basic'");
		cacheAvatars($_POST['adir']);
		showadmin('�޸ĳɹ�',referer());
	}
	else showadmin('�޷������ļ���',referer());
}
elseif(isset($_FILES['afile']['tmp_name'])){
	if(@getimagesize($_FILES['afile']['tmp_name'])){
		$userfile = $_FILES['afile']['tmp_name'];
		$ouserfile = uniqueFileName($avatarsDir.$_FILES['afile']['name']);
		if(copy($userfile,$ouserfile)){
			cacheAvatars($avatarsDir);
			showadmin('�ϴ��ɹ���',referer());
		}
		else showadmin('�޷������ļ���',referer());
	}
	else showadmin('���ϴ�ͼƬ',referer());
}
elseif(isset($_POST['submit3'])){
	//print_r($_POST);exit;
	if(!file_exists('cache/avatars.cache.php')){
		$avatarsArr = cacheAvatars($avatarsDir);
	}
	else include_once('cache/avatars.cache.php');
	
	if(isset($_POST['delIm'])){
		foreach ($_POST['delIm'] as $value){
			if(file_exists($value))
				unlink($value);
		}
	}
	
	foreach ($_POST['filename'] as $key => $value){
		if(!file_exists($avatarsArr[$key]['path']))
			continue;
		if(trim($value) != '' && $value != $avatarsArr[$key]['name'] && !preg_match('/\\\:\/\*\?"<>\|/',$value)){
			$tmpDir = dirname($avatarsArr[$key]['path']);
			$tmpFileName = basename($avatarsArr[$key]['path']);
			
			$fileNameHead = $tmpFileName;
			$fileNameTail = '';
			$fileNameArr = explode('.',$tmpFileName);
			if(count($fileNameArr) > 1){
				$fileNameTail =  '.'.array_pop($fileNameArr);
				$fileNameHead = implode('.',$fileNameArr);
			}
			
			$file = $tmpDir.'/'.$value.$fileNameTail;
			$file = uniqueFileName($file);
			rename($avatarsArr[$key]['path'],$file);
		}
	}
	if(!file_exists($_POST['aIndex']))
		$_POST['aIndex'] = null;
	cacheAvatars($avatarsDir,$_POST['aIndex']);
	showadmin('���³ɹ���',referer());
}

if(!file_exists('cache/avatars.cache.php'))
	$avatarsArr = cacheAvatars($avatarsDir);
else 
	include_once('cache/avatars.cache.php');

if((is_null($avatarsIndex) || !file_exists($avatarsIndex)) && !empty($avatarsArr[0]['path']))
	$avatarsIndex = $avatarsArr[0]['path'];
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=gb2312" />
<link type="text/css" rel="stylesheet" href="./css/guest.css">
<title>ͷ������</title>
</head>

<body>
<table width="98%" border="0" cellpadding="0" cellspacing="1" class="admintb">
    <tr>
      <td colspan="2" class="header">ͷ������</td>
    </tr>
    <tr>
	<form id="form1" name="form1" method="post" action="">
      <td width="20%">ͷ��Ŀ¼</td>
      <td style="word-break:break-all;word-wrap:break-word;">
        <input type="text" name="adir" value="<?php echo $avatarsDir;?>" />
        <input type="submit" name="submit" value="�޸�" />     
      </td> 
	</form>
    </tr>
    <tr>
	<form action="" method="post" enctype="multipart/form-data" name="form2" id="form2">
      <td>�ϴ�ͷ��</td>
      <td style="word-break:break-all;word-wrap:break-word;">
        <input type="file" name="afile" size="40" />
        <input type="submit" name="submit2" value="�ύ" />
      </td> 
	</form>
    </tr>
    <form action="" method="post" enctype="multipart/form-data" name="form3" id="form3">
    <tr>
      <td colspan="2" style="padding-bottom:10px;padding-top:10px;">
<?php 
foreach ($avatarsArr as $key => $val){
	echo "<div style=\"width:105px; height:135px;float:left;clear:none; margin:5px 5px 5px 5px;padding:5px;\"><img src=\"{$val['path']}\">
	<div style=\"font-size:12px;\">
	���ƣ�<input style=\"padding:0;\" type=\"text\" name=\"filename[]\" size=\"6\" value=\"{$val['name']}\">
	<input type=\"radio\" name=\"aIndex\" value=\"{$val['path']}\" ".(($val['path'] == $avatarsIndex)?"checked":'')."/>Ĭ��
  <input type=\"checkbox\" name=\"delIm[]\" value=\"{$val['path']}\" />ɾ��</div></div> ";
}
?>	
	</td>
	</tr>
	</tr>
	<td colspan="2">
    <div style="width:100%; clear:both;text-align:center;"><input type="submit" name="submit3" value=" �� �� " /></div>
	  </td>
    </tr>
    </form>
</table>
</body>
</html>
