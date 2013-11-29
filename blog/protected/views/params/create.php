<?php
$this->breadcrumbs=array(
	'Params'=>array('index'),
	'Create',
);

$this->menu=array(
	array('label'=>'List Params', 'url'=>array('index')),
	array('label'=>'Manage Params', 'url'=>array('admin')),
);
?>

<h1>Create Params</h1>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>