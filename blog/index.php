<?php
//sqlite3 C:\wamp\www\blog\protected\data\blog.db
date_default_timezone_set('PRC');

function p($var){
	CDebug::dump($var);
}
// change the following paths if necessary
$yii=dirname(__FILE__).'/../yii3/framework/yii.php';
$config=dirname(__FILE__).'/protected/config/main.php';


require_once($yii);
Yii::createWebApplication($config)->run();
// p(Yii::app()->db);












