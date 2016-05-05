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

	<?php echo $form->errorSummary($model); ?>
		<?php echo $form->textFieldRow($model,'title',array('size'=>15,'maxlength'=>15)); ?>
		<?php echo $form->toggleButtonRow($model, 'description', array('enabledLabel'=>Yii::t('base','Yes'),'disabledLabel'=>Yii::t('base','No'),));?>
	    <?php echo $form->toggleButtonRow($model, 'homework', array('enabledLabel'=>Yii::t('base','Yes'),'disabledLabel'=>Yii::t('base','No'),));?>
		<?php echo $form->toggleButtonRow($model, 'present', array('enabledLabel'=>Yii::t('base','Yes'),'disabledLabel'=>Yii::t('base','No'),));?>
		<?php echo $form->toggleButtonRow($model, 'date', array('enabledLabel'=>Yii::t('base','Yes'),'disabledLabel'=>Yii::t('base','No'),));?>
		<?php echo $form->toggleButtonRow($model, 'n_pp', array('enabledLabel'=>Yii::t('base','Yes'),'disabledLabel'=>Yii::t('base','No'),));?>
		<?php echo $form->toggleButtonRow($model, 'ticket', array('enabledLabel'=>Yii::t('base','Yes'),'disabledLabel'=>Yii::t('base','No'),));?>
		<?php echo $form->toggleButtonRow($model, 'reports', array('enabledLabel'=>Yii::t('base','Yes'),'disabledLabel'=>Yii::t('base','No'),));?>
		<?php echo $form->textFieldRow($model,'title_report',array('size'=>20,'maxlength'=>20)); ?>
	<div class="row buttons">
		<?php $this->widget(
			'bootstrap.widgets.TbButton',
			array('buttonType' => 'submit', 'type' => 'primary', 'label' => $model->isNewRecord ? Yii::t('terms','Create') : Yii::t('terms','Save'))); ?>
	</div>
	</div>

<?php $this->endWidget(); ?>

</div><!-- form -->