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

if(isset($_GET['versioninfor']))
	getUpdateInfo();
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=gbk" />
<link type="text/css" rel="stylesheet" href="./css/guest.css">
<script language="javascript" src="javascript/Jphp.js"></script>
<script language="javascript" src="javascript/guest.js"></script>
<title>后台首页</title>
</head>

<body>
<table border="0" cellpadding="0" cellspacing="1" class="admintb">
    <tr>
      <td colspan="2" class="header">系统信息</td>
    </tr>
    <tr>
      <td width="40%">留言板版本</td>
      <td><?php echo "php爱好者留言板 ".VERSION;?> [<a href="http://www.phpfans.net/bbs/viewthread.php?tid=6345&amp;extra=page%3D1" target="_blank">查看最新版本</a>]</td>
    </tr>
    <tr>
      <td>操作系统及PHP </td>
      <td><?php echo PHP_OS."/PHP".PHP_VERSION;?></td>
    </tr>
    <tr>
      <td>MySQL版本</td>
      <td><?php echo "MySQL版本".$db->version();?></td>
    </tr>
    <tr>
      <td>服务器及版本</td>
      <td><?php $arr = explode(' ',$_SERVER['SERVER_SOFTWARE']); echo $arr[0];?></td>
    </tr>
    <tr class="td">
      <td>检测更新</td>
      <td id="versionTd">
	  <font color="#0000FF">正在检测新版本...</font>[<a href="javascript:getUpdateInfo();">重新检测</a>]
	  </td>
    </tr>
    <tr id="updataMsgTr" style="display:none;">
      <td>更新信息</td>
      <td id="updataMsgTd">&nbsp;</td>
    </tr>
</table>
<br />
<table width="98%" border="0" cellpadding="0" cellspacing="1" class="admintb">
    <tr>
      <td colspan="2" class="header">关于留言板</td>
    </tr>
    <tr>
      <td width="40%">版权所有</td>
      <td><a href="http://www.phpfans.net/" target="_blank">php爱好者</a> 作者：<a href="http://www.phpfans.net/space/?2">我不是鱼</a></td>
    </tr>
    <tr class="td">
      <td>演示</td>
      <td><a href="http://www.phpfans.net/guestbook/" target="_blank">http://www.phpfans.net/guestbook/</a></td>
    </tr>
    <tr>
      <td>更新日志</td>
      <td><a href="http://www.phpfans.net/bbs/viewthread.php?tid=6345&amp;extra=page%3D1" target="_blank">查看更新日志</a></td>
    </tr>
    <tr>
      <td>问题与建议</td>
      <td><a href="http://www.phpfans.net/bbs/forumdisplay.php?fid=24&amp;page=1" target="_blank">提出问题与建议</a> </td>
    </tr>
    <tr>
      <td>PHPfans首页</td>
      <td><a href="http://www.phpfans.net/" target="_blank">http://www.phpfans.net/</a></td>
    </tr>
    <tr>
      <td>PHPfans论坛</td>
      <td><a href="http://www.phpfans.net/bbs/" target="_blank">http://www.phpfans.net/bbs/</a></td>
    </tr>
</table>
</body>
</html>