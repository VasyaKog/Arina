<?php

if (!Yii::app()->user->checkAccess('admin')) {
$this->redirect(array('user/update/'.Yii::app()->user->id));
} 

else $this->redirect(array('admin'));

/*$this->breadcrumbs = array(
    'Користувачі',
);

$this->menu = array(
    array('label' => 'Додати користувача', 'url' => array('create')),
    array('label' => 'Редагувати користувача', 'url' => array('admin')),
);
?>

<h1>Users</h1>

<?php $this->widget('bootstrap.widgets.TbListView', array(
    'dataProvider' => $dataProvider,
    'itemView' => '_view',
));*/ ?>
