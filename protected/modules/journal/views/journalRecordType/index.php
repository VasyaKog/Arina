<?php
/* @var $this JournalRecordTypeController */
/* @var $dataProvider CActiveDataProvider */

$this->breadcrumbs=array(
	'Journal Record Types',
);

$this->menu=array(
	array('label'=>'Create JournalRecordType', 'url'=>array('create')),
	array('label'=>'Manage JournalRecordType', 'url'=>array('admin')),
);
?>

<h1>Journal Record Types</h1>

<?php $this->widget('zii.widgets.CListView', array(
	'dataProvider'=>$dataProvider,
	'itemView'=>'_view',
)); ?>
