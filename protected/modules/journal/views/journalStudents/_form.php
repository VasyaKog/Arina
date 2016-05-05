<?php
/* @var $this JournalStudentsController */
/* @var $model JournalStudents */
/* @var $form CActiveForm */
/* @var $load Load*/
?>

<div class="form">

    <?php $form=$this->beginWidget(BoosterHelper::FORM, array(
        'id'=>'journal-students-form',
        // Please note: When you enable ajax validation, make sure the corresponding
        // controller action is handling ajax validation correctly.
        // There is a call to performAjaxValidation() commented in generated controller code.
        // See class documentation of CActiveForm for details on this.
        'enableAjaxValidation'=>false,
    )); ?>

    <p class="note">Fields with <span class="required">*</span> are required.</p>

    <?php echo $form->errorSummary($model); ?>
    <?php echo $form->hiddenField(
        $model,
        'load_id',
        array(
            'type'=>"hidden",
        )
        );?>
    <?php echo $form->dropDownListRow(
        $model,
        'type',
        array(
            0 => Yii::t('terms','exclude'),
            1 => Yii::t('terms','included'),
            ),
        array(
            'empty' => Yii::t('terms', 'Select type'),
            'ajax' => array(
                'type'=>'POST',
                'url'=>$this->createUrl('changeStudentList'),
                'update'=>'#JournalStudents_student_id'
            ),
        )
    );?>
        <?php echo $form->dropDownListRow(
            $model,
            'student_id',
            Array(),
            array(
                'empty'=>Yii::t('journal','Select type'),
                'size'=>0,
                'maxlength'=>20)
        ); ?>

    <div class="row">

    </div>

    <div class="row buttons">
        <?php echo CHtml::submitButton($model->isNewRecord ? 'Create' : 'Save'); ?>
    </div>

    <?php $this->endWidget(); ?>

</div>