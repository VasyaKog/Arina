<?php
/* @var $this JournalRecordController */
/* @var $model JournalRecord */

$this->breadcrumbs=array(
	Yii::t('journal','Page of journal')=>array('/journal/default/views/'.$model->load_id),
	Yii::t('journal','JournalRecord').':'.$model->types->title.' '.$model->date=>array('views','id'=>$model->id),
	Yii::t('journal','Update'),
);
?>

	<?php echo '<h1>'.Yii::t('journal','JournalRecord').':'.$model->types->title.' '.$model->date.'</h1>' ?>

<?php $this->renderPartial('_form', array('model'=>$model)); ?>