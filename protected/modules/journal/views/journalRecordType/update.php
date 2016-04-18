<?php
/* @var $this JournalRecordTypeController */
/* @var $model JournalRecordType */

$this->breadcrumbs=array(
	'Journal Record Types'=>array('index'),
	$model->title=>array('view','id'=>$model->id),
	'Update',
);

$this->menu=array(
	array('label'=>'List JournalRecordType', 'url'=>array('index')),
	array('label'=>'Create JournalRecordType', 'url'=>array('create')),
	array('label'=>'View JournalRecordType', 'url'=>array('view', 'id'=>$model->id)),
	array('label'=>'Manage JournalRecordType', 'url'=>array('admin')),
);
?>

<h1>Update JournalRecordType <?php echo $model->id; ?></h1>

<?php $this->renderPartial('_form', array('model'=>$model)); ?>