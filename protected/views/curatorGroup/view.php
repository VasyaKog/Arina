<?php
/* @var $this CuratorGroupController */
/* @var $model CuratorGroup */

$this->breadcrumbs=array(
	'Curator Groups'=>array('index'),
	$model->id,
);

$this->menu=array(
	array('label'=>'List CuratorGroup', 'url'=>array('index')),
	array('label'=>'Create CuratorGroup', 'url'=>array('create')),
	array('label'=>'Update CuratorGroup', 'url'=>array('update', 'id'=>$model->id)),
	array('label'=>'Delete CuratorGroup', 'url'=>'#', 'linkOptions'=>array('submit'=>array('delete','id'=>$model->id),'confirm'=>'Are you sure you want to delete this item?')),
	array('label'=>'Manage CuratorGroup', 'url'=>array('admin')),
);
?>

<h1>View CuratorGroup #<?php echo $model->id; ?></h1>

<?php $this->widget('bootstrap.widgets.TbDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		array(
			'label'=>Yii::t('group','Curator'),
			'type'=>'raw',
			'value'=>CHtml::link($model->curator->getFullName(),array("teacher/view/","id"=>$model->teacher_id)),
		),
		'type',
		'group_id',
		'date',
		'comment',
	),
)); ?>
