<?php
/* @var $this BenefitsController */
/* @var $model Benefits */

$this->breadcrumbs=array(
	Yii::t('benefits','Benefits')=>array('index'),
	$model->title,
);

$this->menu=array(
	array('label'=>Yii::t('benefits','List Benefits'),'url'=>array('index'),'type'=>BoosterHelper::TYPE_PRIMARY),
	array('label'=>Yii::t('benefits','Create Benefits'), 'url'=>array('create'),'type'=>BoosterHelper::TYPE_PRIMARY),
	array('label'=>Yii::t('benefits','View Benefits'), 'url'=>array('view', 'id'=>$model->id),'type'=>BoosterHelper::TYPE_PRIMARY),
	array('label'=>Yii::t('benefits','Update Benefits'), 'url'=>array('update', 'id'=>$model->id),'type'=>BoosterHelper::TYPE_PRIMARY),
	array('label'=>Yii::t('benefits','Delete Benefits'), 'url'=>'#', 'linkOptions'=>array('submit'=>array('delete','id'=>$model->id),'confirm'=>'Are you sure you want to delete this item?'),'icon'=>'trash', 'type'=>BoosterHelper::TYPE_PRIMARY),
);
?>

<h1><?php echo Yii::t('benefits','View Benefits #') ?><?php echo $model->id; ?></h1>

<?php $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		'title',
	),
)); ?>
