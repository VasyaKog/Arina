<?php
/**
 *
 * @var TeacherController $this
 * @var Teacher $model
 */
$this->breadcrumbs = array(
    $model->getFullName(),
    Yii::t('teacher', 'Teachers') => array('index'),
);

$this->menu = array(
    array('label' => Yii::t('teacher', 'Teacher list'), 'url' => array('index'), 'type' => BoosterHelper::TYPE_PRIMARY),
    array('label' => Yii::t('teacher', 'Add new teacher'), 'url' => array('hr/default/create'), 'type' => BoosterHelper::TYPE_PRIMARY),
    array(
        'label' => Yii::t('teacher', 'Update teacher'),
        'icon' => 'pencil',
        'url' => $this->createUrl('update', array('id' => $model->id)),
    ),
    array(
        'label' => Yii::t('teacher', 'Delete teacher'),
        'icon' => 'trash',
        'htmlOptions' => array(
            'submit' => array(
                'delete',
                'id' => $model->id,
            ),
            'confirm' => Yii::t('base', 'Do you want to delete this item?'),
        ),
    ),
);
?>

<h2><?php echo $model->getFullName(); ?></h2>

<?php $this->widget('bootstrap.widgets.TbDetailView', array(
    'data' => $model,
    'attributes' => array(
        'last_name',
        'first_name',
        'middle_name',
        'short_name',
        array(
            'type' => 'raw',
            'label' => Yii::t('teacher', 'Cyclic Commission'),
            'value' => CHtml::link($model->getCyclicCommissionName(), array('cyclicCommission/view', 'id' => $model->cyclic_commission_id)),
        ),

    ),
)); ?>
