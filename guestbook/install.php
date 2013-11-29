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
require_once('include/function.php');
require_once('include/class.php');

function showError($step, $errno=null, $error=null){
	header("location:install.php?step=".$step.(is_null($errno)?"":"&errno=".$errno).(is_null($error)?"":"&error=".$error));
	exit;
}

$errorArr = array(
	0 => '数据库名不能为空或包含特殊字符。',
	1 => '数据表前序不能包含特殊字符。',
	2003 => '无法连接到mysql服务器，请检查mysql是否启动并且数据库服务器是否正确。',
	1045 => '无法连接数据库，请检查数据库用户名或者密码是否正确'
);

if(!isset($_REQUEST['step']) || !in_array($_REQUEST['step'],array(1,2,3)))
	$step = 1;
else
	$step = $_REQUEST['step'];

$errorStr = '';
if(!empty($_REQUEST['error']))
	$errorStr = $_REQUEST['error'];

if(isset($_REQUEST['errno']) && array_key_exists($_REQUEST['errno'],$errorArr))
	$errorStr = $errorArr[$_REQUEST['errno']];

if(isset($_POST['Submit'])){
	$mydbhost = $_POST['m_host'];
	$mydbuser = $_POST['m_root'];
	$mydbpw = $_POST['m_pw'];
	$mydbname = $_POST['m_db'];
	$mydbpre = $_POST['m_pre'];
	
	if(!preg_match('/^\w+$/',$mydbname))
		showError(3,0);
	
	if(trim($mydbpre) !== '' && !preg_match('/^\w+$/',$mydbpre))
		showError(3,1);
	
	$db = new mysql();
	$db->showError = false;
	if(!$db->connect($mydbhost, $mydbuser, $mydbpw))
		showError(3,$db->errno(),$db->error());
	
	$msg = urlencode(serialize(
		array(
			'mydbhost' => $mydbhost,
			'mydbuser' => $mydbuser,
			'mydbpw' => $mydbpw,
			'mydbname' => $mydbname,
			'mydbpre' => $mydbpre
		)
	));
	$step = 4;
}

if(isset($_POST['createDB'])){
	if(file_exists('cache/install_2.0.lock'))
		showError(1);
	
	$msg = unserialize(urldecode($_POST['storemsg']));
	$mydbhost = $msg['mydbhost'];
	$mydbuser = $msg['mydbuser'];
	$mydbpw = $msg['mydbpw'];
	$mydbname = $msg['mydbname'];
	$mydbpre = $msg['mydbpre'];
	$mydbcharset = 'gbk';
	
	$mygbuser = $_POST['m_admin'];
	$mygbemail = $_POST['m_email'];
	$mygbpw = md5($_POST['m_adminpw']);
	sqlEncode($mygbuser);
	sqlEncode($mygbemail);
	
	$db = new mysql();
	$db->connect($mydbhost, $mydbuser, $mydbpw);
	if($db->version() > '4.1') {
		$db->query("set names '".$mydbcharset."'");
		$char = ' ENGINE=MyISAM DEFAULT CHARSET='.$mydbcharset;
	}
	else 
		$char = ' TYPE=MyISAM';

	$sqlTables = array(
		"{$mydbpre}ban" => "CREATE TABLE `{$mydbpre}ban` (
		  `b_id` int(5) unsigned NOT NULL auto_increment,
		  `b_val` varchar(100) NOT NULL,
		  `b_reval` varchar(100) NOT NULL,
		  `b_type` tinyint(1) unsigned NOT NULL default '0',
		  PRIMARY KEY  (`b_id`)
		){$char};",
		"{$mydbpre}post" => "CREATE TABLE `{$mydbpre}post` (
		  `p_id` int(255) unsigned NOT NULL auto_increment,
		  `p_name` varchar(20) NOT NULL default '',
		  `p_email` varchar(20) default NULL,
		  `p_qq` varchar(20) default NULL,
		  `p_homepage` varchar(100) default NULL,
		  `p_image` varchar(255) NOT NULL,
		  `p_title` varchar(160) default NULL,
		  `p_content` mediumtext NOT NULL,
		  `p_ip` varchar(15) NOT NULL default '',
		  `p_date` int(10) unsigned NOT NULL default '0',
		  `p_rank` tinyint(1) unsigned NOT NULL default '0',
		  `p_editor` tinyint(3) unsigned NOT NULL default '1',
		  PRIMARY KEY  (`p_id`)
		){$char};",
		"{$mydbpre}reply" => "CREATE TABLE `{$mydbpre}reply` (
		  `r_id` int(255) unsigned NOT NULL auto_increment,
		  `p_id` int(255) NOT NULL default '0',
		  `r_content` varchar(255) NOT NULL default '',
		  `r_rname` varchar(20) NOT NULL default '',
		  `r_time` date NOT NULL default '0000-00-00',
		  PRIMARY KEY  (`r_id`),
		  UNIQUE KEY `p_id` (`p_id`)
		){$char};",
		"{$mydbpre}setting" => "CREATE TABLE `{$mydbpre}setting` (
		  `id` int(10) unsigned NOT NULL auto_increment,
		  `keyword` varchar(255) NOT NULL,
		  `val` varchar(255) NOT NULL,
		  `expansion` varchar(255) default NULL,
		  `expansion2` varchar(255) default NULL,
		  `type` varchar(15) NOT NULL,
		  PRIMARY KEY  (`id`)
		){$char};"
	);
	$sqlInsert = array(
		"INSERT INTO `{$mydbpre}setting` VALUES (NULL, 'name', 'php爱好者留言板', NULL, NULL, 'basic');",
		"INSERT INTO `{$mydbpre}setting` VALUES (NULL, 'url', 'http://www.phpfans.net/guestbook/', NULL, NULL, 'basic');",
		"INSERT INTO `{$mydbpre}setting` VALUES (NULL, 'icp', '', NULL, NULL, 'basic');",
		"INSERT INTO `{$mydbpre}setting` VALUES (NULL, 'fck', '2', NULL, NULL, 'basic');",
		"INSERT INTO `{$mydbpre}setting` VALUES (NULL, 'eachpage', '7', NULL, NULL, 'basic');",
		"INSERT INTO `{$mydbpre}setting` VALUES (NULL, 'email', '{$mygbemail}', NULL, NULL, 'basic');",
		"INSERT INTO `{$mydbpre}setting` VALUES (NULL, 'maxlen', '0', NULL, NULL, 'basic');",
		"INSERT INTO `{$mydbpre}setting` VALUES (NULL, 'minsec', '0', NULL, NULL, 'basic');",
		"INSERT INTO `{$mydbpre}setting` VALUES (NULL, 'username', '{$mygbuser}', NULL, NULL, 'basic');",
		"INSERT INTO `{$mydbpre}setting` VALUES (NULL, 'password', '{$mygbpw}', NULL, NULL, 'basic');",
		"INSERT INTO `{$mydbpre}setting` VALUES (NULL, 'rcode', '1', NULL, NULL, 'basic');",
		"INSERT INTO `{$mydbpre}setting` VALUES (NULL, 'logo', 'images/logo.gif', NULL, NULL, 'basic');",
		"INSERT INTO `{$mydbpre}setting` VALUES (NULL, 'avatars', 'images/avatars/', NULL, NULL, 'basic');",
		"INSERT INTO `{$mydbpre}setting` VALUES (NULL, '关于我们', '#', '_self', '0', 'bottombar');",
		"INSERT INTO `{$mydbpre}setting` VALUES (NULL, '联系我们', 'mailto:{$mygbemail}', '_self', '1', 'bottombar');",
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
		"INSERT INTO `{$mydbpre}setting` VALUES (NULL, 'email', '邮箱', '0', NULL, 'lang');",
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
		"INSERT INTO `{$mydbpre}setting` VALUES (NULL, '发布留言', 'post.php', '_self', '1', 'topbar');",
		"INSERT INTO `{$mydbpre}post` VALUES (NULL, '我不是鱼', 'deng5765@163.com', '245821218', 'http://www.phpfans.net', 'images/avatars/01.gif', '感谢你使用php爱好者留言簿', '<P>非常感谢你对php爱好者留言簿的支持</P><P>在使用中遇到任何问题,请到</P><P><A target=\"_blank\" href=\"http://www.phpfans.net/gustbook/\">http://www.phpfans.net/gustbook/</A>&nbsp;</P><P>或者</P><P><A target=\"_blank\" href=\"http://www.phpfans.net/bbs/forumdisplay.php?fid=24\">http://www.phpfans.net/bbs/forumdisplay.php?fid=24</A>&nbsp;</P><P>获得帮助或提出你的建议.</P><p>同时请大家密切关注php爱好者留言簿的更新情况</P><P><a target=\"_blank\" href=\"http://www.phpfans.net/bbs/viewthread.php?tid=6345&extra=page%3D1\">http://www.phpfans.net/bbs/viewthread.php?tid=6345&extra=page%3D1</a></P><p>还有欢迎大家光临php爱好者站</P><P><A target=\"_blank\" href=\"http://www.phpfans.net\">http://www.phpfans.net</A></P><p>php爱好者站论坛</P><P><A target=\"_blank\" href=\"http://www.phpfans.net/bbs/\">http://www.phpfans.net/bbs/</A>&nbsp;<img src=\"fckeditor/editor/images/smiley/smile/smile13.gif\"></P>', '127.0.0.1', ".time().",1,1)"
	);
	
	$installStr = '';
	$dbsql = "CREATE DATABASE IF NOT EXISTS ".$mydbname;
	$db->query($dbsql);
	$installStr .= "创建数据库{$mydbname}成功>><br>";
	$db->select_db($mydbname);
	
	foreach ($sqlTables as $tableName => $tableSql){
		$db->query("DROP TABLE IF EXISTS {$tableName}");
		$db->query($tableSql);
		$installStr .= "创建数据表 {$tableName} 成功>><br>";
	}
	
	foreach ($sqlInsert as $sql){
		$db->query($sql);
	}
	$installStr .= "初始化数据表成功<br>";
	
	$str = "<?php\n";
	$str .= "\$mydbhost = '{$mydbhost}';//数据库服务器\n";
	$str .= "\$mydbuser ='{$mydbuser}';//数据库用户名\n";
	$str .= "\$mydbpw = '{$mydbpw}';//数据库密码\n";
	$str .= "\$mydbname = '{$mydbname}';//数据库名\n";
	$str .= "\$mydbpre = '{$mydbpre}';//数据表前序\n";
	$str .= "\$mydbcharset = 'gbk';//数据库编码,不建议修改\n";
	$str .= "define('VERSION','2.0');\n";
	$str .= "?>";
	
	if(is_writable_file('include/config.php')){
		writeFile('include/config.php',$str);
		$installStr .= "配置文档 config.php 更新成功>><br><br>";
		$installStr .= "安装顺利完成.安全起见,请删除install.php和update.php<br>";
		$installStr .= '<a href="index.php">进入留言簿首页</a>';
	}
	else
		$installStr = "文件include/config.php不能写，请手动配置config.php上的内容<br>";
	
	$step = 5;
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=gb2312" />
<title>安装php爱好者留言板</title>
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
>> php爱好者留言板 2.0 安装向导  
</div>
<?php 
switch($step){
	case 1:
	if(!file_exists('cache/install_2.0.lock')){
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
<input type="button" value="我同意" onclick="window.location='install.php?step=2';" />
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
    您已经安装过留言板，为了保证留言板数据安全，请手动删除 install.php 文件，如果您想重新安装留言板，请删除 cache/install_2.0.lock 文件，再次运行安装文件install.php。
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
    <li>将压缩包中 upload 目录下全部文件和目录上传到服务器。</li>
    <li>如果您使用非 WINNT 系统请修改以下属性：
    <div style="padding-left:20px;">
    &nbsp; &nbsp; ./include/config.php 文件 777;<br>
    &nbsp; &nbsp; ./cache 目录 777;<br>
    &nbsp; &nbsp; ./data 目录 777;<br>
    &nbsp; &nbsp; ./images/avatars 目录 777;
    </div>
    </li>
    <li>如果include/config.php文件不可写，请自行修改该文件上传到include目录下。</li>
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
<input type="button" value="下一步" onclick="window.location='install.php?step=3';" <?php echo !$allstatus? 'diabled' : '';?> />
<input type="button" value="重新检查" onclick="window.location.reload();" />
</div>
<?php 
	break;
	case 3:
		if($errorStr){
?>
<table align="center" border="0" cellpadding="0" cellspacing="1" class="admintb" style="width:70%;table-layout:fixed;">
  <tr>
    <td class="header">提示信息</td>
  </tr>
  <tr>
    <td style="padding:10px;color:red;">
    <li><?php echo $errorStr;?></li>
    </td>
  </tr>
</table>
<?php }?>
<table align="center" border="0" cellpadding="0" cellspacing="1" class="admintb" style="width:70%;">
<form id="form1" name="form1" method="post" action="">
  <tr>
    <td colspan="2" class="header">安装 php爱好者留言簿</td>
    </tr>
  <tr>
    <td style="width:30%">数据库服务器:</td>
    <td><input name="m_host" type="text" id="m_host" value="localhost"> 数据库服务器地址, 一般为 localhost</td>
  </tr>
  <tr>
    <td>数据库用户名:</td>
    <td><input name="m_root" type="text" id="m_root" /> 数据库账号用户名</td>
  </tr>
  <tr>
    <td>数据库密码:</td>
    <td><input name="m_pw" type="password" id="m_pw" /> 数据库账号密码</td>
  </tr>
  <tr>
    <td>数据库名:</td>
    <td><input name="m_db" type="text" id="m_db"> 不存在自动创建</td>
  </tr>
  <tr>
    <td>数据表前序</td>
    <td><input name="m_pre" type="text" id="m_pre" value="gue_"> 同一数据库安装多留言板时可改变</td>
  </tr>
  <tr>
    <td colspan="2" style="text-align:center;">
    <input type="button" value="上一步" onclick="window.history.back();"/>
    <input type="submit" name="Submit" value="下一步" /> 
    </td>
    </tr>
</form>
</table>
<?php 
	break;
	case 4:
?>
<table align="center" border="0" cellpadding="0" cellspacing="1" class="admintb" style="width:70%;">
<form id="form1" name="form1" method="post" action="">
   <tr>
    <td colspan="2" class="header">设置管理员账号</td>
   </tr>
  <tr>
    <td>留言簿管理员名:</td>
    <td><input name="m_admin" type="text" id="m_admin" value="我不是鱼"> &nbsp;<input type="hidden" name="storemsg" value="<?php echo $msg;?>"></td>
  </tr>
  <tr>
    <td>留言簿管理员email:</td>
    <td><input name="m_email" type="text" id="m_email" value="deng5765@163.com"></td>
  </tr>
  <tr>
    <td>留言簿管理员密码:</td>
    <td><input name="m_adminpw" type="password" id="m_adminpw"> &nbsp;</td>
  </tr>
  <tr>
    <td colspan="2" style="text-align:center;">
    <input type="button" value="上一步" onclick="window.history.back();"/>
    <input type="submit" name="createDB" value="下一步" /> 
    </td>
    </tr>
</form>
</table>
<?php 
	break;
	case 5:
?>
<table align="center" border="0" cellpadding="0" cellspacing="1" class="admintb" style="width:70%;table-layout:fixed;">
  <tr>
    <td class="header">安装信息</td>
  </tr>
  <tr>
    <td>
    <?php echo $installStr;?>
    </td>
  </tr>
</table>
<div style="text-align:center">
<input type="button" value="进入留言板" onclick="window.location='index.php';" />
<input type="button" value="管理员登陆" onclick="window.location='login.php';" />
</div>
<?php 
	writeFile('cache/install_2.0.lock','');
	break;
}
?>
</body>
</html>