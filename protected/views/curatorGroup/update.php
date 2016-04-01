<?php
/* @var $this CuratorGroupController */
/* @var $model CuratorGroup */

$this->breadcrumbs=array(
	'Curator Groups'=>array('index'),
	$model->id=>array('view','id'=>$model->id),
	'Update',
);

$this->menu=array(
	array('label'=>'List CuratorGroup', 'url'=>array('index')),
	array('label'=>'Create CuratorGroup', 'url'=>array('create')),
	array('label'=>'View CuratorGroup', 'url'=>array('view', 'id'=>$model->id)),
	array('label'=>'Manage CuratorGroup', 'url'=>array('admin')),
);
?>

<h1>Update CuratorGroup <?php echo $model->id; ?></h1>

<?php $this->renderPartial('_form', array('model'=>$model)); ?>