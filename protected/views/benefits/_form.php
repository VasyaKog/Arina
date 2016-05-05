<?php
/* @var $this BenefitsController */
/* @var $model Benefits */
/* @var $form CActiveForm */
?>

<div class="form">

<?php $form=$this->beginWidget(BoosterHelper::FORM, array(
	'id'=>'benefits-form',
	'type' => 'horizontal',
	'htmlOptions' => array('class'=>'well spalO'),
	// Please note: When you enable ajax validation, make sure the corresponding
	// controller action is handling ajax validation correctly.
	// There is a call to performAjaxValidation() commented in generated controller code.
	// See class documentation of CActiveForm for details on this.
	'enableAjaxValidation'=>false,
)); ?>
		<?php echo $form->textFieldRow($model,'title'); ?>
		<?php $this->widget(
			'bootstrap.widgets.TbButton',
			array('buttonType' => 'submit', 'type' => 'primary', 'label' => 'Відправити')
		); ?>
<?php $this->endWidget(); ?>

</div><!-- form -->