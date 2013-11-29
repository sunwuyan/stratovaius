<?php
$this->breadcrumbs=array(
	'Params'=>array('index'),
	$model->id=>array('view','id'=>$model->id),
	'Update',
);

$this->menu=array(
	array('label'=>'List Params', 'url'=>array('index')),
	array('label'=>'Create Params', 'url'=>array('create')),
	array('label'=>'View Params', 'url'=>array('view', 'id'=>$model->id)),
	array('label'=>'Manage Params', 'url'=>array('admin')),
);
?>

<h1>Update Params <?php echo $model->id; ?></h1>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>