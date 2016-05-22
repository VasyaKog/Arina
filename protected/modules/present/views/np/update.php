<?php
/**
 * Created by PhpStorm.
 * User: Arhangel
 * Date: 20.05.2016
 * Time: 9:21
 */

/**
 * @var $model Mark
 * @var $this NpController
 * @var $type JournalRecordType
 */

$this->breadcrumbs = array(
    Yii::t('present','Present')=>array('/present/'),
    Yii::t('journal','JournalRecord').':'.$model->journal_record->types->title.' '.$model->journal_record->date=>array('/journal/journalRecord/views','id'=>$model->journal_record->id),
    Yii::t('present','Pre').":".$model->student->getFullName()=>array('views','id'=>$model->id),
    Yii::t('base','Update'),
);
?>
<?php $this->renderPartial('_form', array('model'=>$model,
    'type'=>$type,)); ?>