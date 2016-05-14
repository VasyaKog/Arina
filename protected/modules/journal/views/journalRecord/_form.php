<?php
/* @var $this JournalRecordController */
/* @var $model JournalRecord */
/* @var $form CActiveForm */
?>

<div class="form">

<?php $form=$this->beginWidget(BoosterHelper::FORM, array(
	'id'=>'journal-record-form',
	// Please note: When you enable ajax validation, make sure the corresponding
	// controller action is handling ajax validation correctly.
	// There is a call to performAjaxValidation() commented in generated controller code.
	// See class documentation of CActiveForm for details on this.
	'enableAjaxValidation'=>false,
)); ?>

	<?php echo $form->errorSummary($model); ?>

	<div class="row">
		<?php echo $form->dropDownListRow(
			$model,
			'type_id',
			JournalRecordType::getListAll('id','title'),
			array(
				'empty'=>Yii::t('journal','Select')
			)); ?>
	</div>

	<div class="row">
		<?php echo $form->textFieldRow($model,'description',array('size'=>60,'maxlength'=>255)); ?>
	</div>

	<div class="row">
		<?php echo $form->textFieldRow($model,'home_work',array('size'=>60,'maxlength'=>255)); ?>
	</div>

	<div class="row">
		<?php echo $form->textFieldRow($model,'hours',array('size'=>10,'maxlength'=>10)); ?>
	</div>

	<div class="row">
		<?php echo $form->dropDownListRow($model,
			'numer_in_day',array('1'=>'1','2'=>'2','3'=>'3','4'=>'4'),array(
			'empty'=>Yii::t('journal','Select'),
		)); ?>
	</div>

	<div class="row buttons">
		<?php echo CHtml::submitButton($model->isNewRecord ? Yii::t('base','Create') : Yii::t('base','Save')); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- form -->