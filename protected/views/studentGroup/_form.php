<?php
/* @var $this StudentGroupController */
/* @var $model StudentGroup */
/* @var $form CActiveForm */
?>

<div class="form">


<?php $form=$this->beginWidget(BoosterHelper::FORM, array(
	'id'=>'student-group-form',
	'type'=>'horizontal',
	'htmlOptions' => array('class'=>'well spal0'),
	// Please note: When you enable ajax validation, make sure the corresponding
	// controller action is handling ajax validation correctly.
	// There is a call to performAjaxValidation() commented in generated controller code.
	// See class documentation of CActiveForm for details on this.
	'enableAjaxValidation'=>true,
)); ?>

	<p class="note">Fields with <span class="required">*</span> are required.</p>

	<?php echo $form->errorSummary($model); ?>

	<div class="form-actions">
		<?php $form->label($model,'date');?>
		<div class="row">

		<?php
		$language = Yii::app()->getLanguage();
			$form->widget('system.zii.widgets.jui.CJuiDatePicker', array(
			'name' => 'date',
			'model' => $model,
			'attribute' => 'date',
			'language' => $language,
			'options' => array(
				'name'=>'date',
				'showAnim' => 'fold',
				'dateFormat'=> 'yy-mm-dd',
				'showButtonPanel' => true,

			),
			'htmlOptions' => array(
				'style' => 'margin-left:30px; height:20px;'
			),
		));?>
		<?php echo $form->error($model,'date'); ?>
			</div>
	</div>

	<div class="row">
		<?php echo $form->dropDownListRow($model, 'type', array(0 => Yii::t('terms','exclude'), 1 => Yii::t('terms','included'),), array('empty' => Yii::t('terms', 'Select type')));?>
	</div>

	<div class="row">
		<?php echo $form->dropDownList('group_id','',Group::getTreeList(),
			array(
				'class'=>'span5',
				'empty'=>Yii::t('group',Yii::t('studentGroup','Select group')),
				'ajax'=>array(
					'type'=>'GET',
					'url'=>CController::createUrl(
						'/group/listByCriteria',
						array('id')
					)
				)

		),
		));?>
	</div>

	<div class="row">
		<?php echo $form->dropDownListRow($model,'student_id',Student::getList(),array('empty'=>Yii::t('student',Yii::t('studentGroup','Select student'))));?>
	</div>

	<div class="row">
		<?php echo $form->textFieldRow($model,'comment',array('size'=>60,'maxlength'=>255)); ?>
	</div>
	<div class="form-actions">
		<?php $this->widget(
			'bootstrap.widgets.TbButton',
			array('buttonType' => 'submit', 'type' => 'primary', 'label' => $model->isNewRecord ? Yii::t('terms','Execute') : Yii::t('terms','Save'))); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- form -->