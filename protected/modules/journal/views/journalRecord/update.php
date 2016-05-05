<?php
/* @var $this JournalRecordController */
/* @var $model JournalRecord */

$this->breadcrumbs=array(
	'Journal Records'=>array('index'),
	$model->id=>array('view','id'=>$model->id),
	'Update',
);

$this->menu=array(
	array('label'=>'List JournalRecord', 'url'=>array('index')),
	array('label'=>'Create JournalRecord', 'url'=>array('create')),
	array('label'=>'View JournalRecord', 'url'=>array('view', 'id'=>$model->id)),
	array('label'=>'Manage JournalRecord', 'url'=>array('admin')),
);
?>

<h1>Update JournalRecord <?php echo $model->id; ?></h1>

<?php $this->renderPartial('_form', array('model'=>$model)); ?>