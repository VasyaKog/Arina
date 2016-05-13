<?php
/** @var $this SiteController */
?>
<div>
    <?= CHtml::beginForm() ?>
    <?= CHtml::dropDownList('year', $data['year'], CHtml::listData(StudyYear::model()->findAll(), 'id', 'title')) ?>
    <?= CHtml::dropDownList('semester', $data['semester'], ['fill' => 'Осінній', 'spring' => 'Весняний']) ?>
    <br/>
    <?= CHtml::submitButton('Генерувати', array('class' => 'btn btn-primary')) ?>
    <?= CHtml::endForm() ?>

</div>