<?php
/* @var $this StudentGroupController */
/* @var $model StudentGroup */

$this->breadcrumbs=array(
	'Student Groups'=>array('index'),
	$model->id=>array('view','id'=>$model->id),
	'Update',
);

$this->menu=array(
	array('label'=>'List StudentGroup', 'url'=>array('index')),
	array('label'=>'Create StudentGroup', 'url'=>array('create')),
	array('label'=>'View StudentGroup', 'url'=>array('view', 'id'=>$model->id)),
	array('label'=>'Manage StudentGroup', 'url'=>array('admin')),
);
?>

<h1>Update StudentGroup <?php echo $model->id; ?></h1>

<?php $this->renderPartial('_form', array('model'=>$model)); ?>