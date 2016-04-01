<?php
/* @var $this BenefitsController */
/* @var $model Benefits */

$this->breadcrumbs=array(
	Yii::t('base','Benefits')=>array('index'),
	$model->title=>array('view','id'=>$model->id),
	Yii::t('base','Update'),
);

$this->menu=array(
	array('label'=>Yii::t('benefits','List Benefits'),'url'=>array('index'),'type'=>BoosterHelper::TYPE_PRIMARY),
	array('label'=>Yii::t('benefits','Create Benefits'), 'url'=>array('create'),'type'=>BoosterHelper::TYPE_PRIMARY),
	array('label'=>Yii::t('benefits','View Benefits'), 'url'=>array('view', 'id'=>$model->id),'type'=>BoosterHelper::TYPE_PRIMARY),
);
?>

<h1>Update Benefits <?php echo $model->id; ?></h1>

<?php $this->renderPartial('_form', array('model'=>$model)); ?>