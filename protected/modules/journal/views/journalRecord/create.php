<?php
/* @var $this JournalRecordController */
/* @var $model JournalRecord */

$this->breadcrumbs=array(
	'Journal Records'=>array('index'),
	'Create',
);

$this->menu=array(
	array('label'=>'List JournalRecord', 'url'=>array('index')),
	array('label'=>'Manage JournalRecord', 'url'=>array('admin')),
);
?>

<h1>Create JournalRecord</h1>

<?php $this->renderPartial('_form', array('model'=>$model)); ?>