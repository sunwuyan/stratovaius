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
error_reporting(0);

require_once('include/function.php');

$start = gettime();

if(PHP_VERSION < '4.1.0') {
	$_GET = &$HTTP_GET_VARS;
	$_POST = &$HTTP_POST_VARS;
	$_COOKIE = &$HTTP_COOKIE_VARS;
	$_SERVER = &$HTTP_SERVER_VARS;
}

sqlEncode($_COOKIE);
sqlEncode($_POST);
sqlEncode($_GET);

$islogin = checklogin();

require_once('include/config.php');
require_once('include/class.php');
$lang_url = file_exists('cache/lang_zh.php') ? 'cache/lang_zh.php' : 'lang/lang_zh.php';
include_once($lang_url);

$db = new mysql();
$db->connect($mydbhost, $mydbuser, $mydbpw, $mydbname,$mydbcharset);

$fanso = new Fanso_lite();

$vartem['version'] = VERSION;
$vartem['setting'] = setting('basic','bottombar','topbar');
$vartem['lang'] = $langArr;

$vartem['navigation'] = navigation('topbar').loginBar($islogin);
$vartem['aboutus'] = navigation('bottombar');
$vartem['copyright'] = copyRight();

$vartem['onload'] = '';
$vartem['loadscript'] = '';
$vartem['csslink'] = file_exists('cache/guest.css') ? "./cache/guest.css" : "./css/guest.css";
?>