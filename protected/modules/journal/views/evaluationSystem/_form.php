<?php
/* @var $this EvaluationSystemController */
/* @var $model EvaluationSystem */
/* @var $form CActiveForm */
?>

<div class="form">

<?php $form=$this->beginWidget(BoosterHelper::FORM, array(
	'id'=>'evaluation-system-form',
	// Please note: When you enable ajax validation, make sure the corresponding
	// controller action is handling ajax validation correctly.
	// There is a call to performAjaxValidation() commented in generated controller code.
	// See class documentation of CActiveForm for details on this.
	'enableAjaxValidation'=>false,
)); ?>



	<?php echo $form->errorSummary($model); ?>

	<div class="row">
		<?php echo $form->textFieldRow($model,'title',array('size'=>50,'maxlength'=>50)); ?>
	</div>

	<div class="row buttons">
		<?php $this->widget(
			'bootstrap.widgets.TbButton',
			array('buttonType' => 'submit', 'type' => 'primary', 'label' => $model->isNewRecord ? Yii::t('terms','Create') : Yii::t('terms','Save'))); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- form -->