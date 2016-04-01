<?php
/* @var $this StudentGroupController */
/* @var $model StudentGroup */

$this->breadcrumbs=array(
	'Student Groups'=>array('index'),
	'Create',
);

$this->menu=array(
	array('label'=>'List StudentGroup', 'url'=>array('index')),
	array('label'=>'Manage StudentGroup', 'url'=>array('admin')),
);
?>

<h1>Create StudentGroup</h1>

<?php $this->renderPartial('_form', array('model'=>$model)); ?>