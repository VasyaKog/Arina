
<?php
/* @var $this JournalStudentsController */
/* @var $model JournalStudents */

$this->breadcrumbs=array(
    Yii::t('journal','Journal Students')=>array('index'),
    'Create',
);

$this->menu=array(
    array('label'=>Yii::t('journal','List JournalStudents'), 'url'=>array('index')),
);
?>

<h1>Create JournalStudents</h1>

<?php $this->renderPartial('_form', array('model'=>$model)); ?>