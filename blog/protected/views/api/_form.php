<div class="form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'api-form',
	'enableAjaxValidation'=>false,
)); ?>

	<p class="note">Fields with <span class="required">*</span> are required.</p>

	<?php echo $form->errorSummary($model); ?>

	<div class="row">
		<?php echo $form->labelEx($model,'isrun'); ?>
		<?php echo $form->textField($model,'isrun'); ?>
		<?php echo $form->error($model,'isrun'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'times'); ?>
		<?php echo $form->textField($model,'times'); ?>
		<?php echo $form->error($model,'times'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'init'); ?>
		<?php echo $form->textField($model,'init',array('size'=>60,'maxlength'=>254)); ?>
		<?php echo $form->error($model,'init'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'path'); ?>
		<?php echo $form->textField($model,'path',array('size'=>60,'maxlength'=>254)); ?>
		<?php echo $form->error($model,'path'); ?>
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
		<?php echo $form->labelEx($model,'clean'); ?>
		<?php echo $form->textField($model,'clean',array('size'=>60,'maxlength'=>254)); ?>
		<?php echo $form->error($model,'clean'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'caseno'); ?>
		<?php echo $form->textField($model,'caseno',array('size'=>25,'maxlength'=>25)); ?>
		<?php echo $form->error($model,'caseno'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'type'); ?>
		<?php echo $form->textField($model,'type',array('size'=>4,'maxlength'=>4)); ?>
		<?php echo $form->error($model,'type'); ?>
	</div>

	<div class="row buttons">
		<?php echo CHtml::submitButton($model->isNewRecord ? 'Create' : 'Save'); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- form -->