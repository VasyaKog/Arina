<?php
/* @var $this EvaluationSystemController */
/* @var $dataProvider CActiveDataProvider */

$this->breadcrumbs=array(
	Yii::t('journal','Journal')=>$this->createUrl('/journal'),
	Yii::t('journal','Evaluation Systems'),
);

$this->menu=array(
	array(
		'label'=> Yii::t('journal','Create EvaluationSystem'),
		'type' => BoosterHelper::TYPE_PRIMARY,
		'url'=>array('create')),
);
?>

<h1><?php echo Yii::t('journal','Evaluation Systems');?></h1>

<?php
$columns= array(
	'title',
	array(
		'header' => Yii::t('base', 'Actions'),
		'class' => 'bootstrap.widgets.TbButtonColumn',
		'template' => '{update}{delete}',
	),
);
$this->renderPartial('//tableList', array(
	'provider'=>$dataProvider,
	'columns' =>$columns,
)); ?>
