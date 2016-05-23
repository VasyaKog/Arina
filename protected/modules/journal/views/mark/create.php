<?php
/**
 * Created by PhpStorm.
 * User: vasyl
 * Date: 23.04.16
 * Time: 10:55
 */
/**
 * @var $model Mark
 * @var $this MarkController
 * @var $type JournalRecordType
 */

$this->breadcrumbs = array(
    Yii::t('journal','Page of journal')=>array('/journal/default/views/'.$model->journal_record->load_id),
    Yii::t('journal','JournalRecord').':'.$model->journal_record->types->title.' '.$model->journal_record->date=>array('/journal/journalRecord/views','id'=>$model->journal_record->id),
    Yii::t('journal','Mark').":".$model->student->getFullName(),
    Yii::t('base','Create'),
);
$this->renderPartial('_form',array(
    'model'=>$model, 
    'type'=>$type,
    )
);