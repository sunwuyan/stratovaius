<?php
$this->breadcrumbs=array(
	'Apis'=>array('index'),
	$model->id=>array('view','id'=>$model->id),
	'Update',
);

$this->menu=array(
	array('label'=>'List Api', 'url'=>array('index')),
	array('label'=>'Create Api', 'url'=>array('create')),
	array('label'=>'View Api', 'url'=>array('view', 'id'=>$model->id)),
	array('label'=>'Manage Api', 'url'=>array('admin')),
);
?>

<h1>Update Api <?php echo $model->id; ?></h1>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>