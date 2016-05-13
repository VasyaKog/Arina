<?php
/**
 * @var GroupController $this
 * @var Group $model
 */

$this->breadcrumbs = array(
    Yii::t('group', 'Groups') => array('index'),
    $model->title,
);
$this->menu = array(
    array(
        'type' => BoosterHelper::TYPE_PRIMARY,
        'label' => Yii::t('group', 'Groups list'),
        'url' => $this->createUrl('index'),
    ),
    array(
        'type' => BoosterHelper::TYPE_PRIMARY,
        'label' => Yii::t('group', 'Create new group'),
        'url' => $this->createUrl('create'),
    ),
    array(
        'label' => Yii::t('student', 'Students of group'),
        'icon' => 'list',
        'url' => Yii::app()->createUrl('student/default/group', array('id' => $model->id)),
    ),
    array(
        'label' => Yii::t('group', 'Update group'),
        'icon' => 'pencil',
        'url' => $this->createUrl('update', array('id' => $model->id)),
    ),
    array(
        'label' => Yii::t('group', 'Delete group'),
        'url' => $this->createUrl('delete', array('id' => $model->id)),
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
<h2><?php echo Yii::t('group', 'View group') . " {$model->title}"; ?></h2>

<?php $this->widget(BoosterHelper::DETAIL_VIEW, array(
    'data' => $model,
    'attributes' => array(
        'title',
        array(
            'type'=>'raw',
            'value' => $model->getCuratorLink(), 'name' => 'curator_id'),
        array(
            'name' => Yii::t('student','Students count'),
            'value' => $model->getStudentsCount(),
        ),
        array(
            'name' => Yii::t('student','Budget students count'),
            'value' => $model->getBudgetStudentsCount(),
        ),
        array(
            'name' => Yii::t('student','Contract students count'),
            'value' => $model->getContractStudentsCount(),
        ),
    ),
));
?>
