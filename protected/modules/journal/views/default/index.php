<?php
/* @var $this DefaultController */
/* @var $model Group */
/* @var $form TbActiveForm */
$this->breadcrumbs = array(
    Yii::t('base', 'Journal'),
);
?>
<h1><?php echo Yii::t('base', 'Journal'); ?></h1>

<?php 

$this->renderPartial('_form', array('model' => $model));

?>
