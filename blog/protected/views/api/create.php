<?php
$this->breadcrumbs=array(
	'Apis'=>array('index'),
	'Create',
);

$this->menu=array(
	array('label'=>'List Api', 'url'=>array('index')),
	array('label'=>'Manage Api', 'url'=>array('admin')),
);
?>

<h1>Create Api</h1>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>