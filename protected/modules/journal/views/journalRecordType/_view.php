<?php
/* @var $this JournalRecordTypeController */
/* @var $data JournalRecordType */
?>

<div class="view">

	<b><?php echo CHtml::encode($data->getAttributeLabel('id')); ?>:</b>
	<?php echo CHtml::link(CHtml::encode($data->id), array('view', 'id'=>$data->id)); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('title')); ?>:</b>
	<?php echo CHtml::encode($data->title); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('description')); ?>:</b>
	<?php echo CHtml::encode($data->description); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('homework')); ?>:</b>
	<?php echo CHtml::encode($data->homework); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('present')); ?>:</b>
	<?php echo CHtml::encode($data->present); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('date')); ?>:</b>
	<?php echo CHtml::encode($data->date); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('n_pp')); ?>:</b>
	<?php echo CHtml::encode($data->n_pp); ?>
	<br />

	<?php /*
	<b><?php echo CHtml::encode($data->getAttributeLabel('ticket')); ?>:</b>
	<?php echo CHtml::encode($data->ticket); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('hours')); ?>:</b>
	<?php echo CHtml::encode($data->hours); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('reports')); ?>:</b>
	<?php echo CHtml::encode($data->reports); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('title_report')); ?>:</b>
	<?php echo CHtml::encode($data->title_report); ?>
	<br />

	*/ ?>

</div>