<?php
/* @var $this JournalStudentsController */
/* @var $dataProvider CActiveDataProvider */
/* @var $load_id int */

$this->breadcrumbs=array(
    'Journal Students',
);

$this->menu=array(
    array(
        'label'=>'Create JournalStudents',
        'type'=>BoosterHelper::TYPE_PRIMARY,
        'url'=>array('journalStudents/create/'.$load_id),
));
?>

<h1>Journal Students</h1>

<?php
$columns = array(
    array(
        'header'=>Yii::t('studentGroup','Student'),
        'type'=>'raw',
        'value'=>'CHtml::link($data->student->getFullNameAndCode(),array("/student/default/view/".$data->student_id))',
        ),

   array(
        'header'=>Yii::t('studentGroup','Student'),
        'value'=>'$data->date',
        'htmlOptions' => array('style' => 'width: 160px'),
   ),
    array(
        'header'=> Yii::t('studentGroup','Type action'),
        'name'=> 'type',
        'type'=>'raw',
        'value' => 'CHtml::label($data->getTypes(),false)',
        'htmlOptions' => array('style' => 'width: 160px'),
    ),
);
$this->renderPartial('//tableList', array(
    'provider'=>$dataProvider,
    'columns' => $columns,
)); ?>