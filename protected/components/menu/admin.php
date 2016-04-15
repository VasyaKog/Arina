<?php
/**
 *
 * @author Dmytro Karpovych <ZAYEC77@gmail.com>
 */
$menu = array(
    array('label' => Yii::t('base', 'References'), 'items' => array(
        array('label' => Yii::t('base', 'Study years'), 'url' => array('/dictionaries/studyYear')),
        array('label' => Yii::t('base', 'Audiences'), 'url' => array('/audience')),
        array('label' => Yii::t('base', 'Departments'), 'url' => array('/department')),
        array('label' => Yii::t('base', 'Specialities'), 'url' => array('/speciality')),
        array('label' => Yii::t('base', 'Positions'), 'url' => array('/position')),
        array('label' => Yii::t('base', 'Subjects'), 'url' => array('/subject'), 'items' => array(
            array('label' => Yii::t('base', 'List'), 'url' => array('/subject')),
            array('label' => Yii::t('base', 'Cycles'), 'url' => array('/cycle')),
        )),
        array('label' => Yii::t('base', 'Teachers'), 'items' => array(
            array('label' => Yii::t('base', 'List'), 'url' => array('/teacher'),),
            array('label' => Yii::t('base', 'Cyclic commissions'), 'url' => array('/cyclicCommission')),
        )),
    )),
    array('label' => Yii::t('base', 'Groups'), 'url' => array('/group')),
    array('label' => Yii::t('base', 'Study plans'), 'url' => array('/studyPlan')),
    array('label' => Yii::t('base', 'Load'), 'url' => array('/load')),
    array('label' => Yii::t('base', 'Journal'), 'url' => array('/journal')),
    array('label' => Yii::t('base', 'Human resources'), 'items' => array(
        array('label' => Yii::t('employee', 'Employees'), 'url' => array('/hr')),
        array('label' => Yii::t('base', 'Students'), 'url' => array('/student')),
        array('label' => Yii::t('base', 'Students import'), 'url' => array('/import')),
    )),
    array('label' => Yii::t('base', 'File Shares'), 'url' => array('/fileShare')),
    array('label' => Yii::t('base', 'Schedule'), 'items' => array(
        array('label' => Yii::t('base', 'Schedule'), 'url' => array('/schedule')),
        array('label' => Yii::t('student', 'Create document'), 'items' => array(
            array('label' => Yii::t('base', 'Overal'),'url' => array('/site/schedule')),
            array('label' => Yii::t('base', 'For teachers'),'url' => array('/site/scheduleTeachers')),
        )),
        array('label' => Yii::t('base', 'Create a document replacements'), 'url' => array('/site/actualSchedule')),
    )),
    array('label' => Yii::t('base', 'Settings'), 'items' => array(
        array('label' => Yii::t('base', 'Main Settings'), 'url' => array('/settings')),
        array('label' => Yii::t('base', 'Settings benefits'),'url' =>array('/benefits')),
    )),
);