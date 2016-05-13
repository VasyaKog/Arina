<?php
/* @var $this EvaluationController */
/* @var $model Evaluation */
/* @var $form CActiveForm */
?>

<div class="form">

<?php $form=$this->beginWidget(BoosterHelper::FORM, array(
	'id'=>'evaluation-form',
	'type'=>'horizontal',
	// Please note: When you enable ajax validation, make sure the corresponding
	// controller action is handling ajax validation correctly.
	// There is a call to performAjaxValidation() commented in generated controller code.
	// See class documentation of CActiveForm for details on this.
	'enableAjaxValidation'=>false,
)); ?>

	<?php echo $form->errorSummary($model); ?>
		<?php echo $form->textFieldRow($model,Yii::t('Evaluation','title',array('size'=>11,'maxlength'=>11))); ?>
		<?php echo $form->dropDownListRow($model,'system_id',EvaluationSystem::getListAll('id', 'title'),array('size'=>0,'maxlength'=>11)); ?>
	<div class="row buttons">
		<?php $this->widget(
			'bootstrap.widgets.TbButton',
			array(
				'buttonType' => 'submit', 
				'type' => 'primary', 
				'label' => $model->isNewRecord ? Yii::t('terms','Create') : Yii::t('terms','Save'))); ?>

	</div>

<?php $this->endWidget(); ?>

</div><!-- form -->