<?php
/**
 * Created by PhpStorm.
 * User: vasyl
 * Date: 15.04.16
 * Time: 22:06
 */
/* @var $this DefaultController */
/* @var $id int */
/* @var $student Student */
/* @var $t boolean*/

$this->widget('journal.widgets.PageJournal', array(
    'load_id' => $id,
    't'=>$t,
    'student_view'=>$student,
));
$this->menu=array(
    array(
        'label'=>Yii::t('journal','Setting JournalStudents'),
        'type'=>BoosterHelper::TYPE_PRIMARY,
        'url'=>array('/journal/journalStudents/index/'.$id),
    ),
);
