<?php
/* @var $this JournalRecordTypeController */
/* @var $model JournalRecordType */

$this->breadcrumbs=array(
	'Journal Record Types'=>array('index'),
	$model->title,
);

$this->menu=array(
	array('label'=>'List JournalRecordType', 'url'=>array('index')),
	array('label'=>'Create JournalRecordType', 'url'=>array('create')),
	array('label'=>'Update JournalRecordType', 'url'=>array('update', 'id'=>$model->id)),
	array('label'=>'Delete JournalRecordType', 'url'=>'#', 'linkOptions'=>array('submit'=>array('delete','id'=>$model->id),'confirm'=>'Are you sure you want to delete this item?')),
	array('label'=>'Manage JournalRecordType', 'url'=>array('admin')),
);
?>

<h1>View JournalRecordType #<?php echo $model->id; ?></h1>

<?php $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		'id',
		'title',
		'description',
		'homework',
		'present',
		'date',
		'n_pp',
		'ticket',
		'hours',
		'reports',
		'title_report',
	),
)); ?>
