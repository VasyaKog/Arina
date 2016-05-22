<?php
/**
 *
 * @author Dmytro Karpovych <ZAYEC77@gmail.com>
 */
$menu = array(   
		array('label' => Yii::t('base', 'Home'), 'url' => array('/site/index')), 
        array('label' => Yii::t('employee', 'Employees'), 'url' => array('/hr')),
        array('label' => Yii::t('base', 'Students'), 'url' => array('/student')),
        //array('label' => Yii::t('base', 'Students import'), 'url' => array('/import')),
           array('label' => Yii::t('user', 'Mycabinet'), 'url' => array('/user/update/'.Yii::app()->user->id))
        );