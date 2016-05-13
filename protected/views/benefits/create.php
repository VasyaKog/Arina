<?php
/* @var $this BenefitsController */
/* @var $model Benefits */

$this->breadcrumbs=array(
	Yii::t('benefits','List Benefits')=>array('index'),
	Yii::t('benefits', "Sign up new benefits"),
);
?>

<header>
	<h2><?php echo Yii::t('benefits', "Sign up new benefits"); ?></h2>
</header>

<?php $this->renderPartial('_form', array('model'=>$model)); ?>