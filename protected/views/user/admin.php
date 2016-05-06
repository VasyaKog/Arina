<?php
$this->breadcrumbs = array(
    'Користувачі' => array('admin'),
    'Управління',
);

$this->menu = array(
   // array('label' => 'Список користувачів', 'url' => array('index')),
    array('label' => 'Додати користувача', 'url' => array('create')),
);

Yii::app()->clientScript->registerScript('search', "
$('.search-button').click(function(){
$('.search-form').toggle();
return false;
});
$('.search-form form').submit(function(){
$.fn.yiiGridView.update('user-grid', {
data: $(this).serialize()
});
return false;
});
");
?>

<h1>Управління користувачами</h1>

<p>
    Ви можете додатково ввести оператор порівняння (<b>&lt;</b>, <b>&lt;=</b>, <b>&gt;</b>, <b>&gt;=</b>, <b>
        &lt;&gt;</b>
    or <b>=</b>) на початку кожного із значень пошуку, щоб вказати, як порівняння повинно бути зроблено..
</p>

<?php echo CHtml::link('Розширений пошук', '#', array('class' => 'search-button btn')); ?>
<div class="search-form" style="display:none">
    <?php $this->renderPartial('_search', array(
        'model' => $model,
    )); ?>
</div><!-- search-form -->

<?php $this->widget('bootstrap.widgets.TbGridView', array(
    'id' => 'user-grid',
    'dataProvider' => $model->search(),
    'filter' => $model,
    'columns' => array(
        'id',
        'username',        
        'email',        
        array(
            'name' => 'employee.last_name',
            'value' => 'CHtml::link($data->employee->fullname, array("view", "id"=>$data->id))',
            'type' => 'raw'
        ),        
        array(
            'name' => 'roles.title',
            'value' => 'CHtml::link($data->roles["title"], array("view", "id"=>$data->id))',
            'type' => 'raw'
        ),     
        'role',
        array(
            'class' => 'bootstrap.widgets.TbButtonColumn',
        ),
    ),
)); ?>
