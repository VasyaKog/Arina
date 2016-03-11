<?php
/**
 *
 * @var TeacherController $this
 * @var \CActiveDataProvider $provider
 * @var Teacher $model
 */
?>
<?php
$this->breadcrumbs = array(
    Yii::t('teacher', 'Teachers'),
);
$this->menu = array(
    array(
        'type' => 'primary',
        'label' => Yii::t('teacher', 'Add new teacher'),
        'url' => $this->createUrl('teacher/create'),
    ),
);
?>

    <h2><?php echo Yii::t('teacher', 'Teacher list') ?></h2>

    <p>
        <?php echo Yii::t('base', 'You may optionally enter a comparison operator (<b>&lt;</b>, <b>&lt;=</b>, <b>&gt;</b>, <b>&gt;=</b>, <b>&lt;&gt;</b> or <b>=</b>) at the beginning of each of your search values to specify how the comparison should be done.'); ?>
    </p>

<?php
$columns = array(
    array(
        'name' => 'last_name',
        'htmlOptions' => array('style' => 'width: 160px'),
    ),
    array(
        'name' => 'first_name',
        'htmlOptions' => array('style' => 'width: 160px'),
    ),
    array(
        'name' => 'middle_name',
        'htmlOptions' => array('style' => 'width: 160px'),
    ),
    array(
        'type' => 'raw',
        'name' => 'cyclic_commission_id',
        'value' => 'CHtml::link($data->cyclicCommission->title, array("cyclicCommission/view","id"=>$data->cyclic_commission_id))',
        'htmlOptions' => array(),
        'filter' => CHtml::listData(CyclicCommission::model()->findAll(), 'id', 'title')
    ),
    array(
        'header' => Yii::t('base', 'Actions'),
        'htmlOptions' => array('nowrap' => 'nowrap'),
        'class' => 'bootstrap.widgets.TbButtonColumn',
        'template' => '{update}{delete}{view}',
    )
);

$this->renderPartial('//tableList',
    array(
        'provider' => $provider,
        'columns' => $columns,
        'filter' => $model,
    )
);