<?php
/* @var $this EvaluationSystemController */
/* @var $model EvaluationSystem */

$this->breadcrumbs=array(
	Yii::t('journal','Journal')=>$this->createUrl('/journal'),
	Yii::t('journal','Evaluation Systems')=>array('index'),
	Yii::t('journal','Create EvaluationSystem'),
);

?>

<h1><?php echo Yii::t('journal','Create EvaluationSystem');?></h1>

<?php $this->renderPartial('_form', array('model'=>$model)); ?>