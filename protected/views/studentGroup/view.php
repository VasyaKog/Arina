<?php
/* @var $this StudentGroupController */
/* @var $model StudentGroup */

$this->breadcrumbs=array(
	'Student Groups'=>array('index'),
	$model->id,
);

$this->menu=array(
	array('label'=>'List StudentGroup', 'url'=>array('index')),
	array('label'=>'Create StudentGroup', 'url'=>array('create')),
	array('label'=>'Update StudentGroup', 'url'=>array('update', 'id'=>$model->id)),
	array('label'=>'Delete StudentGroup', 'url'=>'#', 'linkOptions'=>array('submit'=>array('delete','id'=>$model->id),'confirm'=>'Are you sure you want to delete this item?')),
	array('label'=>'Manage StudentGroup', 'url'=>array('admin')),
);
?>

<h1>View StudentGroup #<?php echo $model->id; ?></h1>

<?php $this->widget('bootstrap.widgets.TbDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		array(
			'label'=> Yii::t('studentGroup','Student'),
			'type' => 'raw',
			'value' => CHtml::link($model->student->getFullNameAndCode(),array("student/default/view/","id"=>$model->student_id)),
			'htmlOptions' => array('style' => 'width: 160px'),
		),
		array(
			'name' =>'date',
			'htmlOptions' => array('style' => 'width: 160px'),
		),
		array(
			'label'=> Yii::t('studentGroup','Group'),
			'type' => 'raw',
			'value' => CHtml::link($model->group->title,array("group/view","id"=>$model->group_id)),
			'htmlOptions' => array('style' => 'width: 160px'),
		),
		array(
			'label'=> Yii::t('studentGroup','Type action'),
			'type' => 'raw',
			'value' => CHtml::label($model->getTypes(),false),
		),
		array(
			'name' =>'comment',
			'htmlOptions' => array('style' => 'width: 160px'),
		),
	),
)); ?>
