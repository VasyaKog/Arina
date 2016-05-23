<?php
/* @var $this DefaultController */
/* @var $model Employee */
/* @var $form TbactiveForm */
?>

    <div class="form">

        <?php $form = $this->beginWidget(BoosterHelper::FORM, array(
            'id' => 'student-form',

            'enableClientValidation' => true,
        )); ?>

        <p class="note"><?php echo Yii::t('base', 'Fields with <span class="required">*</span> are required.'); ?></p>

        <?php echo $form->errorSummary($model); ?>
        <?php if ($model->isNewRecord): ?>
            <div class="row">
                <div class="span12">
        
                    </div>
                <div id="teacher-fields">
                    <div class="span3">
                        <?php echo $form->textFieldRow($model->user, 'username') ?>
                    </div>
                    <div class="span3">
                        <?php echo $form->passwordFieldRow($model->user, 'password') ?></div>

                </div>
            </div>
        <?php endif ?>
        <div class="row">

            <div class="span3">

                <?php echo $form->checkBoxRow($model, 'participates_in_study_process', array('id' => 'participates_in_study_process')); ?>


                <div id="cyclic_commission" >
                    <?php echo $form->dropDownListRow($model, 'cyclic_commission_id', CyclicCommission::getList(), array('empty' => Yii::t('teacher', 'Select cycle commission'))); ?>
                    <script>
                        $('#cyclic_commission').hide();
                        function checkParticipatesInStudyProcess() {
                            var participates_in_study_process = $('#participates_in_study_process');
                            var cyclic_commission = $('#cyclic_commission');
                            if (participates_in_study_process.prop('checked')) {
                                cyclic_commission.show('slow');
                            } else {
                                cyclic_commission.hide('slow');
                            }
                        }
                        checkParticipatesInStudyProcess();
                        var participates_in_study_process = $('#participates_in_study_process');
                        participates_in_study_process.change(function () {
                            checkParticipatesInStudyProcess();
                        });
                    </script>
                </div>

                <?php echo $form->dateFieldRow($model, 'start_date', array()); ?>

                <?php echo $form->dropDownListRow($model, 'position_id', Position::getListAll('id', 'title'), array('empty' => Yii::t('position', 'Select position'))); ?>

                <?php echo $form->textFieldRow($model, 'last_name', array('size' => 40, 'maxlength' => 40)); ?>

                <?php echo $form->textFieldRow($model, 'first_name', array('size' => 40, 'maxlength' => 40)); ?>

                <?php echo $form->textFieldRow($model, 'middle_name', array('size' => 40, 'maxlength' => 40)); ?>

                <?php if (!$model->getIsNewRecord()) echo $form->textFieldRow($model, 'short_name', array('size' => 40, 'maxlength' => 40)); ?>

                <?php echo $form->dropDownListRow($model, 'gender', array(0 => Yii::t('terms', 'Male'), 1 => Yii::t('terms', 'Female'),), array('empty' => Yii::t('terms', 'Select gender'))); ?>

                <?php echo $form->dateFieldRow($model, 'birth_date', array()); ?>

                <?php echo $form->textFieldRow($model, 'nationality', array('size' => 40, 'maxlength' => 40)); ?>

            </div>

            <div class="span3">

                <?php echo $form->dropDownListRow($model, 'education', EmployeeHelper::getEducationTypes()); ?>

                <?php echo $form->dropDownListRow($model, 'postgraduate_training', EmployeeHelper::getPostgraduateTypes()); ?>

                <?php echo $form->textFieldRow($model, 'last_job', array('size' => 255, 'maxlength' => 255)); ?>

                <?php echo $form->textFieldRow($model, 'last_job_position', array('size' => 40, 'maxlength' => 40)); ?>

                <?php echo $form->numberFieldRow($model, 'experience_years'); ?>

                <?php echo $form->numberFieldRow($model, 'experience_months'); ?>

                <?php echo $form->numberFieldRow($model, 'experience_days'); ?>


                <?php echo $form->dropDownListRow($model, 'dismissal_reason', EmployeeHelper::getDismissalReasons()); ?>

                <?php echo $form->dateFieldRow($model, 'dismissal_date', array()); ?>

            </div>

            <div class="span3">

                <?php echo $form->textFieldRow($model, 'pension_data', array('size' => 255, 'maxlength' => 255)); ?>

                <?php echo $form->textFieldRow($model, 'family_status', array('size' => 255, 'maxlength' => 255)); ?>

                <?php echo $form->textFieldRow($model, 'place_of_residence', array('size' => 255, 'maxlength' => 255)); ?>

                <?php echo $form->textFieldRow($model, 'place_of_registration', array('size' => 255, 'maxlength' => 255)); ?>

                <?php echo $form->textFieldRow($model, 'passport', array('size' => 40, 'maxlength' => '40')); ?>

                <?php echo $form->textFieldRow($model, 'passport_issued_by', array('size' => 40, 'maxlength' => '40')); ?>

                <?php echo $form->dateFieldRow($model, 'passport_issued_date', array()); ?>



            </div>
            <div class="span3">
                <div class="panel panel-primary">
                    <div class="panel-heading">
                        <h6>
                            <?= Yii::t('employee', 'Military information'); ?>
                        </h6>
                    </div>
                    <div class="panel-body">

                        <?php echo $form->textFieldRow($model, 'military_accounting_group', array('size' => 40, 'maxlength' => '40')); ?>

                        <?php echo $form->textFieldRow($model, 'military_accounting_category', array('size' => 60, 'maxlength' => '60')); ?>

                        <?php echo $form->textFieldRow($model, 'military_composition', array('size' => 40, 'maxlength' => '40')); ?>

                        <?php echo $form->textFieldRow($model, 'military_rank', array('size' => 40, 'maxlength' => '40')); ?>

                        <?php echo $form->textFieldRow($model, 'military_accounting_speciality_number', array('size' => 40, 'maxlength' => '40')); ?>

                        <?php echo $form->checkBoxRow($model, 'military_suitability', array()); ?>

                        <?php echo $form->textFieldRow($model, 'military_district_office_registration_name', array('size' => 40, 'maxlength' => '40')); ?>

                        <?php echo $form->textFieldRow($model, 'military_district_office_residence_name', array('size' => 40, 'maxlength' => '40')); ?>

                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="span3">
                <!--educations list control-->
                <?php $this->widget('hr.widgets.TableInput',
                    array(
                        'model' => $model,
                        'name' => 'educations_list',
                        'fields' => array(
                            Yii::t('employee', 'Educational institution'),
                            Yii::t('employee', 'Diploma, certificate, serial number'),
                            Yii::t('employee', 'Graduation year'),
                            Yii::t('employee', 'Profession by the diploma'),
                            Yii::t('employee', 'Diploma qualification'),
                            Yii::t('employee', 'Form of study'),
                        ),
                    )
                ); ?>

            </div>
            <div class="span3">
                <!--postgraduate list control-->
                <?php $this->widget('hr.widgets.TableInput',
                    array(
                        'model' => $model,
                        'name' => 'postgraduate_trainings',
                        'fields' => array(
                            Yii::t('employee', 'Educational institution'),
                            Yii::t('employee', 'Diploma, certificate, serial number'),
                            Yii::t('employee', 'Graduation year'),
                            Yii::t('employee', 'Academic degree, academic title'),
                        ),
                    )
                ); ?>
            </div>
            <div class="span3">
                <!--family states control-->
                <?php $this->widget('hr.widgets.TableInput',
                    array(
                        'model' => $model,
                        'name' => 'family_data',
                        'fields' => array(
                            Yii::t('employee', 'Family members'),
                            Yii::t('employee', 'Full name title'),
                            Yii::t('employee', 'Birth year'),
                        ),
                    )
                ); ?>
            </div>
            <div class="span3">
                <!--professional_education-->
                <?php $this->widget('hr.widgets.TableInput',
                    array(
                        'model' => $model,
                        'name' => 'professional_education',
                        'fields' => array(
                            Yii::t('base', 'Date'),
                            Yii::t('employee', 'Structural unit name'),
                            Yii::t('employee', 'Study period'),
                            Yii::t('employee', 'Type of study'),
                            Yii::t('employee', 'Form of study'),
                            Yii::t('employee', 'Document, that certifies professional training, issued by'),
                        ),
                    )
                ); ?>
            </div>
        </div>
        <div class="row">
            <div class="span3">
                <!--professional_education-->
                <?php $this->widget('hr.widgets.TableInput',
                    array(
                        'model' => $model,
                        'name' => 'appointments_and_transfers',
                        'fields' => array(
                            Yii::t('base', 'Date'),
                            Yii::t('employee', 'Structural unit name') . '(' . Yii::t('base', 'Code') . ')',
                            Yii::t('position', 'Position name'),
                            Yii::t('position', 'Position code'),
                            Yii::t('position', 'Category, salary'),
                            Yii::t('employee', 'Order basis number'),
                        ),
                    )
                ); ?>
            </div>
            <div class="span3">
                <!--Vacations-->
                <?php $this->widget('hr.widgets.TableInput',
                    array(
                        'model' => $model,
                        'name' => 'vacations',
                        'fields' => array(
                            Yii::t('employee', 'Vacation type'),
                            Yii::t('employee', 'Vacation period'),
                            Yii::t('employee', 'Vacation start date'),
                            Yii::t('employee', 'Vacation end date'),
                            Yii::t('employee', 'Order basis number'),
                        ),
                    )
                ); ?>
            </div>
        </div>

    </div><!-- form -->
<?php echo CHtml::submitButton($model->isNewRecord ? Yii::t('base', 'Create') : Yii::t('base', 'Save'), array('class' => 'btn')); ?>

<?php $this->endWidget(); ?>