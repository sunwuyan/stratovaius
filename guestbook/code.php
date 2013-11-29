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
require_once('include/function.php');

$im = imagecreate(42, 23);

$backgroundcolor = imagecolorallocate ($im, 255, 255, 255);
$black = imagecolorallocate($im,100,100,100);
$bordercolor = imagecolorallocate($im , 150, 150, 150);

$linenums = mt_rand(10, 32);
for($i=0; $i <= $linenums; $i++) {
	$linecolor = imagecolorallocate($im, mt_rand(0, 255), mt_rand(0, 255), mt_rand(0, 255));
	$linex = mt_rand(0, 48);
	$liney = mt_rand(0, 25);
	imageline($im, $linex, $liney, $linex + mt_rand(0, 4) - 2, $liney + mt_rand(0, 4) - 2, $linecolor);
}

for($i=0; $i <= 64; $i++) {
	$pointcolor = imagecolorallocate($im, mt_rand(50, 255), mt_rand(50, 255), mt_rand(50, 255));
	imagesetpixel($im, mt_rand(0, 40), mt_rand(0, 21), $pointcolor);
}

$string = "";
$arr = range(0,9);

srand((double) microtime()*10000000);
$randcode = array_rand($arr,4);
for($i=0;$i<4;$i++){
	$string .= $arr[$randcode[$i]];
}

setcookie('randcode',encode($string),time()+10*60);

imagerectangle($im, 0, 0, 41, 22, $bordercolor);
imagestring($im,7,4,4,$string,$black);

header('Content-type: image/png');
imagepng($im);
imagedestroy($im);
?>  