<?php
/* @var $this EvaluationSystemController */
/* @var $model EvaluationSystem */

$this->breadcrumbs=array(
	'Evaluation Systems'=>array('index'),
	$model->title=>array('view','id'=>$model->id),
	'Update',
);

$this->menu=array(
	array('label'=>'List EvaluationSystem', 'url'=>array('index')),
	array('label'=>'Create EvaluationSystem', 'url'=>array('create')),
	array('label'=>'View EvaluationSystem', 'url'=>array('view', 'id'=>$model->id)),
	array('label'=>'Manage EvaluationSystem', 'url'=>array('admin')),
);
?>

<h1>Update EvaluationSystem <?php echo $model->id; ?></h1>

<?php $this->renderPartial('_form', array('model'=>$model)); ?>