<?php
/**
 * Created by PhpStorm.
 * User: Arhangel
 * Date: 15.05.2016
 * Time: 0:28
 */
/* @var $this DefaultController */
/* @var $id int */
/* @var $t boolean*/

$this->widget('present.widgets.PagePresent', array(
    'load_id' => $id,
    't'=>$t,
));
$this->menu=array(
    array(
        'label'=>Yii::t('present','Setting PresentStudents'),
        'type'=>BoosterHelper::TYPE_PRIMARY,
        'url'=>array('/present/presentStudents/index/'.$id),
    ),
);