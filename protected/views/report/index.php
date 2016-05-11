<?php
/**
 * @var ReportController $this
 * @var Teacher $model
 */
$this->breadcrumbs = array(
    Yii::t('group', 'Groups'),
);
$this->menu = array(
    array(
        'type' => BoosterHelper::TYPE_PRIMARY,
        'label' => 'download',
        'url' => $this->createUrl('MakeGroupList'),
    ),
);
echo CHtml::dropDownList('listname', 'empty',  array('sdss'=>'afsafss'), array('empty' => '(Select a teacher)'));
echo CHtml::dropDownList('listname', 'empty',  Teacher::getList(), array('empty' => '(Select a teacher)'));
?>