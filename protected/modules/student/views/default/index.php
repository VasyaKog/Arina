<?php
/* @var $this StudentController */
/* @var array $columns */
/* @var $model Student */

$this->breadcrumbs = array(
    Yii::t("student", "Students") => array('index'),
);

$this->menu = array(
    array(
        'type' => BoosterHelper::TYPE_PRIMARY,
        'label' => Yii::t('student', 'Create Student'),
        'url' => $this->createUrl('create'),
    ),
);

Yii::app()->clientScript->registerScript('search', "
$('.search-button').click(function(){
	$('.search-form').toggle();
	return false;
});
$('.search-form form').submit(function(){
	$('#student-grid').yiiGridView('update', {
		data: $(this).serialize()
	});
	return false;
});
");
?>

<h1><?php echo Yii::t("student", "Students") ?></h1>

<p>
    <?php
    echo Yii::t('base', 'You may optionally enter a comparison operator (<b>&lt;</b>, <b>&lt;=</b>, <b>&gt;</b>, <b>&gt;=</b>, <b>&lt;&gt;</b> or <b>=</b>) at the beginning of each of your search values to specify how the comparison should be done.')
    ?>
</p>

<?php echo CHtml::link(Yii::t('base', 'Advanced Search'), '#', array('class' => 'search-button btn btn-info')); ?>
<div class="search-form" style="display:none;">
    <?php $this->renderPartial('_search', array(
        'model' => $model,
    )); ?>
</div>

<?php

$this->widget(BoosterHelper::GRID_VIEW, array(
    'id' => 'student-grid',
    'dataProvider' => $model->search(),
    'filter' => $model,
    'responsiveTable' => true,
    'type' => 'striped condensed bordered hover',
    'columns' => array(
        array(
            'name'=>'code',
            'htmlOptions' => array('width'=>'100px'),
        ),
        array(
            'name' => 'fullName',
            'value' => '$data->getFullName()',
        ),
       array(
            'header' => 'Group',
            'type' => 'raw',
            'value' => '$data->getGroupListLinks()',
            'htmlOptions' => array('width' => '190px'),
            /*'filter' => CHtml::dropDownList('Student[f]',
                    $model->group_id,
                    CHtml::listData(Group::model()->findAll(), 'id', 'title'),
                    array('empty' => '')),
            */
       ),

        array(
            'header' => Yii::t('base', 'Actions'),
            'htmlOptions' => array('nowrap' => 'nowrap'),
            'class' => 'bootstrap.widgets.TbButtonColumn',
            'template' => '{update}{delete}{view}',
        ),
    ),
));
?>


