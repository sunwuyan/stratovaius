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
require_once('include/common.inc.php');
require_once('lang/message_zh.php');
require_once('admin/global.func.php');

checkAdminLogin($islogin,false);

if(isset($_GET['act'])){
	switch($_GET['act']){
		case 'header': require('admin/header.html');break;
		case 'footer': require('admin/footer.html');break;
		case 'left': require('admin/left.php');break;
		case 'about': require('admin/about.php');break;
		case 'basic': require('admin/basic.php');break;
		case 'css': require('admin/css.php');break;
		case 'navigation': require('admin/navigation.php');break;
		case 'avatars': require('admin/avatars.php');break;
		case 'lang': require('admin/lang.php');break;
		case 'export': require('admin/export.php');break;
		case 'import': require('admin/import.php');break;
		case 'bword': require('admin/bword.php');break;
		case 'bip': require('admin/bip.php');break;
		case 'replace': require('admin/replace.php');break;
		case 'versioninfor':getUpdateInfo();break;
		case 'chpw': require('admin/chpw.php');break;
		default: require('admin/about.php');
	}
	exit;
}
?>
<title><?php echo $vartem['setting']['basic']['name']['value']."_后台管理";?></title>
<frameset rows="45,280,12" cols="*">
  <frame src="admincp.php?act=header" scrolling="No" noresize="noresize">
<frameset rows="*" cols="104,660">
  <frame src="admincp.php?act=left" noresize="noresize">
  <frame src="admincp.php?act=about" name="right">
</frameset>
  <frame src="admincp.php?act=footer" scrolling="No" noresize="noresize">
</frameset>
<noframes><body>
</body>
</noframes>