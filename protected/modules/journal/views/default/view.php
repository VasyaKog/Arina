<?php
/**
 * Created by PhpStorm.
 * User: vasyl
 * Date: 15.04.16
 * Time: 22:06
 */
/* @var $this DefaultController */
/* @var $id int */

$this->widget('journal.widgets.PageJournal', array(
    'load_id' => $id,
));
$this->menu=array(
    array(
        'label'=>'Setting list',
        'type'=>BoosterHelper::TYPE_PRIMARY,
        'url'=>array('/journal/journalStudents/index/'.$id),
    ),
);
