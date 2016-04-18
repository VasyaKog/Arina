<?php
/* @var $this EvaluationController */
/* @var $dataProvider CActiveDataProvider */

$this->breadcrumbs=array(
	'Evaluations',
);

$this->menu=array(
	array('label'=>'Create Evaluation', 'url'=>array('create')),
	array('label'=>'Manage Evaluation', 'url'=>array('admin')),
);
?>

<h1>Evaluations</h1>

<?php $this->widget('zii.widgets.CListView', array(
	'dataProvider'=>$dataProvider,
	'itemView'=>'_view',
)); ?>
