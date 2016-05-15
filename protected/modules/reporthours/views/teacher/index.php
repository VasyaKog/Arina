<?php
/**
 * @var TeacherController $this
 * @var ReportHours $model
 * @var TbActiveForm $form
 */
$this->breadcrumbs = array(
    Yii::t('report', 'Reports'),
);
?>
<div id="report-form">
    <?
$form=$this->beginWidget(BoosterHelper::FORM);
    ?>
    <div class="row">
        <?php
        echo $form->dropDownList($model, 'teacher_id',  Teacher::getList(),
            array('empty'=>Yii::t('report','Choose teacher')));
        ?>
    </div>
    <div class="row buttons">
        <?php $this->widget(
        'bootstrap.widgets.TbButton',
        array(
            'buttonType' => 'submit',
            'type' => 'primary',
            'label' => Yii::t('terms','Open'),
        )); ?>
</div>
</div>
    <?
$this->endWidget();
?>
