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
		showadmin('修改成功',referer());
	}
	else showadmin('无法创建文件夹',referer());
}
elseif(isset($_FILES['afile']['tmp_name'])){
	if(@getimagesize($_FILES['afile']['tmp_name'])){
		$userfile = $_FILES['afile']['tmp_name'];
		$ouserfile = uniqueFileName($avatarsDir.$_FILES['afile']['name']);
		if(copy($userfile,$ouserfile)){
			cacheAvatars($avatarsDir);
			showadmin('上传成功。',referer());
		}
		else showadmin('无法复制文件。',referer());
	}
	else showadmin('请上传图片',referer());
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
	showadmin('更新成功。',referer());
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
<title>头像设置</title>
</head>

<body>
<table width="98%" border="0" cellpadding="0" cellspacing="1" class="admintb">
    <tr>
      <td colspan="2" class="header">头像设置</td>
    </tr>
    <tr>
	<form id="form1" name="form1" method="post" action="">
      <td width="20%">头像目录</td>
      <td style="word-break:break-all;word-wrap:break-word;">
        <input type="text" name="adir" value="<?php echo $avatarsDir;?>" />
        <input type="submit" name="submit" value="修改" />     
      </td> 
	</form>
    </tr>
    <tr>
	<form action="" method="post" enctype="multipart/form-data" name="form2" id="form2">
      <td>上传头像</td>
      <td style="word-break:break-all;word-wrap:break-word;">
        <input type="file" name="afile" size="40" />
        <input type="submit" name="submit2" value="提交" />
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
	名称：<input style=\"padding:0;\" type=\"text\" name=\"filename[]\" size=\"6\" value=\"{$val['name']}\">
	<input type=\"radio\" name=\"aIndex\" value=\"{$val['path']}\" ".(($val['path'] == $avatarsIndex)?"checked":'')."/>默认
  <input type=\"checkbox\" name=\"delIm[]\" value=\"{$val['path']}\" />删除</div></div> ";
}
?>	
	</td>
	</tr>
	</tr>
	<td colspan="2">
    <div style="width:100%; clear:both;text-align:center;"><input type="submit" name="submit3" value=" 提 交 " /></div>
	  </td>
    </tr>
    </form>
</table>
</body>
</html>
