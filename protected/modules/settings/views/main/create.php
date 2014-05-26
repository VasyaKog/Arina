<?php
/* @var $this MainControllerController */
/* @var $model Settings */

$this->breadcrumbs = array(
    Yii::t('base', 'Settings') => array('index'),
    Yii::t('base', 'Create'),
);

$this->menu = array(
	array('label' => Yii::t('settings', 'List Settings'), 'url'=>array('index')),
);
?>

<h2><?php echo Yii::t('settings', 'Create Settings'); ?></h2>

<?php $this->renderPartial('_form', array('model'=>$model)); ?>