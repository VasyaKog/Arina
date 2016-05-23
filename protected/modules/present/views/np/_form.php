<?php
/**
 * Created by PhpStorm.
 * User: Arhangel
 * Date: 20.05.2016
 * Time: 9:21
 */

/* @var $this NpController */
/* @var $model Mark */
/* @var $type JournalRecordType*/
/* @var $form CActiveForm */
?>

<div class="form">

    <?php $form=$this->beginWidget(BoosterHelper::FORM, array(
        'id'=>'mark-_form-form',
        // Please note: When you enable ajax validation, make sure the corresponding
        // controller action is handling ajax validation correctly.
        // See class documentation of CActiveForm for details on this,
        // you need to use the performAjaxValidation()-method described there.
        'enableAjaxValidation'=>false,
    )); ?>

    <?php echo $form->errorSummary($model); ?>

    <div class="row">
        <?php echo $form->dropDownListRow(
            $model,
            'comment',
            PresentViewer::getReasons(),
            array('empty'=>Yii::t('present','Select absence reason'))
        ); ?>
    </div>

    <div class="row buttons">
    <?php $this->widget(
        'bootstrap.widgets.TbButton',
        array(
            'buttonType' => 'submit',
            'type' => 'primary',
            'label' => Yii::t('base','Save'),
        )); ?>
    </div>

    <?php $this->endWidget(); ?>

</div><!-- form -->