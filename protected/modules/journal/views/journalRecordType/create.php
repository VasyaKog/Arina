<?php
/* @var $this JournalRecordTypeController */
/* @var $model JournalRecordType */

$this->breadcrumbs=array(
	'Journal Record Types'=>array('index'),
	'Create',
);

$this->menu=array(
	array('label'=>'List JournalRecordType', 'url'=>array('index')),
	array('label'=>'Manage JournalRecordType', 'url'=>array('admin')),
);
?>

<h1>Create JournalRecordType</h1>

<?php $this->renderPartial('_form', array('model'=>$model)); ?>