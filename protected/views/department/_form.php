<?php
/* @var $form TbActiveForm */
/* @var $this DepartmentController */
/* @var $model Department */

$form = $this->beginWidget('bootstrap.widgets.TbActiveForm', array(
    'id' => 'department-form',
    'enableAjaxValidation' => false,
)); ?>

<?php echo $form->errorSummary($model); ?>

<?php echo $form->textFieldRow($model, 'title', array('class' => 'span4', 'maxlength' => 40)); ?>

<?php echo $form->dropDownListRow($model, 'head_id', Teacher::getList(), array('class' => 'span4')); ?>

<?php $this->renderPartial('//formButtons', array('model' => $model)); ?>

<?php $this->endWidget(); ?>
