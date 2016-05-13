<?php
/* @var $this JournalRecordController */
/* @var $model JournalRecord */

$this->breadcrumbs=array(
	$this->breadcrumbs=array(
		Yii::t('journal','Page of journal')=>array('/journal/default/views/'.$model->load_id),
		Yii::t('journal','JournalRecord'),
		Yii::t('base','Create'),
	),
);
?>

<h1>Create JournalRecord</h1>

<?php $this->renderPartial('_form', array('model'=>$model)); ?>