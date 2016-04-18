<?php
/* @var $this EvaluationSystemController */
/* @var $model EvaluationSystem */

$this->breadcrumbs=array(
	'Evaluation Systems'=>array('index'),
	'Create',
);

$this->menu=array(
	array('label'=>'List EvaluationSystem', 'url'=>array('index')),
	array('label'=>'Manage EvaluationSystem', 'url'=>array('admin')),
);
?>

<h1>Create EvaluationSystem</h1>

<?php $this->renderPartial('_form', array('model'=>$model)); ?>