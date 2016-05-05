<?php
/* @var $this JournalRecordController */
/* @var $dataProvider CActiveDataProvider */

$this->breadcrumbs=array(
	'Journal Records',
);

$this->menu=array(
	array('label'=>'Create JournalRecord', 'url'=>array('create')),
	array('label'=>'Manage JournalRecord', 'url'=>array('admin')),
);
?>

<h1>Journal Records</h1>

<?php $this->widget(
	'zii.widgets.CListView',
	array(
		'dataProvider'=>$dataProvider,
		'itemView'=>'_view',
)
); ?>
