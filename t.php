<?php
require_once './yii/framework/utils/CDebug.php';
date_default_timezone_set('PRC');
function p($var){
	CDebug::dump($var);
}
p(strftime("%Y-%m-%d %H:%M:%S"),1393836303371);
