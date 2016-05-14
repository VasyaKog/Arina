<?php
/* @var $this MarkController */
/* @var $model Mark */
/* @var $type JournalRecordType*/
/* @var $form CActiveForm */
?>

<div class="form">

<?php $form=$this->beginWidget(BoosterHelper::FORM, array(
	'id'=>'mark-form',
	// Please note: When you enable ajax validation, make sure the corresponding
	// controller action is handling ajax validation correctly.
	// See class documentation of CActiveForm for details on this,
	// you need to use the performAjaxValidation()-method described there.
	'enableAjaxValidation'=>true,
)); ?>

	<?php echo $form->errorSummary($model); ?>

	<div class="row">
		<?php if($type->present)
			 echo $form->toggleButtonRow($model,'present',array('enabledLabel'=>Yii::t('journal','NP'),'disabledLabel'=>Yii::t('base',' ')));else
		?>
	</div>

	<div class="row">
		<?php  echo $form->dropDownListRow(
			$model,
			'system_id',
			EvaluationSystem::getListAll('id','title'),
			array(
				'empty'=>Yii::t('journal','Select EvaluationSystem'),
				'ajax'=>array(
					'type'=>'POST',
					'url'=>$this->createUrl('changeMarkList'),
					'update'=> '#Mark_value_id',
			),
		)
		); ?>
	</div>

	<div class="row">
		<?php if($type->ticket) echo $form->textFieldRow($model,'ticket_numb'); ?>
	</div>

	<div class="row">
		<?php if($type->ticket) echo $form->textFieldRow($model,'retake_ticket_numb'); ?>
	</div>
	<?php if(!isset($model->value_id)) {?>
	<div class="row">
		<?php echo $form->dropDownListRow(
			$model,
			'value_id',
			array(),
				array(
					'empty'=>Yii::t('journal','Select EvaluationSystem'),
					'ajax'=>array(
						'type'=>'POST',
						'url'=>$this->createUrl('changeMarkList'),
						'update'=> '#Mark_retake_value_id',
					),
			)
		); ?>
	</div>
	<? } else { ?>
	<div class="row">
		<?php echo $form->dropDownListRow(
			$model,
			'value_id',
			Evaluation::getListBySystemId($model->system_id),
			array(
				'empty'=>Yii::t('journal','Select EvaluationSystem'),
				'ajax'=>array(
					'type'=>'POST',
					'url'=>$this->createUrl('changeMarkList'),
					'update'=> '#Mark_retake_value_id',
				),
			)
		); ?>
	</div>
	<?}?>
	<?php if(!isset($model->retake_value_id)) {?>
		<div class="row">
			<?php echo $form->dropDownListRow(
				$model,
				'retake_value_id',
				array(),
				array(
					'empty'=>Yii::t('journal','Select EvaluationSystem'),
				)
			); ?>
		</div>
	<? } else { ?>
		<div class="row">
			<?php echo $form->dropDownListRow(
				$model,
				'retake_value_id',
				Evaluation::getListBySystemId($model->system_id),
				array(
					'empty'=>Yii::t('journal','Select EvaluationSystem'),
				)
			); ?>
		</div>
	<?}?>

	<div class="row">
		<?php echo $form->textFieldRow($model,'comment'); ?>
	</div>


<!--
	<div class="row">
		<?php /*echo $form->labelEx($model,'date'); */?>
		<?php /*echo $form->textField($model,'date'); */?>
		<?php /*echo $form->error($model,'date'); */?>
	</div>

	<div class="row">
		<?php /*echo $form->labelEx($model,'retake_date'); */?>
		<?php /*echo $form->textField($model,'retake_date'); */?>
		<?php /*echo $form->error($model,'retake_date'); */?>
	</div>-->


	<div class="row buttons">
		<?php echo CHtml::submitButton($model->isNewRecord ? Yii::t('base','Create') : Yii::t('base','Save')); ?>
	</div>	

<?php $this->endWidget(); ?>

</div><!-- form -->