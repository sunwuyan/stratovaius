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

function sqlEncode(&$value,$key='',$allowHtml=array()){
	if(is_array($value))
		array_walk($value,'sqlEncode');
	else {
		if(!get_magic_quotes_gpc())
			$value = addslashes($value);
	}
}

function sqlDecode(&$value,$key='',$allowHtml=array()){
	if(is_array($value))
		array_walk($value,'sqlDecode');
	else{	
		if(get_magic_quotes_runtime())
			$vue = stripslashes($value);
		if(!in_array($key,$allowHtml))
			$value = textFormat($value);
		else
			$value = deUnsafeTag($value);
	}
}

function textFormat($text){
	$text = htmlspecialchars($text);
	$text = preg_replace("/[ ]/",'&nbsp;',$text);
	$text = nl2br($text);
	return $text;
}

function deUnsafeTag($content){
	$content = preg_replace("/<script(.*?)>(.*?)<\/script>/ies","htmlspecialchars('\\0')",$content);
	$content = strip_tags($content,'<p><strong><em><u><strike><a><img><font><br><ul><li><b>');
	/*$pattern = array(
		0 => '/<(.+?)class\s*=\s*(\'(.+?)\'|"(.+?)")?(.*?)>/is',
		1 => '/<(.+?)style\s*=\s*(\'(.+?)\'|"(.+?)")?(.*?)>/is',
		2 => '/<(.+?)on[a-zA-z]+\s*=\s*(\'(.+?)\'|"(.+?)")?(.*?)>/is',
		3 => '/<img(.+?)\/?>/is'
	);
	$replacement = array(
		0 => '<\\1\\5>',
		1 => '<\\1\\5>',
		2 => '<\\1\\5>',
		3 => '<img\\1 onload="if(this.width>screen.width*0.55) {this.resized=true; this.width=screen.width*0.55;}">'
	);*/
	$pattern = array(
		0 => '/<(.+?)(class|style|id)\s*=\s*(\'(.+?)\'|"(.+?)")?(.*?)>/is',
		1 => '/<(.+?)on[a-zA-z]+\s*=\s*(\'(.+?)\'|"(.+?)")?(.*?)>/is',
		2 => '/<img(.+?)\/?>/is'
	);
	$replacement = array(
		0 => '<\\1\\6>',
		1 => '<\\1\\5>',
		2 => '<img\\1 onload="if(this.width>screen.width*0.55) {this.resized=true; this.width=screen.width*0.55;}">'
	);
	return preg_replace($pattern,$replacement,$content);
}

function deIp($ip){
	return preg_replace('/\.\d{1,3}$/','.*',$ip);
}

function setting(){
	global $db,$mydbpre;
	
	$types = '';
	if(func_num_args()){
		$types = " where ";
		$argus = func_get_args();
		foreach ($argus as $key => $value){
			$argus[$key] = "type = '" . $value . "'";
		}
		$types .= implode(" or ",$argus); 
	}	
	$sql = "select * from {$mydbpre}setting{$types}";
	$query = $db->query($sql);
	while($row = $db->fetch_array($query)){
		$arr[$row['type']][$row['keyword']]['value'] = $row['val'];
		if(!is_null($row['expansion']))
			$arr[$row['type']][$row['keyword']]['expansion'] = $row['expansion'];
		if(!is_null($row['expansion2']))
			$arr[$row['type']][$row['keyword']]['expansion2'] = $row['expansion2'];	
		if($row['keyword'] == 'name') 
			$arr[$row['type']]['title']['value'] = $row['val'].' - Powered by PHPfans';
	}
	return $arr;
}

function navigation($type){
	global $vartem;	
	$navigate = array();
	foreach ($vartem['setting'][$type] as $key => $val){
		$navigate[$val['expansion2']] = htmlTag('a','href="'.$val['value'].'" target="'.$val['expansion'].'"',$key);
	}
	ksort($navigate);
	
	return implode(' ',$navigate);
}

function loginBar($islogin){
	global $vartem;	
	return ' '.(!$islogin ? 
		htmlTag('a','href="login.php"',$vartem['lang']['login']) :
		htmlTag('a','href="admincp.php" target="_blank"',$vartem['lang']['admin'],"\n")
		. htmlTag('a','href="login.php?logout=true"',$vartem['lang']['logout'])
	);
}

function copyRight(){
	global $vartem,$db,$start;	
	return htmlTag('font','id="copy"','Powered by ' 
		. htmlTag('a','href="http://www.phpfans.net" target="_blank"','PHPfans',' v')
		. VERSION .' &copy; 2006-2008 ') . htmlTag('br');
}

function queryTime(){
	global $vartem,$db,$start;	
	return 'Query: '.$db->querynum.' nums Time: '.sprintf('%.6f',gettime()-$start)
		. (($vartem['setting']['basic']['icp']['value'] != '') ? 
		' '.htmlTag('a','href="http://www.miibeian.gov.cn/" target="_blank"',$vartem['setting']['basic']['icp']['value']) 
		: ''
		);
}

function checkContent($content){
	global $db,$mydbpre,$mearr;
	$arrword = $arrip = array();
	$sql = "select * from {$mydbpre}ban where b_type != 0";
	$query = $db->query($sql);
	while($row = $db->fetch_array($query)){
		if($row['b_type'] == 1) 
			$arrword[] = $row['b_val'];
		elseif($row['b_type'] == 2) 
			$arrip[] = $row['b_reval'];
	}
	foreach($arrip as $value){
		$value = preg_quote($value,'/');
		$value = str_replace('\*','\d{1,3}',$value);
		if(preg_match('/^('.$value.')$/',$_SERVER['REMOTE_ADDR']))
			showmessage($mearr['m8'],'index.php');
	}
	foreach($arrword as $val){
		$val = preg_quote($val,'/');
		if(preg_match('/'.$val.'/',$content)){
			showmessage($mearr['m7'],'index.php');
		}
	}
}

function replaceWords($content){
	global $db,$mydbpre;
	$sql = "select * from {$mydbpre}ban where b_type = 0";
	$query = $db->query($sql);
	while($row = $db->fetch_array($query)){
		$content = str_replace($row['b_val'],$row['b_reval'],$content);
	}
	return $content;
}

function showadmin($message,$referer=''){
	$str = '';
	if($referer != '') 
		$str .= '<meta http-equiv="refresh" content="2;URL='.$referer.'" />';
	$str .= '
  <table width="45%" align="center" cellpadding="0" cellspacing="1" style="border:1px solid #eeeeee; margin-top:50px;">
  <tr bgcolor="#CCCCCC">
    <td height="25" style="padding-left:10px;">信息提示</td>
  </tr>
  <tr align="center">
    <td style="padding:10px 0 10px 0">'.$message;
	if($referer != '') $str .= '<br />
  <br />
  <a href="'.$referer.'">如果你的页面没有跳转,请点这里</a>';
	$str .= '
  </td>
  </tr>
  </table>';
	echo $str;
	exit;
}

function showmessage($message,$referer=''){
	global $vartem, $fanso;
	$vartem['msg'] = $message;
	$vartem['refererlink'] = '';
	$vartem['metaredirect'] = '';
	if($referer != ''){
		$vartem['refererlink'] = htmlTag('a','href="'.$referer.'"',$vartem['lang']['links']);
		$vartem['metaredirect'] = htmlTag('meta','http-equiv="Refresh" content="2;URL='.$referer.'"');
	}
	$fanso->display('showmessage.html');
	exit;
}

function gettime(){
	$timeArr = explode(" ",microtime());
	return $timeArr[1] + $timeArr[0];
}

function checklogin(){
	if(!isset($_COOKIE['islogin']) || $_COOKIE['islogin'] != 1){
		return false;
	}
	else {
		setcookie('islogin',1,time()+60*20);
		return true;
	}
}

function checkAdminLogin($islogin, $show=true){
	global $mearr;
	if(!$islogin){
		$referer = "login.php?referer=".urlencode($_SERVER['REQUEST_URI']);
		if($show)
			showmessage($mearr['m26'],$referer);
		else {
			header("location:".$referer);
			exit;
		}		
	}
}

function encode($str) { 
	$encode_key = '1234567890'; 
	$decode_key = '2468021359'; 
	if (strlen($str) == 0) return  ''; 
	$enstr = '';
	for($i=0;$i<strlen($str);$i++){ 
		for($j=0;$j<strlen($encode_key);$j++){ 
			if($str[$i] == $encode_key [$j]){ 
				$enstr .=  $decode_key[$j]; 
				break; 
			} 
		} 
	} 
	return $enstr; 
} 


function decode($str){ 
	$encode_key = '1234567890'; 
	$decode_key = '2468021359'; 
	if(strlen($str) == 0) return  ''; 
	$destr = '';
	for($i=0;$i<strlen($str);$i++){ 
		for($j=0;$j<strlen($decode_key);$j++){ 
			if($str[$i] == $decode_key [$j]){ 
				$enstr .=  $encode_key[$j]; 
				break; 
			} 
		} 
	} 
	return $destr; 
} 

function checkPage(){
	if(empty($_GET['page'])) 
		return 1;
	elseif(intval($_GET['page']) < 1) 
		return 1;
	else 
		return intval($_GET['page']);
}

function referer($encode=false){
	$referer = './index.php';
	if(!empty($_REQUEST['referer'])){
		$referer = $_REQUEST['referer'];
	}
	elseif(!empty($_SERVER['HTTP_REFERER'])){		
		$referer = $_SERVER['HTTP_REFERER'];
	}

	if($encode)
		$referer = urlencode($referer);
			
	return $referer;
}

function htmlTag($tag,$attr='',$content='',$addition=''){
	if($content == '')
		return '<'.$tag.(($attr=='')?'':' '.$attr).' />'.$addition;
	else 
		return '<'.$tag.(($attr=='')?'':' '.$attr).'>'.$content.'</'.$tag.'>'.$addition;
}

function writeFile($file_url,$file_str){
	if(file_exists($file_url) && !is_writable($file_url))
		return false;
	if(!($fp = fopen($file_url,'w')))
		return false;
	if(!(fwrite($fp,$file_str)))
		return false;
	fclose($fp);
	
	return true;
}

function read_file($file_url){
	if(!file_exists($file_url) || !is_readable($file_url))
		return false;
	if(function_exists('file_get_contents'))
		return file_get_contents($file_url);
	else{
		$fp = fopen($file_url, "r");
		$contents = fread($fp, filesize ($file_url));
		fclose($fp); 
		return $contents;
	}
}

function is_writable_file($filename,$chmod=true){
	if(!file_exists($filename)){
		if($fp = @fopen($filename, 'w')) 
			@fclose($fp);
		else 
			return false;
	}
	if(is_writable($filename))
		return true;
	elseif($chmod){
		@chmod($filename,0777);
		return is_writable_file($filename,false);
	}
	else 
		return false;
}

function is_writable_dir($dir,$chmod=true) {
	if(!is_dir($dir)) {
		@mkdir($dir, 0777);
	}
	if(is_dir($dir)) {
		if($fp = @fopen("$dir/test.txt", 'w')) {
			@fclose($fp);
			@unlink("$dir/test.txt");
			return true;
		} 
		elseif($chmod) {
			@chmod($dir,0777);
			return is_writeable_dir($dir,false);
		}
		else 
			return false;
	}
}

function uniqueFileName($file){
	if(!file_exists($file))
		return $file;
	else{
		$fileName = basename($file);
		$FileDir = dirname($file);
		$fileNameHead = $fileName;
		$fileNameTail = '';
		$fileNameArr = explode('.',$fileName);
		if(count($fileNameArr) > 1){
			$fileNameTail =  '.'.array_pop($fileNameArr);
			$fileNameHead = implode('.',$fileNameArr);
		}
		if(preg_match('/\((\d+)\)$/',$fileNameHead,$numArr)){
			$fileNameHead = preg_replace('/\((\d+)\)$/','('.($numArr[1]+1).')',$fileNameHead);
			return uniqueFileName($FileDir.'/'.$fileNameHead.$fileNameTail);
		}
		else 
			return uniqueFileName($FileDir.'/'.$fileNameHead.'(1)'.$fileNameTail);
	}		 
}

function cacheAvatars($avatarsDir,$avatarsIndex=null){
	if(!preg_match('/\/$/',$avatarsDir))
		$avatarsDir .= '/';	
	
	$mydir = dir($avatarsDir);
	while($file=$mydir->read()){
		if($file != "." && $file != ".." && !is_dir($avatarsDir.$file) && @getimagesize($avatarsDir.$file)){
			$fileName = $file;
			$fileNameArr = explode('.',$fileName);
			if(count($fileNameArr) > 1){
				array_pop($fileNameArr);
				$fileName = implode('.',$fileNameArr);
			}
			$avatarsArr[] = array('name' => $fileName, 'path' => $avatarsDir.$file);
		}
	}
	$mydir->close();
	
	$avArrStr = "<?php\n\$avatarsArr = ".var_export($avatarsArr,true).";\n"
		. "\$avatarsIndex = "
		. (is_null($avatarsIndex) ? "null" : "'{$avatarsIndex}'").";\n"
		. "?>";
	writeFile('cache/avatars.cache.php',$avArrStr);
	
	return $avatarsArr;
}
	
function deleteDir($url){
	if(is_file($url))
		unlink($url);
	elseif (is_dir($url)){
		!preg_match('/(\/|\\\)$/',$url) ? $url .= '/' : '';
		$dir = dir($url);
		while (false !== ($file = $dir->read())) {			
			if($file != '.' && $file != '..'){
				if(is_file($url.$file))
					unlink($url.$file);
				elseif(is_dir($url.$file))
					deleteDir($url . $file);
			}
		}
		$dir->close();
		rmdir($url);
	}
}

function messageList($row){
	global $vartem,$islogin;
	
	$allowHtml = array();
	if(in_array($row['p_editor'],array(1,2))){
		$allowHtml[] = 'p_content';
	}
	array_walk($row, 'sqlDecode', $allowHtml);
	
	$left = htmlTag('img','src="'.$row['p_image'].'"').htmlTag('br')
		. $vartem['lang']['nickname'].':【'.$row['p_name'].'】';
	
	$right_top = htmlTag('span','class="span"',
		(($row['p_rank'] == 1) ? 
			htmlTag('img','src="images/up.gif"',' ')
			. htmlTag('font',' color="#FF0000"',$vartem['lang']['up'],' &nbsp;')
			: ''
		)			
		. $vartem['lang']['which'] .' '
		. htmlTag('font', 'class="floornum"',$row['p_id'],' ')
		. $vartem['lang']['floor']
	)
	. $vartem['lang']['submittime'] .': ' . date('Y-m-d H:i:s',$row['p_date']);
	
	$right_body = (($row['p_title'] != '') ? 
		htmlTag('b','',$row['p_title']).htmlTag('br') 
		: ''
	)
	. $row['p_content']
	. (($row['r_content'] != '') ? 
		htmlTag('div','class="reply"',
			htmlTag('font','class="areply"',$vartem['lang']['adminreply'].':'). htmlTag('br')
			. htmlTag('div','style="padding:0 15px 0 15px;"',$row['r_content'])
			. htmlTag('div','class="bottom"',
				htmlTag('b','',$vartem['lang']['replyer'].':'). $row['r_rname'] . ' '
				. htmlTag('b','',$vartem['lang']['time'].':'). $row['r_time']
			)
			. htmlTag('div','class="autohight"',' ')
		)
		: ''
	)
	. htmlTag('div','class="autohight"',' ');

	$right_bottom = htmlTag('span','class="span"',
		htmlTag('a','href="post.php?edit=true&p_id='.$row['p_id'].'"',
			htmlTag('img','src="images/edit.gif"').$vartem['lang']['edit']
		)
		. htmlTag('a','href="manage.php?action=up&p_id='.$row['p_id'].'"',
			htmlTag('img','src="images/up.gif"').$vartem['lang']['up']
		)
		. htmlTag('a','href="manage.php?action=re&p_id='.$row['p_id'].'"',
			htmlTag('img','src="images/reply.gif"').$vartem['lang']['reply']
		)
		. htmlTag('a','href="manage.php?action=del&p_id='.$row['p_id'].'"',
			htmlTag('img','src="images/delete.gif"').$vartem['lang']['delete']
		)
	) 
	. (($row['p_homepage'] != '' && $row['p_homepage'] != 'http://') ? 
		htmlTag('img','src="images/home.gif"')
		. htmlTag('a','href="'.$row['p_homepage'].'" target="_blank"',$vartem['lang']['homepage'])
		: ''
	)
	. (($row['p_email'] != '') ? 
		htmlTag('img','src="images/mail.gif"')
		. htmlTag('a','href="mailto:'.$row['p_email'].'"',$vartem['lang']['email'])
		: ''
	)
	. (($row['p_qq'] != '') ? 
		htmlTag('img','src="images/qq.gif"')
		. $row['p_qq']
		: ''
	)
	. htmlTag('img','src="images/ip.gif"')
	. ($islogin ? $row['p_ip'] : deIp($row['p_ip']));
	
	return array(
		'left' => $left,
		'right_top' => $right_top,
		'right_body' => $right_body,
		'right_bottom' => $right_bottom
	);
}
?>