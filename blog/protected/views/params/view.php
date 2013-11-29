<?php
$this->breadcrumbs=array(
	'Params'=>array('index'),
	$model->id,
);

$this->menu=array(
	array('label'=>'List Params', 'url'=>array('index')),
	array('label'=>'Create Params', 'url'=>array('create')),
	array('label'=>'Update Params', 'url'=>array('update', 'id'=>$model->id)),
	array('label'=>'Delete Params', 'url'=>'#', 'linkOptions'=>array('submit'=>array('delete','id'=>$model->id),'confirm'=>'Are you sure you want to delete this item?')),
	array('label'=>'Manage Params', 'url'=>array('admin')),
);
?>

<h1>View Params #<?php echo $model->id; ?></h1>

<?php $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		'id',
		'pname',
		'pvalues',
		'caseid',
		'status',
		'gmt_create',
		'gmt_modify',
		'api_id',
	),
)); ?>
