<?php
/**
 * @var ReportController $this
 * @var GroupReport $model_group
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
    echo $form->dropDownList($model_group, 'group_id',  Group::getTreeList(),
        array('empty'=>Yii::t('report','Choose group')));
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