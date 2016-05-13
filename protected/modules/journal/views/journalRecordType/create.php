<?php
/* @var $this JournalRecordTypeController */
/* @var $model JournalRecordType */

$this->breadcrumbs=array(
	Yii::t('journal','Journal')=>$this->createUrl('/journal'),
	Yii::t('journal','Journal Record Types')=>$this->createUrl('/journal/JournalRecordType'),
	Yii::t('journal','Create JournalRecordType'),
);
?>

<h1><?php echo Yii::t('journal','Create JournalRecordType');?></h1>

<?php $this->renderPartial('_form', array('model'=>$model)); ?>