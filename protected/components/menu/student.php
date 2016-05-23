<?php
/**
 *
 * @author Dmytro Karpovych <ZAYEC77@gmail.com>
 */
$menu = array(
	array('label' => Yii::t('base', 'Home'), 'url' => array('/site/index')),
    array('label' => Yii::t('base', 'Study plans'), 'url' => array('/studyPlan')),
    array('label' => Yii::t('base', 'Load'), 'url' => array('/load')),
    array('label' => Yii::t('base', 'Journal'), 'url' => array('/journal')),
      array('label' => Yii::t('user', 'Mycabinet'), 'url' => array('/user/update/'.Yii::app()->user->id)));