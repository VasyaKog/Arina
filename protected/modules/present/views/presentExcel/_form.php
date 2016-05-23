<?php
/**
 * Created by PhpStorm.
 * User: Arhangel
 * Date: 16.05.2016
 * Time: 10:07
 */
/* @var $model PresentViewer */
/* @var $this PresentExcelController */
?>
<div id="present-form">
    <?php
    $form = $this->beginWidget(BoosterHelper::FORM);
    echo $form->dropDownListRow(
        $model,
        'reportType',
        PresentViewer::getReportType(),
        array('empty'=>Yii::t('present','Select Report Type'))
    );

    if ($model->reportType == 1) $url = 'present/excelGroup';
    else if ($model->reportType == 2) $url = 'present/excelDepartment';
    else if ($model->reportType == 3) $url = 'present/excelGeneral';
    else $url = 'present/presentExcel';
    ?>


    <div class="row buttons">
        <?php $this->widget(
            'bootstrap.widgets.TbButton',
            array(
                'url' => array('/'.$url),
                'type' => 'primary',
                'label' => Yii::t('present','Select'),
            )); ?>

    </div><?
    $this->endWidget();
    ?>
</div>