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
		"INSERT INTO `{$mydbpre}post` VALUES (NULL, '�Ҳ�����', 'deng5765@163.com', '245821218', 'http://www.phpfans.net', 'images/avatars/01.gif', '��л��ʹ��php���������Բ�', '<P>�ǳ���л���php���������Բ���֧��</P><P>��ʹ���������κ�����,�뵽</P><P><A target=\"_blank\" href=\"http://www.phpfans.net/gustbook/\">http://www.phpfans.net/gustbook/</A>&nbsp;</P><P>����</P><P><A target=\"_blank\" href=\"http://www.phpfans.net/bbs/forumdisplay.php?fid=24\">http://www.phpfans.net/bbs/forumdisplay.php?fid=24</A>&nbsp;</P><P>��ð����������Ľ���.</P><p>ͬʱ�������й�עphp���������Բ��ĸ������</P><P><a target=\"_blank\" href=\"http://www.phpfans.net/bbs/viewthread.php?tid=6345&extra=page%3D1\">http://www.phpfans.net/bbs/viewthread.php?tid=6345&extra=page%3D1</a></P><p>���л�ӭ��ҹ���php������վ</P><P><A target=\"_blank\" href=\"http://www.phpfans.net\">http://www.phpfans.net</A></P><p>php������վ��̳</P><P><A target=\"_blank\" href=\"http://www.phpfans.net/bbs/\">http://www.phpfans.net/bbs/</A>&nbsp;<img src=\"fckeditor/editor/images/smiley/smile/smile13.gif\"></P>', '127.0.0.1', ".time().",1,1)"
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
		"INSERT INTO `{$mydbpre}setting` VALUES (NULL, '��������', '#', '_self', '0', 'bottombar');",
		"INSERT INTO `{$mydbpre}setting` VALUES (NULL, '��ϵ����', 'mailto:deng5765@163.com', '_self', '1', 'bottombar');",
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
		"INSERT INTO `{$mydbpre}setting` VALUES (NULL, 'email', 'deng5765@163.com', '0', NULL, 'lang');",
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
		"INSERT INTO `{$mydbpre}setting` VALUES (NULL, '��������', 'post.php', '_self', '1', 'topbar');"
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
	$updateStr .= "�޸����ݱ� {$mydbpre}post �ṹ�ɹ���<br>��ʼ�� {$mydbpre}post �ɹ���<br>";
	foreach ($sql_setting as $sql){
		$db->query($sql);
	}
	$updateStr .= "�޸����ݱ� {$mydbpre}setting �ṹ�ɹ���<br>��ʼ�� {$mydbpre}post �ɹ���<br>";
	
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
	$updateStr .= "ɾ�������ļ��ɹ���<br>";
	
	$configContent = read_file('include/config.php');
	$configContent = preg_replace("/define\('VERSION','(.+?)'\);/i","define('VERSION','2.0');",$configContent);
	writeFile('include/config.php',$configContent);
	$updateStr .= "�޸������ļ� include/config.php �ɹ���";
	$updateStr .= "<br><br>������˳�����.��ȫ���,��ɾ��update.php��install.php";
	$step = 4;
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=gb2312" />
<title>����php���������԰�</title>
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
>> php���������԰� 1.5 �� 2.0 ������  
</div>
<?php 
switch($step){
	case 1:
	if(VERSION == '2.0'){
?>
<table align="center" border="0" cellpadding="0" cellspacing="1" class="admintb" style="width:70%;table-layout:fixed;">
  <tr>
    <td class="header">��ʾ��Ϣ</td>
  </tr>
  <tr>
    <td style="padding:5px;">
    ��ǰ�汾2.0�Ѿ������°汾��������¡�
    </td>
  </tr>
</table>
<?php 	
	}
	elseif(!file_exists('cache/update_2.0.lock')){
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
<input type="button" value="��ͬ��" onclick="window.location='update.php?step=2';" />
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
    ���Ѿ����¹����԰壬Ϊ�˱�֤���԰����ݰ�ȫ�����ֶ�ɾ�� update.php �ļ�������������°�װ���԰壬��ɾ�� cache/update_2.0.lock �ļ����ٴ����а�װ�ļ�update.php��
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
    <ul>
    <li>��ѹ������ upload Ŀ¼��<font style="font-size:16px;color:red; font-weight:bold;">�� include/config.php</font> ��ȫ���ļ���Ŀ¼�ϴ�����������������1.5�汾�����԰���ļ���</li>
    <li>�����ʹ�÷� WINNT ϵͳ���޸��������ԣ�
    <div style="padding-left:20px;">
    &nbsp; &nbsp; ./include/config.php �ļ� 777;<br>
    &nbsp; &nbsp; ./cache Ŀ¼ 777;<br>
    &nbsp; &nbsp; ./data Ŀ¼ 777;<br>
    &nbsp; &nbsp; ./images/avatars Ŀ¼ 777;
    </div>
    </li>
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
<input type="button" value="��һ��" onclick="window.location='update.php?step=3';" <?php echo !$allstatus? 'diabled' : '';?> />
<input type="button" value="���¼��" onclick="window.location.reload();" />
</div>
<?php 
	break;
	case 3:
?>
<table align="center" border="0" cellpadding="0" cellspacing="1" class="admintb" style="width:70%;">
<form id="form1" name="form1" method="post" action="">
  <tr>
    <td class="header">���� php���������Բ�</td>
    </tr>
  <tr>
    <td style="padding:10px;">
    <ul>
    <li>���³�����޸����ݱ�Ľṹ���������ݣ����һ�ɾ��һЩ������ļ�������ǰ���������ݺ��ļ��ı��ݡ�</li>
    </ul>
    <div style="text-align:center;">
    <input type="button" value="��һ��" onclick="window.history.back();"/>
    <input type="submit" name="update" value=" �� �� " /> 
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
    <td class="header">������Ϣ</td>
  </tr>
  <tr>
    <td style="padding:10px;">
    <?php echo $updateStr;?>
    </td>
  </tr>
</table>
<div style="text-align:center">
<input type="button" value="�������԰�" onclick="window.location='index.php';" />
<input type="button" value="����Ա��½" onclick="window.location='login.php';" />
</div>
<?php 
	writeFile('cache/update_2.0.lock','');
	break;
}
?>
</body>
</html>