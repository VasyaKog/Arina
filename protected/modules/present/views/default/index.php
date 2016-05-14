<?php
/* @var $this DefaultController */
/* @var $model Group */
/* @var $form TbActiveForm */
$this->breadcrumbs = array(
    Yii::t('base', 'Present'),
);
?>
<h1><?php echo Yii::t('present', 'Present'); ?></h1>

<?php 

$this->renderPartial('_form', array('model' => $model));

/*$this->widget('journal.widgets.PageJournal', array(
    'load_id' => $id,
));
*/
?>


