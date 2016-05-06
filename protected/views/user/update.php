<?php
/**
 * @var $model User
 * @var $this UserController
 */

$this->breadcrumbs = array(
    'Користувачі' => array('index'),
    $model->username => array('view', 'id' => $model->id),
    'Редагування даних',
);

if (Yii::app()->user->checkAccess('admin')) {
    $this->menu = array(
        array('label' => 'Список користувачів', 'url' => array('index')),
        array('label' => 'Додати користувача', 'url' => array('create')),
        array('label' => 'Переглянути користувача', 'url' => array('view', 'id' => $model->id)),
        array('label' => 'Редагувати користувача', 'url' => array('admin')),
    );
}

?>

    <h1>Редагування користувача <?php echo $model->username; ?></h1>

<?php echo $this->renderPartial('_form', array('model' => $model)); ?>