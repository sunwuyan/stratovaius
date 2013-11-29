<div class="wide form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'action'=>Yii::app()->createUrl($this->route),
	'method'=>'get',
)); ?>

	<div class="row">
		<?php echo $form->label($model,'id'); ?>
		<?php echo $form->textField($model,'id'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'pname'); ?>
		<?php echo $form->textField($model,'pname',array('size'=>20,'maxlength'=>20)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'pvalues'); ?>
		<?php echo $form->textField($model,'pvalues',array('size'=>60,'maxlength'=>100)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'caseid'); ?>
		<?php echo $form->textField($model,'caseid'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'status'); ?>
		<?php echo $form->textField($model,'status'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'gmt_create'); ?>
		<?php echo $form->textField($model,'gmt_create'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'gmt_modify'); ?>
		<?php echo $form->textField($model,'gmt_modify'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'api_id'); ?>
		<?php echo $form->textField($model,'api_id'); ?>
	</div>

	<div class="row buttons">
		<?php echo CHtml::submitButton('Search'); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- search-form -->