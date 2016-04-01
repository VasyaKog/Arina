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
        <?php echo $form->textFieldRow($model, 'code', array('size' => 12, 'maxlength' => 12)); ?>

        <?php echo $form->checkBoxListRow($model, 'exemptions', CHtml::listData(Exemption::model()->findAll(), 'id', 'title'), array('placeholder' => '  ')) ?>

        <?php echo $form->textFieldRow($model, 'last_name', array('size' => 40, 'maxlength' => 40)); ?>

        <?php echo $form->textFieldRow($model, 'first_name', array('size' => 40, 'maxlength' => 40)); ?>

        <?php echo $form->textFieldRow($model, 'middle_name', array('size' => 40, 'maxlength' => 40)); ?>

        <?php echo $form->textFieldRow($model, 'phone_number', array('size' => 15, 'maxlength' => 15)); ?>

        <?php echo $form->textFieldRow($model, 'mobile_number', array('size' => 15, 'maxlength' => 15)); ?>

        <?php echo $form->textFieldRow($model, 'mother_name', array('size' => 60, 'maxlength' => 60)); ?>

        <?php echo $form->textFieldRow($model, 'father_name', array('size' => 60, 'maxlength' => 60)); ?>

        <?php echo $form->textFieldRow($model, 'document', array(/*'size' => 60, 'maxlength' => 60*/)); ?>

        <?php echo $form->textFieldRow($model, 'identification_code', array(/*'size' => 60, 'maxlength' => 60*/)); ?>
    </div>
    <div class="span3">
        <?php echo $form->dropDownListRow($model, 'gender', array(0 => Yii::t('terms', 'Male'), 1 => Yii::t('terms', 'Female'),), array('empty' => Yii::t('terms', 'Select gender'))); ?>

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

        <?php echo $form->checkBoxRow($model, 'contract'); ?>

        <?php echo $form->textFieldRow($model, 'form_of_study_notes', array('size' => 50, 'maxlength' => 50)); ?>

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

        <?php echo $form->textFieldRow($model, 'sseed_id', array('size' => 60, 'maxlength' => 100)); ?>
    </div>
   </div>
    <br>
    <?php echo CHtml::submitButton($model->isNewRecord ? Yii::t('base', 'Create') : Yii::t('base', 'Save')); ?>
</div>
<?php $this->endWidget(); ?>

</div><!-- form -->