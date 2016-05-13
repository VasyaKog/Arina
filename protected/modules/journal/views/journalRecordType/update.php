<?php
/* @var $this JournalRecordTypeController */
/* @var $model JournalRecordType */

$this->breadcrumbs=array(
	Yii::t('journal','Journal')=>$this->createUrl('/journal'),
	Yii::t('journal','Journal Record Types')=>$this->createUrl('/journal/JournalRecordType'),
	$model->title=>$this->createUrl('/journal/JournalRecordType/'.$model->id),
	Yii::t('journal','Update'),
);
?>

<h1><?php echo Yii::t('journal','Update JournalRecordType').' '.$model->title; ?></h1>

<?php $this->renderPartial('_form', array('model'=>$model)); ?>