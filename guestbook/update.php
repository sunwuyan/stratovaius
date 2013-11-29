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

define('IN_GB', TRUE);
require_once('include/config.php');
require_once('include/function.php');
require_once('include/class.php');

if(!isset($_REQUEST['step']) || !in_array($_REQUEST['step'],array(1,2,3)))
	$step = 1;
else
	$step = $_REQUEST['step'];

if(isset($_REQUEST['update']) && !file_exists('cache/update_2.0.lock') && VERSION != '2.0'){
	$db = new mysql();
	$db->connect($mydbhost, $mydbuser, $mydbpw, $mydbname,$mydbcharset);
	
	$sql_post = array(
		"ALTER TABLE `{$mydbpre}post` ADD `p_editor` TINYINT( 3 ) UNSIGNED NOT NULL DEFAULT '1'",
		"ALTER TABLE `{$mydbpre}post` CHANGE `p_email` `p_email` VARCHAR( 20 ) NULL ,
		CHANGE `p_qq` `p_qq` VARCHAR( 20 ) NULL ,CHANGE `p_homepage` `p_homepage` VARCHAR( 100 ) NULL ,CHANGE `p_title` `p_title` VARCHAR( 160 ) NULL",
		"ALTER TABLE `{$mydbpre}post` CHANGE `p_image` `p_image` VARCHAR( 255 ) NOT NULL",
		"UPDATE `{$mydbpre}post` SET `p_image` = concat( 'images/avatars/', `p_image` , '.gif' )",
		"ALTER TABLE `{$mydbpre}post` CHANGE `p_date` `p_date` VARCHAR( 20 ) NOT NULL DEFAULT '0000-00-00 00:00:00'",
		"UPDATE `{$mydbpre}post` SET `p_date` = UNIX_TIMESTAMP( `p_date` )",
		"ALTER TABLE `{$mydbpre}post` CHANGE `p_date` `p_date` INT( 10 ) UNSIGNED NOT NULL DEFAULT '0'",
		"UPDATE `{$mydbpre}post` SET `p_content` = REPLACE(`p_content`,'/HtmlEditor/smile/','/fckeditor/editor/images/smiley/smile/')",
		"INSERT INTO `{$mydbpre}post` VALUES (NULL, '我不是鱼', 'deng5765@163.com', '245821218', 'http://www.phpfans.net', 'images/avatars/01.gif', '感谢你使用php爱好者留言簿', '<P>非常感谢你对php爱好者留言簿的支持</P><P>在使用中遇到任何问题,请到</P><P><A target=\"_blank\" href=\"http://www.phpfans.net/gustbook/\">http://www.phpfans.net/gustbook/</A>&nbsp;</P><P>或者</P><P><A target=\"_blank\" href=\"http://www.phpfans.net/bbs/forumdisplay.php?fid=24\">http://www.phpfans.net/bbs/forumdisplay.php?fid=24</A>&nbsp;</P><P>获得帮助或提出你的建议.</P><p>同时请大家密切关注php爱好者留言簿的更新情况</P><P><a target=\"_blank\" href=\"http://www.phpfans.net/bbs/viewthread.php?tid=6345&extra=page%3D1\">http://www.phpfans.net/bbs/viewthread.php?tid=6345&extra=page%3D1</a></P><p>还有欢迎大家光临php爱好者站</P><P><A target=\"_blank\" href=\"http://www.phpfans.net\">http://www.phpfans.net</A></P><p>php爱好者站论坛</P><P><A target=\"_blank\" href=\"http://www.phpfans.net/bbs/\">http://www.phpfans.net/bbs/</A>&nbsp;<img src=\"fckeditor/editor/images/smiley/smile/smile13.gif\"></P>', '127.0.0.1', ".time().",1,1)"
	);
	
	$sql_setting = array(
		"ALTER TABLE `{$mydbpre}setting` CHANGE `keyword` `keyword` VARCHAR( 255 ) NOT NULL",
		"ALTER TABLE `{$mydbpre}setting` ADD `id` INT( 10 ) UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY FIRST",
		"ALTER TABLE `{$mydbpre}setting` ADD `expansion` VARCHAR( 255 ) NULL ,ADD `expansion2` VARCHAR( 255 ) NULL ,ADD `type` VARCHAR( 15 ) NOT NULL",
		"UPDATE `{$mydbpre}setting` SET `type` = 'basic'",
		"UPDATE `{$mydbpre}setting` SET `val` = '2' WHERE `keyword` = 'fck' LIMIT 1;",
		//-----
		"INSERT INTO `{$mydbpre}setting` VALUES (NULL, 'rcode', '1', NULL, NULL, 'basic');",
		"INSERT INTO `{$mydbpre}setting` VALUES (NULL, 'logo', 'images/logo.gif', NULL, NULL, 'basic');",
		"INSERT INTO `{$mydbpre}setting` VALUES (NULL, 'avatars', 'images/avatars/', NULL, NULL, 'basic');",
		"INSERT INTO `{$mydbpre}setting` VALUES (NULL, '关于我们', '#', '_self', '0', 'bottombar');",
		"INSERT INTO `{$mydbpre}setting` VALUES (NULL, '联系我们', 'mailto:deng5765@163.com', '_self', '1', 'bottombar');",
		"INSERT INTO `{$mydbpre}setting` VALUES (NULL, '访客留言', './', '_seft', '2', 'bottombar');",
		"INSERT INTO `{$mydbpre}setting` VALUES (NULL, 'headborder', '#CCCCCC', '头部边框颜色', 'colorborder', 'css');",
		"INSERT INTO `{$mydbpre}setting` VALUES (NULL, 'tailborder', '#CCCCCC', '尾部边框颜色', 'colorborder', 'css');",
		"INSERT INTO `{$mydbpre}setting` VALUES (NULL, 'tableboder', '#52628f', '表格边框颜色', 'colorborder', 'css');",
		"INSERT INTO `{$mydbpre}setting` VALUES (NULL, 'navigationborder', '#6B7291', '导航条边框颜色', 'colorborder', 'css');",
		"INSERT INTO `{$mydbpre}setting` VALUES (NULL, 'pageborder', '#6B7291', '分页边框颜色', 'colorborder', 'css');",
		"INSERT INTO `{$mydbpre}setting` VALUES (NULL, 'bigfont', '14px', '大号字体', 'fontsize', 'css');",
		"INSERT INTO `{$mydbpre}setting` VALUES (NULL, 'smallfont', '12px', '小号字体', 'fontsize', 'css');",
		"INSERT INTO `{$mydbpre}setting` VALUES (NULL, 'width', '775', '留言板宽度', 'dimensions', 'css');",
		"INSERT INTO `{$mydbpre}setting` VALUES (NULL, 'msgleftbg', '#F9FAFB', '留言左边背景颜色', 'colorbg', 'css');",
		"INSERT INTO `{$mydbpre}setting` VALUES (NULL, 'headbg', '#f0f1f3', '头部背景颜色', 'colorbg', 'css');",
		"INSERT INTO `{$mydbpre}setting` VALUES (NULL, 'tailbg', '#FFFFFF', '尾部背景颜色', 'colorbg', 'css');",
		"INSERT INTO `{$mydbpre}setting` VALUES (NULL, 'tableheader', '#6B7291', '表格头背景颜色', 'colorbg', 'css');",
		"INSERT INTO `{$mydbpre}setting` VALUES (NULL, 'headcolor', '#414141', '头部字体颜色', 'colorfont', 'css');",
		"INSERT INTO `{$mydbpre}setting` VALUES (NULL, 'msgcolor', '#67678F', '留言字体颜色', 'colorfont', 'css');",
		"INSERT INTO `{$mydbpre}setting` VALUES (NULL, 'replyborder', '#698cc3', '管理员回复边框颜色', 'colorborder', 'css');",
		"INSERT INTO `{$mydbpre}setting` VALUES (NULL, 'replybg', '#f6fafd', '管理员回复背景颜色', 'colorbg', 'css');",
		"INSERT INTO `{$mydbpre}setting` VALUES (NULL, 'replycolor', '#4875b7', '管理员回复字体颜色', 'colorfont', 'css');",
		"INSERT INTO `{$mydbpre}setting` VALUES (NULL, 'tbinborder', '#d6e0ef', '表格内边框颜色', 'colorborder', 'css');",
		"INSERT INTO `{$mydbpre}setting` VALUES (NULL, 'taillinkcolor', '#52628f', '尾部链接字体颜色', 'colorfont', 'css');",
		"INSERT INTO `{$mydbpre}setting` VALUES (NULL, 'tableheadercolor', '#FFFFFF', '表格头字体颜色', 'colorfont', 'css');",
		"INSERT INTO `{$mydbpre}setting` VALUES (NULL, 'navigationcolor', '#660066', '导航条字体颜色', 'colorfont', 'css');",
		"INSERT INTO `{$mydbpre}setting` VALUES (NULL, 'bodybg', '#FFFFFF', '页面背景颜色', 'colorbg', 'css');",
		"INSERT INTO `{$mydbpre}setting` VALUES (NULL, 'defaultfont', 'Tahoma, Verdana', '默认字体', 'fontfamily', 'css');",
		"INSERT INTO `{$mydbpre}setting` VALUES (NULL, 'msgrightbg', '#FFFFFF', '留言右边背景颜色', 'colorbg', 'css');",
		"INSERT INTO `{$mydbpre}setting` VALUES (NULL, 'msgleftcolor', '#000000', '留言左边字体颜色', 'colorfont', 'css');",
		"INSERT INTO `{$mydbpre}setting` VALUES (NULL, 'msgtopcolor', '#000000', '留言上边字体颜色', 'colorfont', 'css');",
		"INSERT INTO `{$mydbpre}setting` VALUES (NULL, 'msgbottomcolor', '#000000', '留言下边字体颜色', 'colorfont', 'css');",
		"INSERT INTO `{$mydbpre}setting` VALUES (NULL, 'pagelinkcolor', '#660066', '分页链接字体颜色', 'colorfont', 'css');",
		"INSERT INTO `{$mydbpre}setting` VALUES (NULL, 'pagecolor', '#000000', '分页字体颜色', 'colorfont', 'css');",
		"INSERT INTO `{$mydbpre}setting` VALUES (NULL, 'footcolor', '#000000', '尾部字体颜色', 'colorfont', 'css');",
		"INSERT INTO `{$mydbpre}setting` VALUES (NULL, 'about', '关于我们', '0', NULL, 'lang');",
		"INSERT INTO `{$mydbpre}setting` VALUES (NULL, 'admin', '系统管理', '0', NULL, 'lang');",
		"INSERT INTO `{$mydbpre}setting` VALUES (NULL, 'adminreply', '管理员回复', '0', NULL, 'lang');",
		"INSERT INTO `{$mydbpre}setting` VALUES (NULL, 'avatars', '头像', '0', NULL, 'lang');",
		"INSERT INTO `{$mydbpre}setting` VALUES (NULL, 'cancel', ' 取 消 ', '0', NULL, 'lang');",
		"INSERT INTO `{$mydbpre}setting` VALUES (NULL, 'close', ' 关 闭 ', '0', NULL, 'lang');",
		"INSERT INTO `{$mydbpre}setting` VALUES (NULL, 'code', '验证码', '0', NULL, 'lang');",
		"INSERT INTO `{$mydbpre}setting` VALUES (NULL, 'contact', '联系我们', '0', NULL, 'lang');",
		"INSERT INTO `{$mydbpre}setting` VALUES (NULL, 'contactadmin', '联系站长', '0', NULL, 'lang');",
		"INSERT INTO `{$mydbpre}setting` VALUES (NULL, 'content', '留言内容', '0', NULL, 'lang');",
		"INSERT INTO `{$mydbpre}setting` VALUES (NULL, 'delete', '删除', '0', NULL, 'lang');",
		"INSERT INTO `{$mydbpre}setting` VALUES (NULL, 'deleted', ' 删 除 ', '0', NULL, 'lang');",
		"INSERT INTO `{$mydbpre}setting` VALUES (NULL, 'deletem', '删除留言', '0', NULL, 'lang');",
		"INSERT INTO `{$mydbpre}setting` VALUES (NULL, 'downm', '取消置顶', '0', NULL, 'lang');",
		"INSERT INTO `{$mydbpre}setting` VALUES (NULL, 'edit', '编辑', '0', NULL, 'lang');",
		"INSERT INTO `{$mydbpre}setting` VALUES (NULL, 'editm', ' 编 辑 ', '0', NULL, 'lang');",
		"INSERT INTO `{$mydbpre}setting` VALUES (NULL, 'email', 'deng5765@163.com', '0', NULL, 'lang');",
		"INSERT INTO `{$mydbpre}setting` VALUES (NULL, 'face', 'face', '0', NULL, 'lang');",
		"INSERT INTO `{$mydbpre}setting` VALUES (NULL, 'favorite', '加入收藏', '0', NULL, 'lang');",
		"INSERT INTO `{$mydbpre}setting` VALUES (NULL, 'floor', '楼', '0', NULL, 'lang');",
		"INSERT INTO `{$mydbpre}setting` VALUES (NULL, 'homepage', '主页', '0', NULL, 'lang');",
		"INSERT INTO `{$mydbpre}setting` VALUES (NULL, 'ifdeletem', '确定删除此留言', '0', NULL, 'lang');",
		"INSERT INTO `{$mydbpre}setting` VALUES (NULL, 'links', '如果您的浏览器没有自动跳转，请点击这里', '0', NULL, 'lang');",
		"INSERT INTO `{$mydbpre}setting` VALUES (NULL, 'login', '管理员登陆', '0', NULL, 'lang');",
		"INSERT INTO `{$mydbpre}setting` VALUES (NULL, 'logined', ' 登 陆 ', '0', NULL, 'lang');",
		"INSERT INTO `{$mydbpre}setting` VALUES (NULL, 'logout', '退出', '0', NULL, 'lang');",
		"INSERT INTO `{$mydbpre}setting` VALUES (NULL, 'manage', '管理留言', '0', NULL, 'lang');",
		"INSERT INTO `{$mydbpre}setting` VALUES (NULL, 'message', '访客留言', '0', NULL, 'lang');",
		"INSERT INTO `{$mydbpre}setting` VALUES (NULL, 'nickname', '昵称', '0', NULL, 'lang');",
		"INSERT INTO `{$mydbpre}setting` VALUES (NULL, 'password', '密 码', '0', NULL, 'lang');",
		"INSERT INTO `{$mydbpre}setting` VALUES (NULL, 'phpfans', 'php爱好者', '0', NULL, 'lang');",
		"INSERT INTO `{$mydbpre}setting` VALUES (NULL, 'picture', '看不清请点击图片刷新', '0', NULL, 'lang');",
		"INSERT INTO `{$mydbpre}setting` VALUES (NULL, 'post', '签写留言', '0', NULL, 'lang');",
		"INSERT INTO `{$mydbpre}setting` VALUES (NULL, 'qq', 'QQ/oicq', '0', NULL, 'lang');",
		"INSERT INTO `{$mydbpre}setting` VALUES (NULL, 'reload', '看不清请点击图片刷新', '0', NULL, 'lang');",
		"INSERT INTO `{$mydbpre}setting` VALUES (NULL, 'replied', ' 回 复 ', '0', NULL, 'lang');",
		"INSERT INTO `{$mydbpre}setting` VALUES (NULL, 'reply', '回复', '0', NULL, 'lang');",
		"INSERT INTO `{$mydbpre}setting` VALUES (NULL, 'replyer', '回复人', '0', NULL, 'lang');",
		"INSERT INTO `{$mydbpre}setting` VALUES (NULL, 'replym', '回复留言', '0', NULL, 'lang');",
		"INSERT INTO `{$mydbpre}setting` VALUES (NULL, 'sethome', '设为主页', '0', NULL, 'lang');",
		"INSERT INTO `{$mydbpre}setting` VALUES (NULL, 'submit', '发布留言', '0', NULL, 'lang');",
		"INSERT INTO `{$mydbpre}setting` VALUES (NULL, 'submitted', ' 提 交 ', '0', NULL, 'lang');",
		"INSERT INTO `{$mydbpre}setting` VALUES (NULL, 'submittime', '发表于', '0', NULL, 'lang');",
		"INSERT INTO `{$mydbpre}setting` VALUES (NULL, 'time', '时间', '0', NULL, 'lang');",
		"INSERT INTO `{$mydbpre}setting` VALUES (NULL, 'title', '留言主题', '0', NULL, 'lang');",
		"INSERT INTO `{$mydbpre}setting` VALUES (NULL, 'tips', 'php爱好者留言簿 提示信息', '0', NULL, 'lang');",
		"INSERT INTO `{$mydbpre}setting` VALUES (NULL, 'up', '置顶', '0', NULL, 'lang');",
		"INSERT INTO `{$mydbpre}setting` VALUES (NULL, 'upm', '顶置主题', '0', NULL, 'lang');",
		"INSERT INTO `{$mydbpre}setting` VALUES (NULL, 'upordown', '置顶/解除置顶', '0', NULL, 'lang');",
		"INSERT INTO `{$mydbpre}setting` VALUES (NULL, 'username', '用户名', '0', NULL, 'lang');",
		"INSERT INTO `{$mydbpre}setting` VALUES (NULL, 'view', '浏览留言', '0', NULL, 'lang');",
		"INSERT INTO `{$mydbpre}setting` VALUES (NULL, 'which', '第', '0', NULL, 'lang');",
		"INSERT INTO `{$mydbpre}setting` VALUES (NULL, 'm1', '非常感谢，您的留言已经发布，现在将转入留言簿主页。', '1', NULL, 'lang');",
		"INSERT INTO `{$mydbpre}setting` VALUES (NULL, 'm2', '您的回复已经发布，现在将转入你进入前的页面。', '1', NULL, 'lang');",
		"INSERT INTO `{$mydbpre}setting` VALUES (NULL, 'm3', '删除留言成功，现在将转入你进入前的页面。', '1', NULL, 'lang');",
		"INSERT INTO `{$mydbpre}setting` VALUES (NULL, 'm4', '昵称或内容不能为空，请从新签写留言。', '1', NULL, 'lang');",
		"INSERT INTO `{$mydbpre}setting` VALUES (NULL, 'm5', '你输入的管理员密码不正确，你不具备操作的权限。', '1', NULL, 'lang');",
		"INSERT INTO `{$mydbpre}setting` VALUES (NULL, 'm6', '发布的信息必须含有一个汉字，谢谢合作。', '1', NULL, 'lang');",
		"INSERT INTO `{$mydbpre}setting` VALUES (NULL, 'm7', '你发布的留言包括本站视为敏感的字符，本站拒绝你发言。', '1', NULL, 'lang');",
		"INSERT INTO `{$mydbpre}setting` VALUES (NULL, 'm8', '你的ip已经在本站黑名单中，本站拒绝你发言。', '1', NULL, 'lang');",
		"INSERT INTO `{$mydbpre}setting` VALUES (NULL, 'm9', '你发布的留言超过本站设置的最大长度，请从新签写留言。', '1', NULL, 'lang');",
		"INSERT INTO `{$mydbpre}setting` VALUES (NULL, 'm10', '你发布留言过快，请休息一阵再发。', '1', NULL, 'lang');",
		"INSERT INTO `{$mydbpre}setting` VALUES (NULL, 'm11', '置顶/解除置顶留言成功，你进入前的页面。', '1', NULL, 'lang');",
		"INSERT INTO `{$mydbpre}setting` VALUES (NULL, 'm12', '你已经登陆。请勿重复登陆。', '1', NULL, 'lang');",
		"INSERT INTO `{$mydbpre}setting` VALUES (NULL, 'm13', '验证码已经过期，请重新发言。', '1', NULL, 'lang');",
		"INSERT INTO `{$mydbpre}setting` VALUES (NULL, 'm14', '验证码不正确，请重新填写。', '1', NULL, 'lang');",
		"INSERT INTO `{$mydbpre}setting` VALUES (NULL, 'm15', 'email格式不正确，请重新填写。', '1', NULL, 'lang');",
		"INSERT INTO `{$mydbpre}setting` VALUES (NULL, 'm16', '登陆成功，现在跳转到你登陆前的页面。', '1', NULL, 'lang');",
		"INSERT INTO `{$mydbpre}setting` VALUES (NULL, 'm17', '用户名或密码有误，请重新登陆。', '1', NULL, 'lang');",
		"INSERT INTO `{$mydbpre}setting` VALUES (NULL, 'm18', '已经成功退出，现在跳转到你退出前的页面。', '1', NULL, 'lang');",
		"INSERT INTO `{$mydbpre}setting` VALUES (NULL, 'm19', '昵称不能为空。', '1', NULL, 'lang');",
		"INSERT INTO `{$mydbpre}setting` VALUES (NULL, 'm20', '验证码不能为空。', '1', NULL, 'lang');",
		"INSERT INTO `{$mydbpre}setting` VALUES (NULL, 'm21', '验证码不正确。', '1', NULL, 'lang');",
		"INSERT INTO `{$mydbpre}setting` VALUES (NULL, 'm22', 'Email格式不正确。', '1', NULL, 'lang');",
		"INSERT INTO `{$mydbpre}setting` VALUES (NULL, 'm23', 'QQ号码不正确。', '1', NULL, 'lang');",
		"INSERT INTO `{$mydbpre}setting` VALUES (NULL, 'm24', '主页地址不正确。', '1', NULL, 'lang');",
		"INSERT INTO `{$mydbpre}setting` VALUES (NULL, 'm25', '编辑留言成功，现在将转入你进入前的页面。', '1', NULL, 'lang');",
		"INSERT INTO `{$mydbpre}setting` VALUES (NULL, 'm26', '请先登陆再进行管理。', '1', NULL, 'lang');",
		"INSERT INTO `{$mydbpre}setting` VALUES (NULL, 'addavatars', '选择头像', '0', NULL, 'lang');",
		"INSERT INTO `{$mydbpre}setting` VALUES (NULL, 'cancelavatars', '取消选择', '0', NULL, 'lang');",
		"INSERT INTO `{$mydbpre}setting` VALUES (NULL, 'bnickname', '昵称不能为空。', '2', NULL, 'lang');",
		"INSERT INTO `{$mydbpre}setting` VALUES (NULL, 'bcode', '验证码不能为空。', '2', NULL, 'lang');",
		"INSERT INTO `{$mydbpre}setting` VALUES (NULL, 'eemail', 'Email格式不正确。', '2', NULL, 'lang');",
		"INSERT INTO `{$mydbpre}setting` VALUES (NULL, 'ecode', '验证码不正确。', '2', NULL, 'lang');",
		"INSERT INTO `{$mydbpre}setting` VALUES (NULL, 'eqq', 'QQ号码不正确。', '2', NULL, 'lang');",
		"INSERT INTO `{$mydbpre}setting` VALUES (NULL, 'ehomepage', '主页地址不正确。', '2', NULL, 'lang');",
		"INSERT INTO `{$mydbpre}setting` VALUES (NULL, 'bcontent', '留言内容不能为空。', '2', NULL, 'lang');",
		"INSERT INTO `{$mydbpre}setting` VALUES (NULL, '浏览留言', './', '_self', '0', 'topbar');",
		"INSERT INTO `{$mydbpre}setting` VALUES (NULL, '发布留言', 'post.php', '_self', '1', 'topbar');"
	);
	
	$file_arr = array(
		'index.php','admincp.php','code.php','install.php',
		'login.php','manage.php','post.php','update.php',
		'version.php','admin','cache','css','data','fckeditor',
		'htmlarea','images','include','javascript','lang','templates'
	);
	
	$delete_files = array(
		'images/avatars/noavatar.gif'
	);
	
	$updateStr = '';
	foreach ($sql_post as $sql){
		$db->query($sql);
	}
	$updateStr .= "修改数据表 {$mydbpre}post 结构成功。<br>初始化 {$mydbpre}post 成功。<br>";
	foreach ($sql_setting as $sql){
		$db->query($sql);
	}
	$updateStr .= "修改数据表 {$mydbpre}setting 结构成功。<br>初始化 {$mydbpre}post 成功。<br>";
	
	$dir = dir("./");
	while (false !== ($file = $dir->read())) {
		if(is_file($file) && !in_array($file,$file_arr)){
			unlink($file);
		}
		elseif (is_dir($file) && !in_array($file,$file_arr) && $file != '.' && $file != '..'){
			deleteDir($file);
		}
	}
	$dir->close();
	
	foreach ($delete_files as $path){
		if(file_exists($path))
			unlink($path);
	}
	$updateStr .= "删除多余文件成功。<br>";
	
	$configContent = read_file('include/config.php');
	$configContent = preg_replace("/define\('VERSION','(.+?)'\);/i","define('VERSION','2.0');",$configContent);
	writeFile('include/config.php',$configContent);
	$updateStr .= "修改配置文件 include/config.php 成功。";
	$updateStr .= "<br><br>更新已顺利完成.安全起见,请删除update.php和install.php";
	$step = 4;
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=gb2312" />
<title>更新php爱好者留言板</title>
<style type="text/css">
body, table{
	font-family:Tahoma, Verdana;
}
.admintb{ 
	background:#eeeeee;text-align:left; margin:5px; width:98%;
}
.admintb td{ 
	font-size:14px;text-align:left; 
	height:25px;padding-left:5px; background:#FFFFFF;
}
.admintb .header{ 
	font-size:14px;font-weight:bold; text-align:left; color:#000000;
	height:25px; padding-left:5px;background:#DDDDDD;  
}
#statustb td{text-align:center;}
</style>
</head>
<body>
<div style="font-size:16px;font-weight:bold; padding-left:10%;">
>> php爱好者留言板 1.5 到 2.0 更新向导  
</div>
<?php 
switch($step){
	case 1:
	if(VERSION == '2.0'){
?>
<table align="center" border="0" cellpadding="0" cellspacing="1" class="admintb" style="width:70%;table-layout:fixed;">
  <tr>
    <td class="header">提示信息</td>
  </tr>
  <tr>
    <td style="padding:5px;">
    当前版本2.0已经是最新版本，无需更新。
    </td>
  </tr>
</table>
<?php 	
	}
	elseif(!file_exists('cache/update_2.0.lock')){
?>
<table align="center" border="0" cellpadding="0" cellspacing="1" class="admintb" style="width:70%;table-layout:fixed;">
  <tr>
    <td class="header">用户协议</td>
  </tr>
  <tr>
    <td style="padding:5px; font-size:12px;">
<strong>php爱好者使用协议：</strong>
<p>版权所有 (c) 2006-2008，<a href="http://www.phpfans.net">php爱好者站</a><br />
保留所有权利。


<p>本授权协议适用于 php爱好者留言板 所有版本，php爱好者站拥有协议的最终解释权。
<ul type="I">
<p><li><b>协议许可的权利</b></li>
<ul type="1">
<li>您可以根据自己需要修改 php爱好者留言板 源代码或界面风格以适应您的网站要求。</li>
<li>您和您的用户可以在使用php爱好者留言板发布留言和回复，并独立承担与留言内容的相关法律义务。</li>
</ul>

<p><li><b>协议的约束</b></li>
<ul type="1">
<li>未获书面许可之前，不得将 php爱好者留言板 用于商业用途。</li>
<li><font color="red">未经书面许可，留言板页面页脚处的版权信息 PHPfans 及其链接 http://www.phpfans.net 都必须保留，而不能清除或修改。有特殊需要请与作者联系。</font></li>
<li>禁止在 php爱好者留言板 的整体或任何部分基础上以发展任何修改版本用于重新分发。</li>
</ul>

<p><li><b>免责声明</b></li>
<ul type="1">
<li>php爱好者留言板是php学习和交流为目的，不承担任何连带责任。</li>
<li>用户出于自愿而使用本留言板，我们不承担任何因使用本软件而产生问题的相关责任。</li>
<li>php爱好者站不对使用php爱好者留言板构建的留言板中的留言或回复承担责任。</li>
</ul>
</ul>
<p>感谢您选择 php爱好者留言板。希望我们的留言板让你感到满意。
<p>
  php爱好者站致力于php技术的学习和交流。<br />
  php爱好者站网址为 <a href="http://www.phpfans.net">http://www.phpfans.net</a>，<br />
  php爱好者讨论区网址为 <a href="http://www.phpfans.net/bbs/">http://www.phpfans.net/bbs/</a>，<br /> 
  php爱好者留言板网址为 <a href="http://www.phpfans.net/guestbook/">http://www.phpfans.net/guestbook/</a>。<br /> 
</p>
    </td>
  </tr>
</table>
<div style="text-align:center">
<input type="button" value="我同意" onclick="window.location='update.php?step=2';" />
<input type="button" value="我不同意" onclick="window.close();" />
</div>
<?php 
	}
	else{
?>
<table align="center" border="0" cellpadding="0" cellspacing="1" class="admintb" style="width:70%;table-layout:fixed;">
  <tr>
    <td class="header">错误信息</td>
  </tr>
  <tr>
    <td style="padding:5px;">
    您已经更新过留言板，为了保证留言板数据安全，请手动删除 update.php 文件，如果您想重新安装留言板，请删除 cache/update_2.0.lock 文件，再次运行安装文件update.php。
    </td>
  </tr>
</table>
<?php 	
	}
	break;
	case 2:
		$checkArr = array(
			'./include/config.php',
			'./cache',
			'./data',
			'./images/avatars'
		);
		$statusHTML = '';
		$allstatus = true;
		foreach ($checkArr as $value){
			$status = false;
			if(is_file($value))
				$status = is_writable_file($value);
			elseif (is_dir($value))
				$status = is_writable_dir($value);
			if($status)
				$statusStr = '<font color=#0000FF>可写</font>';
			else{
				$allstatus = false;
				$statusStr = '<font color=#FF0000>不可写</font>';
			}
		
			$statusHTML .= htmlTag('tr','',
				htmlTag('td','',$value)
				. htmlTag('td','',"可写")
				. htmlTag('td','',$statusStr)
			);
		}
?>
<table align="center" border="0" cellpadding="0" cellspacing="1" class="admintb" style="width:70%;table-layout:fixed;">
  <tr>
    <td class="header">提示信息</td>
  </tr>
  <tr>
    <td style="padding:5px;">
    <ul>
    <li>将压缩包中 upload 目录下<font style="font-size:16px;color:red; font-weight:bold;">除 include/config.php</font> 的全部文件和目录上传到服务器覆盖所有1.5版本的留言板的文件。</li>
    <li>如果您使用非 WINNT 系统请修改以下属性：
    <div style="padding-left:20px;">
    &nbsp; &nbsp; ./include/config.php 文件 777;<br>
    &nbsp; &nbsp; ./cache 目录 777;<br>
    &nbsp; &nbsp; ./data 目录 777;<br>
    &nbsp; &nbsp; ./images/avatars 目录 777;
    </div>
    </li>
    </td>
  </tr>
</table>
<table id="statustb" align="center" border="0" cellpadding="0" cellspacing="1" class="admintb" style="width:70%;table-layout:fixed;">
  <tr>
    <td class="header">目录文件名称</td>
    <td class="header">要求</td>
    <td class="header">当前状态</td>
  </tr> 
  <?php echo $statusHTML;?>
</table>
<div style="text-align:center">
<input type="button" value="上一步" onclick="window.history.back();"/>
<input type="button" value="下一步" onclick="window.location='update.php?step=3';" <?php echo !$allstatus? 'diabled' : '';?> />
<input type="button" value="重新检查" onclick="window.location.reload();" />
</div>
<?php 
	break;
	case 3:
?>
<table align="center" border="0" cellpadding="0" cellspacing="1" class="admintb" style="width:70%;">
<form id="form1" name="form1" method="post" action="">
  <tr>
    <td class="header">更新 php爱好者留言簿</td>
    </tr>
  <tr>
    <td style="padding:10px;">
    <ul>
    <li>更新程序会修改数据表的结构和增加数据，并且会删除一些多余的文件。更新前请做好数据和文件的备份。</li>
    </ul>
    <div style="text-align:center;">
    <input type="button" value="上一步" onclick="window.history.back();"/>
    <input type="submit" name="update" value=" 更 新 " /> 
    </div>
    </td>
    </tr>
</form>
</table>
<?php 
	break;
	case 4:
?>
<table align="center" border="0" cellpadding="0" cellspacing="1" class="admintb" style="width:70%;table-layout:fixed;">
  <tr>
    <td class="header">更新信息</td>
  </tr>
  <tr>
    <td style="padding:10px;">
    <?php echo $updateStr;?>
    </td>
  </tr>
</table>
<div style="text-align:center">
<input type="button" value="进入留言板" onclick="window.location='index.php';" />
<input type="button" value="管理员登陆" onclick="window.location='login.php';" />
</div>
<?php 
	writeFile('cache/update_2.0.lock','');
	break;
}
?>
</body>
</html>