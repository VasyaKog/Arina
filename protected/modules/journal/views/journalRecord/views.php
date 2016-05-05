<?php
/* @var $this JournalRecordController */
/* @var $model JournalRecord */

$this->breadcrumbs=array(
	'Journal Records'=>array('index'),
	$model->id,
);

$this->menu=array(
	array('label'=>'List JournalRecord', 'url'=>array('index')),
	array('label'=>'Create JournalRecord', 'url'=>array('create')),
	array('label'=>'Update JournalRecord', 'url'=>array('update', 'id'=>$model->id)),
	array('label'=>'Delete JournalRecord', 'url'=>'#', 'linkOptions'=>array('submit'=>array('delete','id'=>$model->id),'confirm'=>'Are you sure you want to delete this item?')),
	array('label'=>'Manage JournalRecord', 'url'=>array('admin')),
);
?>

<h1>View JournalRecord #<?php echo $model->id; ?></h1>

<?php $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		'id',
		'type_id',
		'date',
		'description',
		'home_work',
		'load_id',
		'teacher_id',
		'n_pp',
		'numer_in_day',
	),
)); ?>
