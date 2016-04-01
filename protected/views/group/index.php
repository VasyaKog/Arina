<?php
/**
 *
 * @var GroupController $this
 * @var \CActiveDataProvider $provider
 * * @var integer $speciality_id
 */
?>
<?php
$this->breadcrumbs = array(
    Yii::t('group', 'Groups'),
);
$this->menu = array(
    array(
        'type' => BoosterHelper::TYPE_PRIMARY,
        'label' => Yii::t('group', 'Create new group'),
        'url' => $this->createUrl('create'),
    ),
);
?>


    <h2><?php echo Yii::t('group', 'Groups list'); ?></h2>
<?php $this->renderPartial('//tableList',
    array(
        'provider' => $provider,
        'columns' => array(
            array('name' => 'title'),
            array(
                'header' => Yii::t('teacher', 'Curator'),
                'type' => 'raw',
                'name' => 'curator.name',
                'value' => '$data->getCuratorLink()',
            ),
            array(
                'header' => Yii::t('base', 'Speciality'),
                'type' => 'raw',
                'name' => 'speciality.title',
                'value' => 'CHtml::link($data->speciality->title, array("speciality/view", "id"=>$data->speciality_id))',
            ),
            array(
                'header' => Yii::t('base', 'Actions'),
                'htmlOptions' => array('nowrap' => 'nowrap'),
                'class' => 'bootstrap.widgets.TbButtonColumn',
                'template' => '{update}{delete}{view}{students}{excel}{doc}',
                'buttons' => array(
                    'students' => array(
                        'label' => Yii::t('student', 'Students list'),
                        'icon' => 'icon-list',
                        'url' => 'Yii::app()->createUrl("student/default/group", array("id"=>$data->id))',
                    ),
                    'excel' => array(
                        'label' => Yii::t('student', 'Get "fast" list'),
                        'icon' => 'icon-file',
                        'url' => 'Yii::app()->createUrl("/group/makeExcel", array("id"=>$data->id))',
                    ),
                    'doc' => array(
                        'label' => Yii::t('student', 'Create document'),
                        'icon' => 'icon-list-alt',
                        'url' => 'Yii::app()->createUrl("/group/doc", array("id"=>$data->id))',
                    ),
                ),
            )
        ),
    )
);
?>