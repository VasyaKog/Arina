<?php
/* @var $this DefaultController */
/* @var array $columns */
/* @var $model Employee */


$this->breadcrumbs = array(
    Yii::t('employee', 'Employees') => array('index'),
);

        Yii::app()->session['arr_id'] = $model->search()->getData();
$this->menu = array(
    array(
        'type' => BoosterHelper::TYPE_PRIMARY,
        'label' => Yii::t('employee', 'Create an employee'),
        'url' => $this->createUrl('create'),
    ),
    array(
        'type' => BoosterHelper::TYPE_INFO,
        'label' => Yii::t('employee', 'Get list in excel'),
        'url' => //$this->createUrl('excelList'),
        Yii::app()->createUrl("/hr/default/excelList"),
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

<h1><?php echo Yii::t('employee', 'Employees') ?></h1>



<p>
    <?php
    echo Yii::t('base', 'You may optionally enter a comparison operator (<b>&lt;</b>, <b>&lt;=</b>, <b>&gt;</b>, <b>&gt;=</b>, <b>&lt;&gt;</b> or <b>=</b>) at the beginning of each of your search values to specify how the comparison should be done.');
    
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
        /*array(
            'name' => 'fullName',
            'value' => '$data->getFullName()',
        ),*/

        'last_name',
        'first_name',
        'middle_name',
        array(
            'header' => Yii::t('base', 'Actions'),
            'htmlOptions' => array('nowrap' => 'nowrap'),
            'class' => 'bootstrap.widgets.TbButtonColumn',
            'template' => '{update}{delete}{view}{excel}',
            'buttons' => array(
                    
                    'excel' => array(
                        'label' => Yii::t('employee', 'Create personal card'),
                        'icon' => 'icon-file',
                        'url' => 'Yii::app()->createUrl("/hr/default/excelCard", array("id"=>$data->id))',
                    )
            ),
        ),
    ),
));
Yii::app()->session['arr_id'] = $model->search()->getData();

?>


