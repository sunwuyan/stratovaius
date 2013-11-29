<?php
$this->breadcrumbs=array(
	'Apis'=>array('index'),
	$model->id,
);

$this->menu=array(
	array('label'=>'List Api', 'url'=>array('index')),
	array('label'=>'Create Api', 'url'=>array('create')),
	array('label'=>'Update Api', 'url'=>array('update', 'id'=>$model->id)),
	array('label'=>'Delete Api', 'url'=>'#', 'linkOptions'=>array('submit'=>array('delete','id'=>$model->id),'confirm'=>'Are you sure you want to delete this item?')),
	array('label'=>'Manage Api', 'url'=>array('admin')),
);
?>

<h1>View Api #<?php echo $model->id; ?></h1>

<?php $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		'id',
		'isrun',
		'times',
		'init',
		'path',
		'status',
		'gmt_create',
		'gmt_modify',
		'clean',
		'caseno',
		'type',
	),
)); ?>
