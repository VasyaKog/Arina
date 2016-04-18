<?php
/* @var $this JournalRecordTypeController */
/* @var $model JournalRecordType */
/* @var $form CActiveForm */
?>

<div class="form">

<?php $form=$this->beginWidget(BoosterHelper::FORM, array(
	'id'=>'journal-record-type-form',
	'type'=>'horizontal',
	// Please note: When you enable ajax validation, make sure the corresponding
	// controller action is handling ajax validation correctly.
	// There is a call to performAjaxValidation() commented in generated controller code.
	// See class documentation of CActiveForm for details on this.
	'enableAjaxValidation'=>false,
)); ?>

	<p class="note">Fields with <span class="required">*</span> are required.</p>

	<?php echo $form->errorSummary($model); ?>
		<?php echo $form->textFieldRow($model,'title',array('size'=>15,'maxlength'=>15)); ?>
		<?php echo $form->checkBoxRow($model,'description'); ?>
		<?php echo $form->checkBoxRow($model,'homework'); ?>
		<?php echo $form->checkBoxRow($model,'present'); ?>
		<?php echo $form->checkBoxRow($model,'date'); ?>
		<?php echo $form->checkBoxRow($model,'n_pp'); ?>
		<?php echo $form->checkBoxRow($model,'ticket'); ?>
		<?php echo $form->textFieldRow($model,'hours'); ?>
		<?php echo $form->checkBoxRow($model,'reports'); ?>
		<?php echo $form->textFieldRow($model,'title_report',array('size'=>20,'maxlength'=>20)); ?>
	<div class="row buttons">
		<?php echo CHtml::submitButton($model->isNewRecord ? 'Create' : 'Save'); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- form -->