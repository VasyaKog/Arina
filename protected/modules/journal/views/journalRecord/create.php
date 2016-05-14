<?php
/* @var $this JournalRecordController */
/* @var $model JournalRecord */

$this->breadcrumbs=array(
		Yii::t('journal','Page of journal')=>array('/journal/default/views/','id'=>$model->load_id),
		Yii::t('journal','JournalRecord'),
		Yii::t('base','Create'),
);
?>

<?php $this->renderPartial('_form', array('model'=>$model)); ?>