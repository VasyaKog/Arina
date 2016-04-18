<?php
/* @var $this EvaluationSystemController */
/* @var $dataProvider CActiveDataProvider */

$this->breadcrumbs=array(
	'Evaluation Systems',
);

$this->menu=array(
	array('label'=>'Create EvaluationSystem', 'url'=>array('create')),
	array('label'=>'Manage EvaluationSystem', 'url'=>array('admin')),
);
?>

<h1>Evaluation Systems</h1>

<?php $this->widget('zii.widgets.CListView', array(
	'dataProvider'=>$dataProvider,
	'itemView'=>'_view',
)); ?>
