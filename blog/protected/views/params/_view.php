<div class="view">

	<b><?php echo CHtml::encode($data->getAttributeLabel('id')); ?>:</b>
	<?php echo CHtml::link(CHtml::encode($data->id), array('view', 'id'=>$data->id)); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('pname')); ?>:</b>
	<?php echo CHtml::encode($data->pname); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('pvalues')); ?>:</b>
	<?php echo CHtml::encode($data->pvalues); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('caseid')); ?>:</b>
	<?php echo CHtml::encode($data->caseid); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('status')); ?>:</b>
	<?php echo CHtml::encode($data->status); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('gmt_create')); ?>:</b>
	<?php echo CHtml::encode($data->gmt_create); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('gmt_modify')); ?>:</b>
	<?php echo CHtml::encode($data->gmt_modify); ?>
	<br />

	<?php /*
	<b><?php echo CHtml::encode($data->getAttributeLabel('api_id')); ?>:</b>
	<?php echo CHtml::encode($data->api_id); ?>
	<br />

	*/ ?>

</div>