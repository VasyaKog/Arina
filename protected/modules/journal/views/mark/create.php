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

$this->breadcrumbs=array(
    'Mark'=>array('index'),
    $model->id,

);

$this->renderPartial('_form',array(
    'model'=>$model,
        'type'=>$type,
    )
);