<?php
/* @var $this StudentController */
/* @var $model Student */
/* @var $form TbActiveForm */
?>

<div class="form">

    <?php $form = $this->beginWidget(BoosterHelper::FORM, array(
        'id' => 'student-form',

        'enableClientValidation' => true,
    )); ?>

    <p class="note"><?php echo Yii::t('base', 'Fields with <span class="required">*</span> are required.'); ?></p>

    <?php echo $form->errorSummary($model); ?>
    <div class="row">
        <div class="span3">




            <?php echo $form->dropDownListRow($model, 'education', EducationHelper::getEducationTypes()); ?>

        </div>
        <div class="span3">

            <?php echo $form->textFieldRow($model, 'official_address', array('size' => 60, 'maxlength' => 200)); ?>

            <?php echo $form->textAreaRow($model, 'characteristics', array('rows' => 7, 'cols' => 50)); ?>

            <?php echo $form->textFieldRow($model, 'factual_address', array('size' => 60, 'maxlength' => 100)); ?>

            <?php echo $form->datepickerRow($model, 'birth_date'); ?>

            <?php echo $form->datepickerRow($model, 'admission_date'); ?>

            <?php echo $form->textFieldRow($model, 'tuition_payment', array('size' => 50, 'maxlength' => 50)); ?>

            <?php echo $form->textFieldRow($model, 'admission_order_number'); ?>

            <?php echo $form->textFieldRow($model, 'admission_semester'); ?>
        </div>
        <div class="span3">
            <?php echo $form->textFieldRow($model, 'entry_exams', array('size' => 60, 'maxlength' => 100)); ?>

            <?php echo $form->textFieldRow($model, 'education_document', array('size' => 50, 'maxlength' => 50)); ?>

            <?php echo $form->textFieldRow($model, 'contract', array('size' => 50, 'maxlength' => 50)); ?>

            <?php echo $form->textFieldRow($model, 'math_mark'); ?>

            <?php echo $form->textFieldRow($model, 'ua_language_mark'); ?>

            <?php echo $form->textFieldRow($model, 'mother_workplace', array('size' => 50, 'maxlength' => 50)); ?>

            <?php echo $form->textFieldRow($model, 'mother_position', array('size' => 50, 'maxlength' => 50)); ?>

            <?php echo $form->textFieldRow($model, 'mother_workphone', array('size' => 20, 'maxlength' => 20)); ?>

            <?php echo $form->textFieldRow($model, 'mother_boss_workphone', array('size' => 20, 'maxlength' => 20)); ?>

            <?php echo $form->textFieldRow($model, 'father_workplace', array('size' => 50, 'maxlength' => 50)); ?>

            <?php echo $form->textFieldRow($model, 'father_position', array('size' => 50, 'maxlength' => 50)); ?>
        </div>
        <div class="span3">
            <?php echo $form->textFieldRow($model, 'father_workphone', array('size' => 20, 'maxlength' => 20)); ?>

            <?php echo $form->textFieldRow($model, 'father_boss_workphone', array('size' => 20, 'maxlength' => 20)); ?>

            <?php echo $form->toggleButtonRow($model, 'graduated'); ?>

            <?php echo $form->datepickerRow($model, 'graduation_date'); ?>

            <?php echo $form->textFieldRow($model, 'graduation_basis', array('size' => 50, 'maxlength' => 50)); ?>

            <?php echo $form->textFieldRow($model, 'graduation_semester'); ?>

            <?php echo $form->textFieldRow($model, 'graduation_order_number'); ?>

            <?php echo $form->textFieldRow($model, 'diploma', array('size' => 50, 'maxlength' => 50)); ?>

            <?php echo $form->textFieldRow($model, 'direction', array('size' => 50, 'maxlength' => 50)); ?>

            <?php echo $form->textFieldRow($model, 'misc_data', array('size' => 60, 'maxlength' => 100)); ?>

            <?php echo $form->textFieldRow($model, 'hobby', array('size' => 60, 'maxlength' => 100)); ?>
        </div>
    </div>
    <br>
    <?php echo CHtml::submitButton($model->isNewRecord ? Yii::t('base', 'Create') : Yii::t('base', 'Save')); ?>
</div>
<?php $this->endWidget(); ?>

</div><!-- form -->