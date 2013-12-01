<?php
//sqlite3 C:\wamp\www\blog\protected\data\blog.db
date_default_timezone_set('PRC');
//echo "hello world!";

function p($var){
	CDebug::dump($var);
}
// change the following paths if necessary
$yii=dirname(__FILE__).'/../yii/framework/yii.php';
$config=dirname(__FILE__).'/protected/config/main.php';


require_once($yii);
Yii::createWebApplication($config)->run();

#echo CHtml::checkBoxList('mylist[goods]','first',array('first'=>'a>e','multi','c','d'),array());

#p(Yii::app()->cache);












