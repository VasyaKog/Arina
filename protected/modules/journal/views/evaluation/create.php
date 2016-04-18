<?php
/* @var $this EvaluationController */
/* @var $model Evaluation */

$this->breadcrumbs=array(
	'Evaluations'=>array('index'),
	'Create',
);

$this->menu=array(
	array('label'=>'List Evaluation', 'url'=>array('index')),
	array('label'=>'Manage Evaluation', 'url'=>array('admin')),
);
?>

<h1>Create Evaluation</h1>

<?php $this->renderPartial('_form', array('model'=>$model)); ?>