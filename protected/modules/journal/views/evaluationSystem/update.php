<?php
/* @var $this EvaluationSystemController */
/* @var $model EvaluationSystem */

$this->breadcrumbs=array(
	Yii::t('journal','Journal')=>$this->createUrl('/journal'),
	Yii::t('journal','Evaluation Systems')=>array('index'),
	$model->title=>$this->createUrl('/journal/EvoluationSystem/'.$model->id),
	Yii::t('journal','Update'),
);


?>

<h1><?php echo Yii::t('journal','Update EvaluationSystem').': "'.$model->title.'""'; ?></h1>

<?php $this->renderPartial('_form', array('model'=>$model)); ?>