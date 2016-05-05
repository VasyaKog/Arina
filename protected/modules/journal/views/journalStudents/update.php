<?php
/* @var $this JournalStudentsController */
/* @var $model JournalStudents */

$this->breadcrumbs=array(
    'Journal Students'=>array('index'),
    $model->id=>array('view','id'=>$model->id),
    'Update',
);

$this->menu=array(
    array('label'=>'List JournalStudents', 'url'=>array('index')),
    array('label'=>'Create JournalStudents', 'url'=>array('create')),
);
?>

<h1>Update JournalStudents <?php echo $model->id; ?></h1>

<?php $this->renderPartial('_form', array('model'=>$model)); ?>