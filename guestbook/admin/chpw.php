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

if(isset($_POST['submit'])){
	if(md5($_POST['oldpw']) == $vartem['setting']['basic']['password']['value']){
		if(trim($_POST['newpw']) == '' || trim($_POST['username']) == '') showadmin('新密码不能为空。',$_SERVER['HTTP_REFERER']);
		if($_POST['newpw'] != $_POST['renewpw']) showadmin('两次输入密码不一样。',$_SERVER['HTTP_REFERER']);
		$db->query("update {$mydbpre}setting set val = '".$_POST['username']."' where keyword = 'username' and type = 'basic' limit 1");
		$db->query("update {$mydbpre}setting set val = '".md5($_POST['newpw'])."' where keyword = 'password' and type = 'basic' limit 1");
		showadmin('更新成功。');
	}
	else showadmin('你的旧密码不正确。',$_SERVER['HTTP_REFERER']);
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=gb2312" />
<title>管理员密码修改</title>
<link type="text/css" rel="stylesheet" href="./css/guest.css">
</style>
</head>

<body>
<form id="form1" name="form1" method="post" action="" onsubmit="return checkip();">
  <table border="0" cellpadding="0" cellspacing="1" class="admintb" align="center">
    <tr>
      <td colspan="2" class="header">管理员密码修改</td>
    </tr>	
    <tr>
      <td style="width:150px;">管理员名</td>
      <td><input name="username" type="text" id="username" value="<?php echo $vartem['setting']['basic']['username']['value'];?>" /></td>
    </tr>
    <tr>
      <td>旧密码</td>
      <td><input type="password" name="oldpw" /></td>
    </tr>
    <tr>
      <td>新密码</td>
      <td><input type="password" name="newpw" /></td>
    </tr>
    <tr>
      <td>确认新密码</td>
      <td><input type="password" name="renewpw" /></td>
    </tr>
	<tr>
      <td colspan="2">
  <div align="center">
    <input type="submit" name="submit" value=" 提 交 " />
  </div>
  	</td>
    </tr>
  </table>
</form>
</body>
</html>
