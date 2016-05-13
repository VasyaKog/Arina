<?php
/** @var $this SiteController */
?>
<div>
    <?= CHtml::beginForm() ?>
    <?= CHtml::dateField('date', date("Y-m-d",time() + 86400)) ?>
    <br/>
    <?= CHtml::submitButton('Генерувати', array('class' => 'btn btn-primary')) ?>
    <?= CHtml::endForm() ?>

</div>