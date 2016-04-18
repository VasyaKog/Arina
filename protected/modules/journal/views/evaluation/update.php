<?php
/* @var $this EvaluationController */
/* @var $model Evaluation */

$this->breadcrumbs=array(
	'Evaluations'=>array('index'),
	$model->title=>array('view','id'=>$model->id),
	'Update',
);

$this->menu=array(
	array('label'=>'List Evaluation', 'url'=>array('index')),
	array('label'=>'Create Evaluation', 'url'=>array('create')),
	array('label'=>'View Evaluation', 'url'=>array('view', 'id'=>$model->id)),
	array('label'=>'Manage Evaluation', 'url'=>array('admin')),
);
?>

<h1>Update Evaluation <?php echo $model->id; ?></h1>

<?php $this->renderPartial('_form', array('model'=>$model)); ?>