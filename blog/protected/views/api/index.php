<?php
$this->breadcrumbs=array(
	'Apis',
);

$this->menu=array(
	array('label'=>'Create Api', 'url'=>array('create')),
	array('label'=>'Manage Api', 'url'=>array('admin')),
);
?>

<h1>Apis</h1>

<?php $this->widget('zii.widgets.CListView', array(
	'dataProvider'=>$dataProvider,
	'itemView'=>'_view',
)); ?>
