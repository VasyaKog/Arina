<?php
/**
 * @var ReportController $this
 * @var ReportHours $model
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
        echo $form->dropDownList($model, 'group_id',  Group::getTreeList(),
            array('empty'=>Yii::t('report','Choose group')));
        ?>
    </div>
    <div class="row">
        <?php
        echo $form->dropDownList($model, 'month',
            array('01'=>Yii::t('month','January'),
                '02'=>Yii::t('month','February'),
                '03'=>Yii::t('month','March'),
                '04'=>Yii::t('month','April'),
                '05'=>Yii::t('month','May'),
                '06'=>Yii::t('month','June'),
                '07'=>Yii::t('month','July'),
                '08'=>Yii::t('month','August'),
                '09'=>Yii::t('month','September'),
                '10'=>Yii::t('month','October'),
                '11'=>Yii::t('month','November'),
                '12'=>Yii::t('month','December'),
            ),
            array('empty'=>Yii::t('report','Choose month')));
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
