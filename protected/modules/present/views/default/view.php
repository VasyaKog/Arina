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
/* @var $month string*/
$this->breadcrumbs = array(
    Yii::t('present', 'Present')=>array('/present/'),
    Yii::t('present', 'Page of Present'),
);

$this->widget('present.widgets.PagePresent', array(
    'load_id' => $id,
    't' => $t,
    'month' => $month,
));
