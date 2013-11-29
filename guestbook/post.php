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
$message_url = file_exists('cache/message_zh.php') ? 'cache/message_zh.php' : 'lang/message_zh.php';
include_once($message_url);

$gbM = new gbManage();
extract($_POST);

if(isset($_POST['edit'])){
	checkAdminLogin($islogin);	
	
	$msg = array();
	$msg['p_name'] = $name;
	$msg['p_content'] = $content;
	$msg['p_image'] = $myim;
	
	$email ? $msg['p_email'] = $email : '';
	$qq ? $msg['p_qq'] = $qq : '';
	$homepage ? $msg['p_homepage'] = $homepage : '';
	$title ? $msg['p_title'] = $title : '';

	$gbM->updateData($mydbpre.'post', $msg, "", "`p_id` = '$p_id'");
	
	showmessage($mearr['m25'],referer());
}
else if(isset($_POST['submit'])){	
	if(!$islogin){
		if($vartem['setting']['basic']['rcode']['value']){
			if(!isset($_COOKIE['randcode']))
				showmessage($mearr['m13'],referer());
			
			if(encode($rcode) != $_COOKIE['randcode'])
			    showmessage($mearr['m14'],referer());
		}
		
		if($vartem['setting']['basic']['minsec']['value'] && isset($_COOKIE['cminsec']) && ($_COOKIE['cminsec'] + $vartem['setting']['basic']['minsec']['value']) > time())
			showmessage($mearr['m10'],'./');
	
		if($name === '' || $content === '')
			showmessage($mearr['m4'],referer());
		
		if($vartem['setting']['basic']['maxlen']['value'] && strlen($content) > $vartem['setting']['basic']['maxlen']['value'])
			showmessage($mearr['m9'],referer());
		
		if(trim($email) !== '' && !preg_match('/^[\w\-]+(\.[\w\-]+)*@[\w\-]+(\.[\w\-]+)*(\.[a-zA-Z]{2,6})$/',$email))
			showmessage($mearr['m15'],referer());
		
		checkContent($content);
		$content = replaceWords($content);	
	}
	$homepage == 'http://' ? $homepage = '' : '';
	
	$msg = array();
	$msg['p_name'] = $name;
	$msg['p_content'] = $content;
	$msg['p_image'] = $myim;
	$msg['p_ip'] = $_SERVER['REMOTE_ADDR'];
	$msg['p_date'] = time();
	$msg['p_editor'] = $htmleditor;
	
	$email ? $msg['p_email'] = $email : '';
	$qq ? $msg['p_qq'] = $qq : '';
	$homepage ? $msg['p_homepage'] = $homepage : '';
	$title ? $msg['p_title'] = $title : '';
		
	$gbM->insertData($mydbpre.'post', $msg);
	
	if($vartem['setting']['basic']['minsec']['value']){
		setcookie("cminsec",time(),time()+2*$vartem['setting']['basic']['minsec']['value']);
	}
	
	showmessage($mearr['m1'],'./');
}

if(isset($_GET['edit']) && !empty($_GET['p_id'])){
	checkAdminLogin($islogin);		

	$sql = "select * from {$mydbpre}post where p_id = '".$_GET['p_id']."'";
	$row = $gbM->return_row($sql);
}

if(!file_exists('cache/avatars.cache.php'))
	$avatarsArr = cacheAvatars($avatarsDir);
else 
	include_once('cache/avatars.cache.php');
	
$vartem['avatars'] = '';
foreach ($avatarsArr as $key => $val){
	$vartem['avatars'] .= "<div class=\"avatar\"><img src=\"{$val['path']}\" onmouseover=\"changeStyle(this,1);\" onmouseout=\"changeStyle(this,0);\" onclick=\"chooseIm(this);\">
	<div style=\"font-size:12px;\"> 
	{$val['name']}</div></div> ";
}
	
$vartem['rowspan'] = 5;	
if($islogin || !$vartem['setting']['basic']['rcode']['value']){
	$vartem['row']['rcodeshow'] = '';
	$vartem['rowspan']--;
}	
else	
	$vartem['row']['rcodeshow'] = htmlTag('tr','',
	     htmlTag('td','',htmlTag('b','',$vartem['lang']['code'].':').htmlTag('font','color="#FF0000"','*'),"\n")
	     . htmlTag('td','',
			 htmlTag('input','name="rcode" type="text" id="rcode" size="6" maxlength="4"','',"\n")
		     . htmlTag('img',"src=\"code.php\" align=\"absmiddle\" style=\"cursor:pointer\" onClick=\"this.src='code.php?timestamp='+new Date().getTime();\"",''," \n")
		     . htmlTag('font','style="font-weight:normal"',$vartem['lang']['reload'],"\n")
		 )
	);	

$vartem['row']['p_name'] = isset($row) ? $row['p_name'] : ($islogin ? $vartem['setting']['basic']['username']['value'] : '');
$vartem['row']['p_email'] = isset($row) ? $row['p_email'] : '';
$vartem['row']['p_qq'] = isset($row) ? $row['p_qq'] : '';
$vartem['row']['p_homepage'] = (isset($row) && !empty($row['p_homepage'])) ? $row['p_homepage'] : 'http://';
$vartem['row']['p_title'] = isset($row) ? $row['p_title'] : '';
$vartem['row']['p_image'] = isset($row) ? $row['p_image'] : (isset($avatarsIndex)? $avatarsIndex :'images/avatars/01.gif');
$vartem['row']['p_content'] = isset($row) ? $row['p_content'] : '';
isset($row) ? $vartem['setting']['basic']['fck']['value'] = $row['p_editor'] : '';
$vartem['row']['submit'] = isset($row) ? 
	'<input name="edit" type="submit" id="edit" value="'.$vartem['lang']['editm'].'">'
	. '<input name="p_id" type="hidden" id="p_id" value="'.$row['p_id'].'">'
	. '<input name="referer" type="hidden" id="referer" value="'. referer() .'">'
	: '<input type="submit" name="submit" value="'.$vartem['lang']['submit'].'">';
	
switch($vartem['setting']['basic']['fck']['value']){
	case 0:		
		$vartem['row']['htmleditor'] = 'echo "<textarea name=\"content\" style=\"width:100%; height:235px;\" id=\"content\">" . $vartem["row"]["p_content"] . "</textarea>";';
		break;
	case 1:
		$vartem['row']['htmleditor'] = 'include("fckeditor/fckeditor.php");
	  	$oFCKeditor = new FCKeditor("content") ;
	  	$oFCKeditor->BasePath = "./fckeditor/";
	  	$oFCKeditor->Value = $vartem["row"]["p_content"];
	  	$oFCKeditor->Create();';
		break;
	case 2:
		$vartem['row']['htmleditor'] = 'echo "<textarea name=\"content\" style=\"width:100%; height:235px;visibility:hidden;\" id=\"content\">" . $vartem["row"]["p_content"] . "</textarea>";';
		$vartem['loadscript'] = "<script language=\"javascript\" type=\"text/javascript\">\n"
			. "var _editor_url = \"htmlarea/\";\n"
			. "var _editor_lang = \"en\";\n"
			. "</script>\n"
			. "<script type=\"text/javascript\" src=\"htmlarea/htmlarea.js\"></script>\n"
			. "<script language=\"javascript\" type=\"text/javascript\">\n"
			. "var editor = null;\n"
			. "function initEditor(){\n"
			. "	var config = new HTMLArea.Config();\n"
			. "	config.pageStyle = \"body { padding:0px;margin:5px;font-size:10pt;font-family:宋体;} p{margin:0}\";\n"            
			. "	editor = new HTMLArea(\"content\", config);\n"
			. "	editor.generate();\n"
			. "	return false;\n"
			. "}\n"
			. "HTMLArea.onload = initEditor;\n"
			. "</script>\n";
		break;
	default: break;	
}

$fanso->display('post.html');
?>