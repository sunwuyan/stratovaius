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

define('IN_GB', TRUE);
require_once('include/function.php');
require_once('include/class.php');

function showError($step, $errno=null, $error=null){
	header("location:install.php?step=".$step.(is_null($errno)?"":"&errno=".$errno).(is_null($error)?"":"&error=".$error));
	exit;
}

$errorArr = array(
	0 => '���ݿ�������Ϊ�ջ���������ַ���',
	1 => '���ݱ�ǰ���ܰ��������ַ���',
	2003 => '�޷����ӵ�mysql������������mysql�Ƿ������������ݿ�������Ƿ���ȷ��',
	1045 => '�޷��������ݿ⣬�������ݿ��û������������Ƿ���ȷ'
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
		"INSERT INTO `{$mydbpre}setting` VALUES (NULL, 'name', 'php���������԰�', NULL, NULL, 'basic');",
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
		"INSERT INTO `{$mydbpre}setting` VALUES (NULL, '��������', '#', '_self', '0', 'bottombar');",
		"INSERT INTO `{$mydbpre}setting` VALUES (NULL, '��ϵ����', 'mailto:{$mygbemail}', '_self', '1', 'bottombar');",
		"INSERT INTO `{$mydbpre}setting` VALUES (NULL, '�ÿ�����', './', '_seft', '2', 'bottombar');",
		"INSERT INTO `{$mydbpre}setting` VALUES (NULL, 'headborder', '#CCCCCC', 'ͷ���߿���ɫ', 'colorborder', 'css');",
		"INSERT INTO `{$mydbpre}setting` VALUES (NULL, 'tailborder', '#CCCCCC', 'β���߿���ɫ', 'colorborder', 'css');",
		"INSERT INTO `{$mydbpre}setting` VALUES (NULL, 'tableboder', '#52628f', '���߿���ɫ', 'colorborder', 'css');",
		"INSERT INTO `{$mydbpre}setting` VALUES (NULL, 'navigationborder', '#6B7291', '�������߿���ɫ', 'colorborder', 'css');",
		"INSERT INTO `{$mydbpre}setting` VALUES (NULL, 'pageborder', '#6B7291', '��ҳ�߿���ɫ', 'colorborder', 'css');",
		"INSERT INTO `{$mydbpre}setting` VALUES (NULL, 'bigfont', '14px', '�������', 'fontsize', 'css');",
		"INSERT INTO `{$mydbpre}setting` VALUES (NULL, 'smallfont', '12px', 'С������', 'fontsize', 'css');",
		"INSERT INTO `{$mydbpre}setting` VALUES (NULL, 'width', '775', '���԰���', 'dimensions', 'css');",
		"INSERT INTO `{$mydbpre}setting` VALUES (NULL, 'msgleftbg', '#F9FAFB', '������߱�����ɫ', 'colorbg', 'css');",
		"INSERT INTO `{$mydbpre}setting` VALUES (NULL, 'headbg', '#f0f1f3', 'ͷ��������ɫ', 'colorbg', 'css');",
		"INSERT INTO `{$mydbpre}setting` VALUES (NULL, 'tailbg', '#FFFFFF', 'β��������ɫ', 'colorbg', 'css');",
		"INSERT INTO `{$mydbpre}setting` VALUES (NULL, 'tableheader', '#6B7291', '���ͷ������ɫ', 'colorbg', 'css');",
		"INSERT INTO `{$mydbpre}setting` VALUES (NULL, 'headcolor', '#414141', 'ͷ��������ɫ', 'colorfont', 'css');",
		"INSERT INTO `{$mydbpre}setting` VALUES (NULL, 'msgcolor', '#67678F', '����������ɫ', 'colorfont', 'css');",
		"INSERT INTO `{$mydbpre}setting` VALUES (NULL, 'replyborder', '#698cc3', '����Ա�ظ��߿���ɫ', 'colorborder', 'css');",
		"INSERT INTO `{$mydbpre}setting` VALUES (NULL, 'replybg', '#f6fafd', '����Ա�ظ�������ɫ', 'colorbg', 'css');",
		"INSERT INTO `{$mydbpre}setting` VALUES (NULL, 'replycolor', '#4875b7', '����Ա�ظ�������ɫ', 'colorfont', 'css');",
		"INSERT INTO `{$mydbpre}setting` VALUES (NULL, 'tbinborder', '#d6e0ef', '����ڱ߿���ɫ', 'colorborder', 'css');",
		"INSERT INTO `{$mydbpre}setting` VALUES (NULL, 'taillinkcolor', '#52628f', 'β������������ɫ', 'colorfont', 'css');",
		"INSERT INTO `{$mydbpre}setting` VALUES (NULL, 'tableheadercolor', '#FFFFFF', '���ͷ������ɫ', 'colorfont', 'css');",
		"INSERT INTO `{$mydbpre}setting` VALUES (NULL, 'navigationcolor', '#660066', '������������ɫ', 'colorfont', 'css');",
		"INSERT INTO `{$mydbpre}setting` VALUES (NULL, 'bodybg', '#FFFFFF', 'ҳ�汳����ɫ', 'colorbg', 'css');",
		"INSERT INTO `{$mydbpre}setting` VALUES (NULL, 'defaultfont', 'Tahoma, Verdana', 'Ĭ������', 'fontfamily', 'css');",
		"INSERT INTO `{$mydbpre}setting` VALUES (NULL, 'msgrightbg', '#FFFFFF', '�����ұ߱�����ɫ', 'colorbg', 'css');",
		"INSERT INTO `{$mydbpre}setting` VALUES (NULL, 'msgleftcolor', '#000000', '�������������ɫ', 'colorfont', 'css');",
		"INSERT INTO `{$mydbpre}setting` VALUES (NULL, 'msgtopcolor', '#000000', '�����ϱ�������ɫ', 'colorfont', 'css');",
		"INSERT INTO `{$mydbpre}setting` VALUES (NULL, 'msgbottomcolor', '#000000', '�����±�������ɫ', 'colorfont', 'css');",
		"INSERT INTO `{$mydbpre}setting` VALUES (NULL, 'pagelinkcolor', '#660066', '��ҳ����������ɫ', 'colorfont', 'css');",
		"INSERT INTO `{$mydbpre}setting` VALUES (NULL, 'pagecolor', '#000000', '��ҳ������ɫ', 'colorfont', 'css');",
		"INSERT INTO `{$mydbpre}setting` VALUES (NULL, 'footcolor', '#000000', 'β��������ɫ', 'colorfont', 'css');",
		"INSERT INTO `{$mydbpre}setting` VALUES (NULL, 'about', '��������', '0', NULL, 'lang');",
		"INSERT INTO `{$mydbpre}setting` VALUES (NULL, 'admin', 'ϵͳ����', '0', NULL, 'lang');",
		"INSERT INTO `{$mydbpre}setting` VALUES (NULL, 'adminreply', '����Ա�ظ�', '0', NULL, 'lang');",
		"INSERT INTO `{$mydbpre}setting` VALUES (NULL, 'avatars', 'ͷ��', '0', NULL, 'lang');",
		"INSERT INTO `{$mydbpre}setting` VALUES (NULL, 'cancel', ' ȡ �� ', '0', NULL, 'lang');",
		"INSERT INTO `{$mydbpre}setting` VALUES (NULL, 'close', ' �� �� ', '0', NULL, 'lang');",
		"INSERT INTO `{$mydbpre}setting` VALUES (NULL, 'code', '��֤��', '0', NULL, 'lang');",
		"INSERT INTO `{$mydbpre}setting` VALUES (NULL, 'contact', '��ϵ����', '0', NULL, 'lang');",
		"INSERT INTO `{$mydbpre}setting` VALUES (NULL, 'contactadmin', '��ϵվ��', '0', NULL, 'lang');",
		"INSERT INTO `{$mydbpre}setting` VALUES (NULL, 'content', '��������', '0', NULL, 'lang');",
		"INSERT INTO `{$mydbpre}setting` VALUES (NULL, 'delete', 'ɾ��', '0', NULL, 'lang');",
		"INSERT INTO `{$mydbpre}setting` VALUES (NULL, 'deleted', ' ɾ �� ', '0', NULL, 'lang');",
		"INSERT INTO `{$mydbpre}setting` VALUES (NULL, 'deletem', 'ɾ������', '0', NULL, 'lang');",
		"INSERT INTO `{$mydbpre}setting` VALUES (NULL, 'downm', 'ȡ���ö�', '0', NULL, 'lang');",
		"INSERT INTO `{$mydbpre}setting` VALUES (NULL, 'edit', '�༭', '0', NULL, 'lang');",
		"INSERT INTO `{$mydbpre}setting` VALUES (NULL, 'editm', ' �� �� ', '0', NULL, 'lang');",
		"INSERT INTO `{$mydbpre}setting` VALUES (NULL, 'email', '����', '0', NULL, 'lang');",
		"INSERT INTO `{$mydbpre}setting` VALUES (NULL, 'face', 'face', '0', NULL, 'lang');",
		"INSERT INTO `{$mydbpre}setting` VALUES (NULL, 'favorite', '�����ղ�', '0', NULL, 'lang');",
		"INSERT INTO `{$mydbpre}setting` VALUES (NULL, 'floor', '¥', '0', NULL, 'lang');",
		"INSERT INTO `{$mydbpre}setting` VALUES (NULL, 'homepage', '��ҳ', '0', NULL, 'lang');",
		"INSERT INTO `{$mydbpre}setting` VALUES (NULL, 'ifdeletem', 'ȷ��ɾ��������', '0', NULL, 'lang');",
		"INSERT INTO `{$mydbpre}setting` VALUES (NULL, 'links', '������������û���Զ���ת����������', '0', NULL, 'lang');",
		"INSERT INTO `{$mydbpre}setting` VALUES (NULL, 'login', '����Ա��½', '0', NULL, 'lang');",
		"INSERT INTO `{$mydbpre}setting` VALUES (NULL, 'logined', ' �� ½ ', '0', NULL, 'lang');",
		"INSERT INTO `{$mydbpre}setting` VALUES (NULL, 'logout', '�˳�', '0', NULL, 'lang');",
		"INSERT INTO `{$mydbpre}setting` VALUES (NULL, 'manage', '��������', '0', NULL, 'lang');",
		"INSERT INTO `{$mydbpre}setting` VALUES (NULL, 'message', '�ÿ�����', '0', NULL, 'lang');",
		"INSERT INTO `{$mydbpre}setting` VALUES (NULL, 'nickname', '�ǳ�', '0', NULL, 'lang');",
		"INSERT INTO `{$mydbpre}setting` VALUES (NULL, 'password', '�� ��', '0', NULL, 'lang');",
		"INSERT INTO `{$mydbpre}setting` VALUES (NULL, 'phpfans', 'php������', '0', NULL, 'lang');",
		"INSERT INTO `{$mydbpre}setting` VALUES (NULL, 'picture', '����������ͼƬˢ��', '0', NULL, 'lang');",
		"INSERT INTO `{$mydbpre}setting` VALUES (NULL, 'post', 'ǩд����', '0', NULL, 'lang');",
		"INSERT INTO `{$mydbpre}setting` VALUES (NULL, 'qq', 'QQ/oicq', '0', NULL, 'lang');",
		"INSERT INTO `{$mydbpre}setting` VALUES (NULL, 'reload', '����������ͼƬˢ��', '0', NULL, 'lang');",
		"INSERT INTO `{$mydbpre}setting` VALUES (NULL, 'replied', ' �� �� ', '0', NULL, 'lang');",
		"INSERT INTO `{$mydbpre}setting` VALUES (NULL, 'reply', '�ظ�', '0', NULL, 'lang');",
		"INSERT INTO `{$mydbpre}setting` VALUES (NULL, 'replyer', '�ظ���', '0', NULL, 'lang');",
		"INSERT INTO `{$mydbpre}setting` VALUES (NULL, 'replym', '�ظ�����', '0', NULL, 'lang');",
		"INSERT INTO `{$mydbpre}setting` VALUES (NULL, 'sethome', '��Ϊ��ҳ', '0', NULL, 'lang');",
		"INSERT INTO `{$mydbpre}setting` VALUES (NULL, 'submit', '��������', '0', NULL, 'lang');",
		"INSERT INTO `{$mydbpre}setting` VALUES (NULL, 'submitted', ' �� �� ', '0', NULL, 'lang');",
		"INSERT INTO `{$mydbpre}setting` VALUES (NULL, 'submittime', '������', '0', NULL, 'lang');",
		"INSERT INTO `{$mydbpre}setting` VALUES (NULL, 'time', 'ʱ��', '0', NULL, 'lang');",
		"INSERT INTO `{$mydbpre}setting` VALUES (NULL, 'title', '��������', '0', NULL, 'lang');",
		"INSERT INTO `{$mydbpre}setting` VALUES (NULL, 'tips', 'php���������Բ� ��ʾ��Ϣ', '0', NULL, 'lang');",
		"INSERT INTO `{$mydbpre}setting` VALUES (NULL, 'up', '�ö�', '0', NULL, 'lang');",
		"INSERT INTO `{$mydbpre}setting` VALUES (NULL, 'upm', '��������', '0', NULL, 'lang');",
		"INSERT INTO `{$mydbpre}setting` VALUES (NULL, 'upordown', '�ö�/����ö�', '0', NULL, 'lang');",
		"INSERT INTO `{$mydbpre}setting` VALUES (NULL, 'username', '�û���', '0', NULL, 'lang');",
		"INSERT INTO `{$mydbpre}setting` VALUES (NULL, 'view', '�������', '0', NULL, 'lang');",
		"INSERT INTO `{$mydbpre}setting` VALUES (NULL, 'which', '��', '0', NULL, 'lang');",
		"INSERT INTO `{$mydbpre}setting` VALUES (NULL, 'm1', '�ǳ���л�����������Ѿ����������ڽ�ת�����Բ���ҳ��', '1', NULL, 'lang');",
		"INSERT INTO `{$mydbpre}setting` VALUES (NULL, 'm2', '���Ļظ��Ѿ����������ڽ�ת�������ǰ��ҳ�档', '1', NULL, 'lang');",
		"INSERT INTO `{$mydbpre}setting` VALUES (NULL, 'm3', 'ɾ�����Գɹ������ڽ�ת�������ǰ��ҳ�档', '1', NULL, 'lang');",
		"INSERT INTO `{$mydbpre}setting` VALUES (NULL, 'm4', '�ǳƻ����ݲ���Ϊ�գ������ǩд���ԡ�', '1', NULL, 'lang');",
		"INSERT INTO `{$mydbpre}setting` VALUES (NULL, 'm5', '������Ĺ���Ա���벻��ȷ���㲻�߱�������Ȩ�ޡ�', '1', NULL, 'lang');",
		"INSERT INTO `{$mydbpre}setting` VALUES (NULL, 'm6', '��������Ϣ���뺬��һ�����֣�лл������', '1', NULL, 'lang');",
		"INSERT INTO `{$mydbpre}setting` VALUES (NULL, 'm7', '�㷢�������԰�����վ��Ϊ���е��ַ�����վ�ܾ��㷢�ԡ�', '1', NULL, 'lang');",
		"INSERT INTO `{$mydbpre}setting` VALUES (NULL, 'm8', '���ip�Ѿ��ڱ�վ�������У���վ�ܾ��㷢�ԡ�', '1', NULL, 'lang');",
		"INSERT INTO `{$mydbpre}setting` VALUES (NULL, 'm9', '�㷢�������Գ�����վ���õ���󳤶ȣ������ǩд���ԡ�', '1', NULL, 'lang');",
		"INSERT INTO `{$mydbpre}setting` VALUES (NULL, 'm10', '�㷢�����Թ��죬����Ϣһ���ٷ���', '1', NULL, 'lang');",
		"INSERT INTO `{$mydbpre}setting` VALUES (NULL, 'm11', '�ö�/����ö����Գɹ��������ǰ��ҳ�档', '1', NULL, 'lang');",
		"INSERT INTO `{$mydbpre}setting` VALUES (NULL, 'm12', '���Ѿ���½�������ظ���½��', '1', NULL, 'lang');",
		"INSERT INTO `{$mydbpre}setting` VALUES (NULL, 'm13', '��֤���Ѿ����ڣ������·��ԡ�', '1', NULL, 'lang');",
		"INSERT INTO `{$mydbpre}setting` VALUES (NULL, 'm14', '��֤�벻��ȷ����������д��', '1', NULL, 'lang');",
		"INSERT INTO `{$mydbpre}setting` VALUES (NULL, 'm15', 'email��ʽ����ȷ����������д��', '1', NULL, 'lang');",
		"INSERT INTO `{$mydbpre}setting` VALUES (NULL, 'm16', '��½�ɹ���������ת�����½ǰ��ҳ�档', '1', NULL, 'lang');",
		"INSERT INTO `{$mydbpre}setting` VALUES (NULL, 'm17', '�û������������������µ�½��', '1', NULL, 'lang');",
		"INSERT INTO `{$mydbpre}setting` VALUES (NULL, 'm18', '�Ѿ��ɹ��˳���������ת�����˳�ǰ��ҳ�档', '1', NULL, 'lang');",
		"INSERT INTO `{$mydbpre}setting` VALUES (NULL, 'm19', '�ǳƲ���Ϊ�ա�', '1', NULL, 'lang');",
		"INSERT INTO `{$mydbpre}setting` VALUES (NULL, 'm20', '��֤�벻��Ϊ�ա�', '1', NULL, 'lang');",
		"INSERT INTO `{$mydbpre}setting` VALUES (NULL, 'm21', '��֤�벻��ȷ��', '1', NULL, 'lang');",
		"INSERT INTO `{$mydbpre}setting` VALUES (NULL, 'm22', 'Email��ʽ����ȷ��', '1', NULL, 'lang');",
		"INSERT INTO `{$mydbpre}setting` VALUES (NULL, 'm23', 'QQ���벻��ȷ��', '1', NULL, 'lang');",
		"INSERT INTO `{$mydbpre}setting` VALUES (NULL, 'm24', '��ҳ��ַ����ȷ��', '1', NULL, 'lang');",
		"INSERT INTO `{$mydbpre}setting` VALUES (NULL, 'm25', '�༭���Գɹ������ڽ�ת�������ǰ��ҳ�档', '1', NULL, 'lang');",
		"INSERT INTO `{$mydbpre}setting` VALUES (NULL, 'm26', '���ȵ�½�ٽ��й���', '1', NULL, 'lang');",
		"INSERT INTO `{$mydbpre}setting` VALUES (NULL, 'addavatars', 'ѡ��ͷ��', '0', NULL, 'lang');",
		"INSERT INTO `{$mydbpre}setting` VALUES (NULL, 'cancelavatars', 'ȡ��ѡ��', '0', NULL, 'lang');",
		"INSERT INTO `{$mydbpre}setting` VALUES (NULL, 'bnickname', '�ǳƲ���Ϊ�ա�', '2', NULL, 'lang');",
		"INSERT INTO `{$mydbpre}setting` VALUES (NULL, 'bcode', '��֤�벻��Ϊ�ա�', '2', NULL, 'lang');",
		"INSERT INTO `{$mydbpre}setting` VALUES (NULL, 'eemail', 'Email��ʽ����ȷ��', '2', NULL, 'lang');",
		"INSERT INTO `{$mydbpre}setting` VALUES (NULL, 'ecode', '��֤�벻��ȷ��', '2', NULL, 'lang');",
		"INSERT INTO `{$mydbpre}setting` VALUES (NULL, 'eqq', 'QQ���벻��ȷ��', '2', NULL, 'lang');",
		"INSERT INTO `{$mydbpre}setting` VALUES (NULL, 'ehomepage', '��ҳ��ַ����ȷ��', '2', NULL, 'lang');",
		"INSERT INTO `{$mydbpre}setting` VALUES (NULL, 'bcontent', '�������ݲ���Ϊ�ա�', '2', NULL, 'lang');",
		"INSERT INTO `{$mydbpre}setting` VALUES (NULL, '�������', './', '_self', '0', 'topbar');",
		"INSERT INTO `{$mydbpre}setting` VALUES (NULL, '��������', 'post.php', '_self', '1', 'topbar');",
		"INSERT INTO `{$mydbpre}post` VALUES (NULL, '�Ҳ�����', 'deng5765@163.com', '245821218', 'http://www.phpfans.net', 'images/avatars/01.gif', '��л��ʹ��php���������Բ�', '<P>�ǳ���л���php���������Բ���֧��</P><P>��ʹ���������κ�����,�뵽</P><P><A target=\"_blank\" href=\"http://www.phpfans.net/gustbook/\">http://www.phpfans.net/gustbook/</A>&nbsp;</P><P>����</P><P><A target=\"_blank\" href=\"http://www.phpfans.net/bbs/forumdisplay.php?fid=24\">http://www.phpfans.net/bbs/forumdisplay.php?fid=24</A>&nbsp;</P><P>��ð����������Ľ���.</P><p>ͬʱ�������й�עphp���������Բ��ĸ������</P><P><a target=\"_blank\" href=\"http://www.phpfans.net/bbs/viewthread.php?tid=6345&extra=page%3D1\">http://www.phpfans.net/bbs/viewthread.php?tid=6345&extra=page%3D1</a></P><p>���л�ӭ��ҹ���php������վ</P><P><A target=\"_blank\" href=\"http://www.phpfans.net\">http://www.phpfans.net</A></P><p>php������վ��̳</P><P><A target=\"_blank\" href=\"http://www.phpfans.net/bbs/\">http://www.phpfans.net/bbs/</A>&nbsp;<img src=\"fckeditor/editor/images/smiley/smile/smile13.gif\"></P>', '127.0.0.1', ".time().",1,1)"
	);
	
	$installStr = '';
	$dbsql = "CREATE DATABASE IF NOT EXISTS ".$mydbname;
	$db->query($dbsql);
	$installStr .= "�������ݿ�{$mydbname}�ɹ�>><br>";
	$db->select_db($mydbname);
	
	foreach ($sqlTables as $tableName => $tableSql){
		$db->query("DROP TABLE IF EXISTS {$tableName}");
		$db->query($tableSql);
		$installStr .= "�������ݱ� {$tableName} �ɹ�>><br>";
	}
	
	foreach ($sqlInsert as $sql){
		$db->query($sql);
	}
	$installStr .= "��ʼ�����ݱ�ɹ�<br>";
	
	$str = "<?php\n";
	$str .= "\$mydbhost = '{$mydbhost}';//���ݿ������\n";
	$str .= "\$mydbuser ='{$mydbuser}';//���ݿ��û���\n";
	$str .= "\$mydbpw = '{$mydbpw}';//���ݿ�����\n";
	$str .= "\$mydbname = '{$mydbname}';//���ݿ���\n";
	$str .= "\$mydbpre = '{$mydbpre}';//���ݱ�ǰ��\n";
	$str .= "\$mydbcharset = 'gbk';//���ݿ����,�������޸�\n";
	$str .= "define('VERSION','2.0');\n";
	$str .= "?>";
	
	if(is_writable_file('include/config.php')){
		writeFile('include/config.php',$str);
		$installStr .= "�����ĵ� config.php ���³ɹ�>><br><br>";
		$installStr .= "��װ˳�����.��ȫ���,��ɾ��install.php��update.php<br>";
		$installStr .= '<a href="index.php">�������Բ���ҳ</a>';
	}
	else
		$installStr = "�ļ�include/config.php����д�����ֶ�����config.php�ϵ�����<br>";
	
	$step = 5;
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=gb2312" />
<title>��װphp���������԰�</title>
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
>> php���������԰� 2.0 ��װ��  
</div>
<?php 
switch($step){
	case 1:
	if(!file_exists('cache/install_2.0.lock')){
?>
<table align="center" border="0" cellpadding="0" cellspacing="1" class="admintb" style="width:70%;table-layout:fixed;">
  <tr>
    <td class="header">�û�Э��</td>
  </tr>
  <tr>
    <td style="padding:5px; font-size:12px;">
<strong>php������ʹ��Э�飺</strong>
<p>��Ȩ���� (c) 2006-2008��<a href="http://www.phpfans.net">php������վ</a><br />
��������Ȩ����


<p>����ȨЭ�������� php���������԰� ���а汾��php������վӵ��Э������ս���Ȩ��
<ul type="I">
<p><li><b>Э����ɵ�Ȩ��</b></li>
<ul type="1">
<li>�����Ը����Լ���Ҫ�޸� php���������԰� Դ��������������Ӧ������վҪ��</li>
<li>���������û�������ʹ��php���������԰巢�����Ժͻظ����������е����������ݵ���ط�������</li>
</ul>

<p><li><b>Э���Լ��</b></li>
<ul type="1">
<li>δ���������֮ǰ�����ý� php���������԰� ������ҵ��;��</li>
<li><font color="red">δ��������ɣ����԰�ҳ��ҳ�Ŵ��İ�Ȩ��Ϣ PHPfans �������� http://www.phpfans.net �����뱣����������������޸ġ���������Ҫ����������ϵ��</font></li>
<li>��ֹ�� php���������԰� ��������κβ��ֻ������Է�չ�κ��޸İ汾�������·ַ���</li>
</ul>

<p><li><b>��������</b></li>
<ul type="1">
<li>php���������԰���phpѧϰ�ͽ���ΪĿ�ģ����е��κ��������Ρ�</li>
<li>�û�������Ը��ʹ�ñ����԰壬���ǲ��е��κ���ʹ�ñ���������������������Ρ�</li>
<li>php������վ����ʹ��php���������԰幹�������԰��е����Ի�ظ��е����Ρ�</li>
</ul>
</ul>
<p>��л��ѡ�� php���������԰塣ϣ�����ǵ����԰�����е����⡣
<p>
  php������վ������php������ѧϰ�ͽ�����<br />
  php������վ��ַΪ <a href="http://www.phpfans.net">http://www.phpfans.net</a>��<br />
  php��������������ַΪ <a href="http://www.phpfans.net/bbs/">http://www.phpfans.net/bbs/</a>��<br /> 
  php���������԰���ַΪ <a href="http://www.phpfans.net/guestbook/">http://www.phpfans.net/guestbook/</a>��<br /> 
</p>
    </td>
  </tr>
</table>
<div style="text-align:center">
<input type="button" value="��ͬ��" onclick="window.location='install.php?step=2';" />
<input type="button" value="�Ҳ�ͬ��" onclick="window.close();" />
</div>
<?php 
	}
	else{
?>
<table align="center" border="0" cellpadding="0" cellspacing="1" class="admintb" style="width:70%;table-layout:fixed;">
  <tr>
    <td class="header">������Ϣ</td>
  </tr>
  <tr>
    <td style="padding:5px;">
    ���Ѿ���װ�����԰壬Ϊ�˱�֤���԰����ݰ�ȫ�����ֶ�ɾ�� install.php �ļ�������������°�װ���԰壬��ɾ�� cache/install_2.0.lock �ļ����ٴ����а�װ�ļ�install.php��
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
				$statusStr = '<font color=#0000FF>��д</font>';
			else{
				$allstatus = false;
				$statusStr = '<font color=#FF0000>����д</font>';
			}
		
			$statusHTML .= htmlTag('tr','',
				htmlTag('td','',$value)
				. htmlTag('td','',"��д")
				. htmlTag('td','',$statusStr)
			);
		}
?>
<table align="center" border="0" cellpadding="0" cellspacing="1" class="admintb" style="width:70%;table-layout:fixed;">
  <tr>
    <td class="header">��ʾ��Ϣ</td>
  </tr>
  <tr>
    <td style="padding:5px;">
    <li>��ѹ������ upload Ŀ¼��ȫ���ļ���Ŀ¼�ϴ�����������</li>
    <li>�����ʹ�÷� WINNT ϵͳ���޸��������ԣ�
    <div style="padding-left:20px;">
    &nbsp; &nbsp; ./include/config.php �ļ� 777;<br>
    &nbsp; &nbsp; ./cache Ŀ¼ 777;<br>
    &nbsp; &nbsp; ./data Ŀ¼ 777;<br>
    &nbsp; &nbsp; ./images/avatars Ŀ¼ 777;
    </div>
    </li>
    <li>���include/config.php�ļ�����д���������޸ĸ��ļ��ϴ���includeĿ¼�¡�</li>
    </td>
  </tr>
</table>
<table id="statustb" align="center" border="0" cellpadding="0" cellspacing="1" class="admintb" style="width:70%;table-layout:fixed;">
  <tr>
    <td class="header">Ŀ¼�ļ�����</td>
    <td class="header">Ҫ��</td>
    <td class="header">��ǰ״̬</td>
  </tr> 
  <?php echo $statusHTML;?>
</table>
<div style="text-align:center">
<input type="button" value="��һ��" onclick="window.history.back();"/>
<input type="button" value="��һ��" onclick="window.location='install.php?step=3';" <?php echo !$allstatus? 'diabled' : '';?> />
<input type="button" value="���¼��" onclick="window.location.reload();" />
</div>
<?php 
	break;
	case 3:
		if($errorStr){
?>
<table align="center" border="0" cellpadding="0" cellspacing="1" class="admintb" style="width:70%;table-layout:fixed;">
  <tr>
    <td class="header">��ʾ��Ϣ</td>
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
    <td colspan="2" class="header">��װ php���������Բ�</td>
    </tr>
  <tr>
    <td style="width:30%">���ݿ������:</td>
    <td><input name="m_host" type="text" id="m_host" value="localhost"> ���ݿ��������ַ, һ��Ϊ localhost</td>
  </tr>
  <tr>
    <td>���ݿ��û���:</td>
    <td><input name="m_root" type="text" id="m_root" /> ���ݿ��˺��û���</td>
  </tr>
  <tr>
    <td>���ݿ�����:</td>
    <td><input name="m_pw" type="password" id="m_pw" /> ���ݿ��˺�����</td>
  </tr>
  <tr>
    <td>���ݿ���:</td>
    <td><input name="m_db" type="text" id="m_db"> �������Զ�����</td>
  </tr>
  <tr>
    <td>���ݱ�ǰ��</td>
    <td><input name="m_pre" type="text" id="m_pre" value="gue_"> ͬһ���ݿⰲװ�����԰�ʱ�ɸı�</td>
  </tr>
  <tr>
    <td colspan="2" style="text-align:center;">
    <input type="button" value="��һ��" onclick="window.history.back();"/>
    <input type="submit" name="Submit" value="��һ��" /> 
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
    <td colspan="2" class="header">���ù���Ա�˺�</td>
   </tr>
  <tr>
    <td>���Բ�����Ա��:</td>
    <td><input name="m_admin" type="text" id="m_admin" value="�Ҳ�����"> &nbsp;<input type="hidden" name="storemsg" value="<?php echo $msg;?>"></td>
  </tr>
  <tr>
    <td>���Բ�����Աemail:</td>
    <td><input name="m_email" type="text" id="m_email" value="deng5765@163.com"></td>
  </tr>
  <tr>
    <td>���Բ�����Ա����:</td>
    <td><input name="m_adminpw" type="password" id="m_adminpw"> &nbsp;</td>
  </tr>
  <tr>
    <td colspan="2" style="text-align:center;">
    <input type="button" value="��һ��" onclick="window.history.back();"/>
    <input type="submit" name="createDB" value="��һ��" /> 
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
    <td class="header">��װ��Ϣ</td>
  </tr>
  <tr>
    <td>
    <?php echo $installStr;?>
    </td>
  </tr>
</table>
<div style="text-align:center">
<input type="button" value="�������԰�" onclick="window.location='index.php';" />
<input type="button" value="����Ա��½" onclick="window.location='login.php';" />
</div>
<?php 
	writeFile('cache/install_2.0.lock','');
	break;
}
?>
</body>
</html>