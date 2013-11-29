<?php
$this->breadcrumbs=array(
	'Params',
);

$this->menu=array(
	array('label'=>'Create Params', 'url'=>array('create')),
	array('label'=>'Manage Params', 'url'=>array('admin')),
);
?>

<h1>Params</h1>

<?php $this->widget('zii.widgets.CListView', array(
	'dataProvider'=>$dataProvider,
	'itemView'=>'_view',
)); ?>
