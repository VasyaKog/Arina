<?php
/* @var $this JournalRecordController */
/* @var $model JournalRecord */

$this->breadcrumbs=array(
	Yii::t('journal','Page of journal')=>array('/journal/default/views/'.$model->load_id),
	Yii::t('journal','JournalRecord'),
);
$this->menu=array(
	array('label'=>Yii::t('journal','Update'), 'url'=>array('update', 'id'=>$model->id)),
	array('label'=>Yii::t('journal','Delete'), 'url'=>array('delete', 'id'=>$model->id), 'linkOptions'=>array('submit'=>array('delete','id'=>$model->id),'confirm'=>'Are you sure you want to delete this item?')),
);
?>

<?php $this->widget('booster.widgets.TbDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		array(
			'name'=>'type_id',
			'value'=>$model->types->title,
		),
		'date',
		'description',
		'home_work',
		array(
			'name'=>'teacher_id',
			'value'=>$model->teacher->getFullName(),
		),
		'hours',
		'numer_in_day',
	),
)); ?>
