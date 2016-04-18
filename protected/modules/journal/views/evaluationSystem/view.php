<?php
/* @var $this EvaluationSystemController */
/* @var $model EvaluationSystem */

$this->breadcrumbs=array(
	'Evaluation Systems'=>array('index'),
	$model->title,
);

$this->menu=array(
	array('label'=>'List EvaluationSystem', 'url'=>array('index')),
	array('label'=>'Create EvaluationSystem', 'url'=>array('create')),
	array('label'=>'Update EvaluationSystem', 'url'=>array('update', 'id'=>$model->id)),
	array('label'=>'Delete EvaluationSystem', 'url'=>'#', 'linkOptions'=>array('submit'=>array('delete','id'=>$model->id),'confirm'=>'Are you sure you want to delete this item?')),
	array('label'=>'Manage EvaluationSystem', 'url'=>array('admin')),
);
?>

<h1>View EvaluationSystem #<?php echo $model->id; ?></h1>

<?php $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		'id',
		'title',
	),
)); ?>
