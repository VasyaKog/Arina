<?php
/* @var $this DefaultController */
/* @var $model Group */
/* @var $form TbActiveForm */
$this->breadcrumbs = array(
    Yii::t('present', 'Present'),
);

$this->menu = array(
    array(
        'type' => BoosterHelper::TYPE_INFO,
        'label' => Yii::t('present', 'Generate excel list'),
        'url' => $this->createUrl('excelList'),
    ),
);

?>
<h1><?php echo Yii::t('present', 'Present'); ?></h1>

<?php 

$this->renderPartial('_form', array('model' => $model));

?>


