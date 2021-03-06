<?php
/* @var $this ActualClassController */
/* @var $model ActualClass */

$this->breadcrumbs = array(
    Yii::t('actualClass', 'Actual Classes') => array('index'),
    Yii::t('actualClass', 'Manage'),
);

$this->menu = array(
    array('label' => Yii::t('actualClass', 'List ActualClass'), 'url' => array('index')),
    array('label' => Yii::t('actualClass', 'Create ActualClass'), 'url' => array('create')),
);

Yii::app()->clientScript->registerScript('search', "
$('.search-button').click(function(){
	$('.search-form').toggle();
	return false;
});
$('.search-form form').submit(function(){
	$('#actual-class-grid').yiiGridView('update', {
		data: $(this).serialize()
	});
	return false;
});
");
?>

<h1>Manage Actual Classes</h1>

<p>
    <?php echo Yii::t('base', 'You may optionally enter a comparison operator (<b>&lt;</b>, <b>&lt;=</b>, <b>&gt;</b>, <b>&gt;=</b>, <b>&lt;&gt;</b> or <b>=</b>) at the beginning of each of your search values to specify how the comparison should be done.'); ?>
</p>

<?php echo CHtml::link(Yii::t('base', 'Advanced Search'), '#', array('class' => 'search-button')); ?>
<div class="search-form" style="display:none">
    <?php $this->renderPartial('_search', array(
        'model' => $model,
    )); ?>
</div><!-- search-form -->

<?php $this->widget('zii.widgets.grid.CGridView', array(
    'id' => 'actual-class-grid',
    'dataProvider' => $model->search(),
    'filter' => $model,
    'columns' => array(
        'id',
        'date',
        'class_number',
        'teacher_load_id',
        'class_type',
        array(
            'class' => 'CButtonColumn',
        ),
    ),
)); ?>
