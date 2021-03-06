<?php
$this->breadcrumbs = array(
    Yii::t('base', 'Departments') => array('index'),
    $model->title => array('view', 'id' => $model->id),
    Yii::t('base', 'Updating'),
);

$this->menu = array(
    array('label' => Yii::t('department', 'Departments list'), 'url' => array('index'), 'type' => BoosterHelper::TYPE_PRIMARY),
    array('label' => Yii::t('department', 'Create new department'), 'url' => array('create'), 'type' => BoosterHelper::TYPE_PRIMARY),
    array('label' => Yii::t('department', 'View department'), 'url' => array('view', 'id' => $model->id)),
    array('label' => Yii::t('department', 'Manage department'), 'url' => array('admin')),
);
?>

    <h2><?php echo Yii::t('department', 'Update department') . " $model->id"; ?></h2>

<?php echo $this->renderPartial('_form', array('model' => $model)); ?>