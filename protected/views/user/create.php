<?php
/**
 * @var User $model
 * @var UserController $this
 */
$this->breadcrumbs = array(
    'Користувачі' => array('admin'),
    'Додати користувача',
);

$this->menu = array(
    array('label' => 'Список користувачів', 'url' => array('user/admin')),
    array('label' => 'Редагувати користувача', 'url' => array('admin')),
);
?>

    <h1>Додати користувача</h1>

<?php echo $this->renderPartial('_form', array('model' => $model)); ?>