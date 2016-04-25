<?php
/* @var $this EvaluationController */
/* @var $model Evaluation */

$this->breadcrumbs=array(
	Yii::t('journal','Journal')=>$this->createUrl('/journal'),
	Yii::t('journal','Evaluations')=>array('index'),
	Yii::t('journal','Create Evaluation'),
);
?>

<h1><?php echo Yii::t('journal','Create Evaluation');?></h1>

<?php $this->renderPartial('_form', array('model'=>$model)); ?>