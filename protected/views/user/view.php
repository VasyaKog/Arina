<?php
$this->breadcrumbs = array(
    'Users' => array('index'),
    $model->id,
);

$this->menu = array(
    array('label' => 'Список користувачів', 'url' => array('index')),
    //array('label' => 'Створити користувача', 'url' => array('create')),
    array('label' => 'Редагувати користвуача', 'url' => array('update', 'id' => $model->id)),
    array('label' => 'Видалити користувача', 'url' => '#', 'linkOptions' => array('submit' => array('delete', 'id' => $model->id), 'confirm' => 'Ви впевнені, що хочете видалити цього користвуача?')),
    //array('label' => 'Список користувачів', 'url' => array('admin')),
);
?>

<h1>View User #<?php echo $model->id; ?></h1>

<?php $this->widget('bootstrap.widgets.TbDetailView', array(
    'data' => $model,
    'attributes' => array(
        'id',
        'username',        
        //'email',
        'roles.title',
        array(
            'name'=>'Ініціали користувача',
            'value'=> $model->getName(),
            ),
       
        'active.name')    
        )); ?>
