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
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=gb2312" />
<title>后台菜单</title>
<style type="text/css">
body{ margin:0}
.table{ background:#eeeeee;text-align:left; margin:5px 0 5px 5px;}
.table .td{ background:#FFFFFF; height:25px;padding-left:5px;}
.header{ background:#DDDDDD; height:25px; padding-left:5px;}
</style>
</head>

<body>
<table width="95%" border="0" cellpadding="0" cellspacing="1" class="table">
  <tr>
    <td class="header"><a href="admincp.php?act=about" target="right">后台首页</a></td>
  </tr>
  <tr>
    <td class="td"><a href="admincp.php?act=basic" target="right">基本设置</a></td>
  </tr>
  <tr>
    <td class="td"><a href="admincp.php?act=css" target="right">字体颜色设置</a></td>
  </tr>
  <tr>
    <td class="td"><a href="admincp.php?act=navigation" target="right">导航设置</a></td>
  </tr>
  <tr>
    <td class="td"><a href="admincp.php?act=lang" target="right">界面语言</a></td>
  </tr>
  <tr>
    <td class="td"><a href="admincp.php?act=avatars" target="right">头像设置</a></td>
  </tr>
  <tr>
    <td class="td"><a href="admincp.php?act=replace" target="right">语句过滤</a></td>
  </tr>
  <tr>
    <td class="td"><a href="admincp.php?act=bword" target="right">限制关键字</a></td>
  </tr>
  <tr>
    <td class="td"><a href="admincp.php?act=bip" target="right">限制IP</a></td>
  </tr>
  <tr>
    <td class="td"><a href="admincp.php?act=export" target="right">数据备份</a></td>
  </tr>
  <tr>
    <td class="td"><a href="admincp.php?act=import" target="right">数据恢复</a></td>
  </tr>
  <tr>
    <td class="td"><a href="admincp.php?act=chpw" target="right">密码修改</a></td>
  </tr>
  <tr>
    <td class="td"><a href="login.php?logout=true" target="_parent">退出</a></td>
  </tr>
</table>
</body>
</html>
