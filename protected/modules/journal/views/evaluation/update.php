<?php
/* @var $this EvaluationController */
/* @var $model Evaluation */

$this->breadcrumbs=array(
	Yii::t('journal','Journal')=>$this->createUrl('/journal'),
	Yii::t('journal','Evaluations')=>array('index'),
	$model->title=>array($model->title),
	Yii::t('journal','Update'),
);

?>

<h1><?php echo Yii::t('journal','Update Evaluation').' "'.$model->title.'"'; ?></h1>

<?php $this->renderPartial('_form', array('model'=>$model)); ?>