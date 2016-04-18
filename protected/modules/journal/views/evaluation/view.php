<?php
/* @var $this EvaluationController */
/* @var $model Evaluation */

$this->breadcrumbs=array(
	'Evaluations'=>array('index'),
	$model->title,
);

$this->menu=array(
	array('label'=>'List Evaluation', 'url'=>array('index')),
	array('label'=>'Create Evaluation', 'url'=>array('create')),
	array('label'=>'Update Evaluation', 'url'=>array('update', 'id'=>$model->id)),
	array('label'=>'Delete Evaluation', 'url'=>'#', 'linkOptions'=>array('submit'=>array('delete','id'=>$model->id),'confirm'=>'Are you sure you want to delete this item?')),
	array('label'=>'Manage Evaluation', 'url'=>array('admin')),
);
?>

<h1>View Evaluation #<?php echo $model->id; ?></h1>

<?php $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		'id',
		'title',
		'system_id',
	),
)); ?>
