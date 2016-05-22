<?php
/**
 * Created by PhpStorm.
 * User: Arhangel
 * Date: 21.05.2016
 * Time: 17:31
 */

/* @var $this PresentExcelController */
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