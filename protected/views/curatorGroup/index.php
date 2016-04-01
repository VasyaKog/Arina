<?php
/* @var $this CuratorGroupController */
/* @var $dataProvider CActiveDataProvider */

$this->breadcrumbs=array(
	Yii::t('curatorGroup','Curator Groups'),
);

$this->menu=array(
	array(
		'type' =>'primary',
		'label'=>Yii::t('curatorGroup','Create CuratorGroup'),
		'url'=>array('create')),
);
?>

<h1><?php echo Yii::t('curatorGroup','Curator Groups')?></h1>

<?php
$columns= array(
	array(
		'header'=>Yii::t('group','Curator'),
		'type'=>'raw',
		'value' => 'CHtml::link($data->curator->getFullName(),array("../teacher/view/","id"=>$data->teacher_id))',
	),
	array(
		'header'=>Yii::t('terms','Type'),
		'type'=>'raw',
		'value' =>'CHtml::label($data->getType(),false)',
	),
	array(
		'header'=>Yii::t('group','Group'),
		'type'=>'raw',
		'value'=>'CHtml::link($data->group->title,array("../group"))',
	),
	array(
		'name'=>'date',
	),
	array(
		'name'=>'comment',
	),
	array(
		'header' => Yii::t('base', 'Actions'),
		'htmlOptions' => array('nowrap' => 'nowrap'),
		'class' => 'bootstrap.widgets.TbButtonColumn',
		'template' => '{update}{delete}{view}',
	),
);
$this->renderPartial('//tableList',
	array(
		'provider'=>$dataProvider,
		'columns'=>$columns,
)); ?>
