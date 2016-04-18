<?php
/* @var $this JournalRecordController */
/* @var $model JournalRecord */
/* @var $form CActiveForm */
?>

<div class="form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'journal-record-form',
	// Please note: When you enable ajax validation, make sure the corresponding
	// controller action is handling ajax validation correctly.
	// There is a call to performAjaxValidation() commented in generated controller code.
	// See class documentation of CActiveForm for details on this.
	'enableAjaxValidation'=>false,
)); ?>

	<p class="note">Fields with <span class="required">*</span> are required.</p>

	<?php echo $form->errorSummary($model); ?>

	<div class="row">
		<?php echo $form->labelEx($model,'type_id'); ?>
		<?php echo $form->textField($model,'type_id'); ?>
		<?php echo $form->error($model,'type_id'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'date'); ?>
		<?php echo $form->textField($model,'date'); ?>
		<?php echo $form->error($model,'date'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'description'); ?>
		<?php echo $form->textField($model,'description',array('size'=>60,'maxlength'=>255)); ?>
		<?php echo $form->error($model,'description'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'home_work'); ?>
		<?php echo $form->textField($model,'home_work',array('size'=>60,'maxlength'=>255)); ?>
		<?php echo $form->error($model,'home_work'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'load_id'); ?>
		<?php echo $form->textField($model,'load_id'); ?>
		<?php echo $form->error($model,'load_id'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'teacher_id'); ?>
		<?php echo $form->textField($model,'teacher_id',array('size'=>10,'maxlength'=>10)); ?>
		<?php echo $form->error($model,'teacher_id'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'n_pp'); ?>
		<?php echo $form->textField($model,'n_pp',array('size'=>10,'maxlength'=>10)); ?>
		<?php echo $form->error($model,'n_pp'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'numer_in_day'); ?>
		<?php echo $form->textField($model,'numer_in_day'); ?>
		<?php echo $form->error($model,'numer_in_day'); ?>
	</div>

	<div class="row buttons">
		<?php echo CHtml::submitButton($model->isNewRecord ? 'Create' : 'Save'); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- form -->