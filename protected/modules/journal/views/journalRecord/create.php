<?php
/* @var $this JournalRecordController */
/* @var $model JournalRecord */

$this->breadcrumbs=array(
	'Journal Records'=>array('index'),
	'Create',
);
?>

<h1>Create JournalRecord</h1>

<?php $this->renderPartial('_form', array('model'=>$model)); ?>