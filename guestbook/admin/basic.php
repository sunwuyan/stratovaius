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

if(isset($_POST['name'])){
	if(!preg_match('/^\d+$/',$_POST['eachpage'])) showadmin('每页显示留言条数必须为整数',$_SERVER['HTTP_REFERER']);
	if(!preg_match('/^\d+$/',$_POST['maxlen'])) showadmin('每条留言最大字符数必须为整数',$_SERVER['HTTP_REFERER']);
	if(!preg_match('/^\d+$/',$_POST['minsec'])) showadmin('留言间隔时间必须为整数',$_SERVER['HTTP_REFERER']);
	foreach ($_POST as $key => $value){
		$sql = "update {$mydbpre}setting set val = '".$value."' where keyword = '$key' and type = 'basic' limit 1";
		$db->query($sql);
	}
	showadmin('更新成功',$_SERVER['HTTP_REFERER']);
}
//$arr = setting();
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=gb2312" />
<link type="text/css" rel="stylesheet" href="./css/guest.css">
<title>基本设置</title>
</head>

<body>
<form id="form1" name="form1" method="post" action="">
  <table width="98%" border="0" cellpadding="0" cellspacing="1" class="admintb">
    <tr>
      <td colspan="2" class="header">系统设置</td>
    </tr>
    <tr>
      <td>留言板名称</td>
      <td><input name="name" type="text" id="name" value="<?php echo $vartem['setting']['basic']['name']['value'];?>" /></td>
    </tr>
    <tr>
      <td>留言板URL </td>
      <td><input name="url" type="text" id="url"  value="<?php echo $vartem['setting']['basic']['url']['value'];?>" size="40" /></td>
    </tr>
    <tr>
      <td>Logo</td>
      <td><input name="logo" type="text" id="logo" size="45" value="<?php echo $vartem['setting']['basic']['logo']['value'];?>" /></td>
    </tr>
    <tr>
      <td>网站备案码</td>
      <td><input name="icp" type="text" id="icp"  value="<?php echo $vartem['setting']['basic']['icp']['value'];?>" />
      如果没有，请留空</td>
    </tr>
    <tr>
      <td>在线编辑器</td>
      <td>
        <input type="radio" name="fck" value="2" <?php if($vartem['setting']['basic']['fck']['value']==2) echo "checked";?>/>htmlerea编辑器(推荐)
      	<input type="radio" name="fck" value="1" <?php if($vartem['setting']['basic']['fck']['value']==1) echo "checked";?>/>FCK编辑器
        <input type="radio" name="fck" value="0" <?php if($vartem['setting']['basic']['fck']['value']==0) echo "checked";?>/>不使用html编辑器          </td>
    </tr>
    <tr>
      <td>每页显示留言条数</td>
      <td><input name="eachpage" type="text" size="10" id="eachpage" value="<?php echo $vartem['setting']['basic']['eachpage']['value'];?>" /></td>
    </tr>
    <tr>
      <td>留言验证码</td>
      <td>
      	<input type="radio" name="rcode" value="1" <?php if($vartem['setting']['basic']['rcode']['value']==1) echo "checked";?>/>开启(推荐)
        <input type="radio" name="rcode" value="0" <?php if($vartem['setting']['basic']['rcode']['value']==0) echo "checked";?>/>关闭  </td>
    </tr>
    <tr>
      <td>每条留言最大字符数</td>
      <td><input name="maxlen" type="text" id="maxlen" size="10" value="<?php echo $vartem['setting']['basic']['maxlen']['value'];?>" />
      个(0为不限制) </td>
    </tr>
    <tr>
      <td>留言间隔时间</td>
      <td><input name="minsec" type="text" id="minsec" value="<?php echo $vartem['setting']['basic']['minsec']['value'];?>" size="10" /> 
      秒(0为不限制) </td>
    </tr>
    <tr>
      <td>站长email</td>
      <td><input name="email" type="text" id="email" value="<?php echo $vartem['setting']['basic']['email']['value'];?>"/></td>
    </tr>
    <tr>
      <td colspan="2">
	  <div style="text-align:center;"><input type="submit" id="submit" value=" 提 交 " /></div>
	  </td>
    </tr>
  </table>
</form>
</body>
</html>
