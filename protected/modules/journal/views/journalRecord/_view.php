<?php
/* @var $this JournalRecordController */
/* @var $data JournalRecord */
?>

<div class="view">

	<b><?php echo CHtml::encode($data->getAttributeLabel('id')); ?>:</b>
	<?php echo CHtml::link(CHtml::encode($data->id), array('view', 'id'=>$data->id)); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('type_id')); ?>:</b>
	<?php echo CHtml::encode($data->type_id); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('date')); ?>:</b>
	<?php echo CHtml::encode($data->date); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('description')); ?>:</b>
	<?php echo CHtml::encode($data->description); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('home_work')); ?>:</b>
	<?php echo CHtml::encode($data->home_work); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('load_id')); ?>:</b>
	<?php echo CHtml::encode($data->load_id); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('teacher_id')); ?>:</b>
	<?php echo CHtml::encode($data->teacher_id); ?>
	<br />

	<?php /*
	<b><?php echo CHtml::encode($data->getAttributeLabel('n_pp')); ?>:</b>
	<?php echo CHtml::encode($data->n_pp); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('numer_in_day')); ?>:</b>
	<?php echo CHtml::encode($data->numer_in_day); ?>
	<br />

	*/ ?>

</div>