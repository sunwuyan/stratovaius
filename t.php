<?php
require_once 'yii.php';
function p($var){
	CDebug::dump($var);
}
class Post extends CActveRecord 
{
    public $title='please enter a title';
    public static function model($className=__CLSSS__)
    {
        return parent::model($className);
    }
    public function tableName()
    {
        return "{{post}}";
    } 

    public function primaryKey()
    {
        return 'id';
    }

    
}
