<div class="form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'params-form',
	'enableAjaxValidation'=>false,
)); ?>

	<p class="note">Fields with <span class="required">*</span> are required.</p>

	<?php echo $form->errorSummary($model); ?>

	<div class="row">
		<?php echo $form->labelEx($model,'pname'); ?>
		<?php echo $form->textField($model,'pname',array('size'=>20,'maxlength'=>20)); ?>
		<?php echo $form->error($model,'pname'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'pvalues'); ?>
		<?php echo $form->textField($model,'pvalues',array('size'=>60,'maxlength'=>100)); ?>
		<?php echo $form->error($model,'pvalues'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'caseid'); ?>
		<?php echo $form->textField($model,'caseid'); ?>
		<?php echo $form->error($model,'caseid'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'status'); ?>
		<?php echo $form->textField($model,'status'); ?>
		<?php echo $form->error($model,'status'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'gmt_create'); ?>
		<?php echo $form->textField($model,'gmt_create'); ?>
		<?php echo $form->error($model,'gmt_create'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'gmt_modify'); ?>
		<?php echo $form->textField($model,'gmt_modify'); ?>
		<?php echo $form->error($model,'gmt_modify'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'api_id'); ?>
		<?php echo $form->textField($model,'api_id'); ?>
		<?php echo $form->error($model,'api_id'); ?>
	</div>

	<div class="row buttons">
		<?php echo CHtml::submitButton($model->isNewRecord ? 'Create' : 'Save'); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- form -->