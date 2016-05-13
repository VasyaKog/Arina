
<?php
/* @var $this JournalStudentsController */
/* @var $model JournalStudents */

$this->breadcrumbs=array(
    Yii::t('journal','Page of journal')=>array('/journal/default/views/'.$model->load_id),
    Yii::t('journal','Journal Students')=>array('/journal/journalStudents/index/'.$model->load_id),
    Yii::t('base','Settings'),
);
?>
<h1><? echo Yii::t('journal','To setting JournalStudents')?></h1>

<?php $this->renderPartial('_form', array('model'=>$model)); ?>