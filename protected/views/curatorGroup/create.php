<?php
/* @var $this CuratorGroupController */
/* @var $model CuratorGroup */

$this->breadcrumbs=array(
	'Curator Groups'=>array('index'),
	'Create',
);

$this->menu=array(
	array('label'=>'List CuratorGroup', 'url'=>array('index')),
	array('label'=>'Manage CuratorGroup', 'url'=>array('admin')),
);
?>

<h1>Create CuratorGroup</h1>

<?php $this->renderPartial('_form', array('model'=>$model)); ?>