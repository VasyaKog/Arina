<?php
/**
 * @var WorkController $this
 * @var WorkSubject $model
 * @var WorkPlan $plan
 * @var TbActiveForm $form
 */
?>

<style>
    .controls div {
        float: left;
        width: 160px;
    }

    .row {
        margin-right: -50px;
    }

    .line hr {
        border-color: #808080;
        height: 2px;
        width: 82%;
    }

    .semester {
        margin-top: 10px;
    }
</style>
<div>
    <?php $form = $this->beginWidget(BoosterHelper::FORM, array(
            'id' => 'add_subject',
            'htmlOptions' => array('class' => 'well'))
    ); ?>
    <h2>Додання предмету</h2>

    <div class="row">
        <div class="span5">

            <?php echo CHtml::label('Цикл', 'cycle'); ?>
            <?php echo CHtml::dropDownList('cycle', '', SubjectCycle::getList(), array('class' => 'span5',
                'empty'=>'',
                'ajax' => array(
                    'type' => 'GET',
                    'url' => CController::createUrl('/subject/listBySpeciality', array('id' => $plan->speciality_id)), //url to call.
                    'update' => '#WorkSubject_subject_id',
                    'data' => array('cycle_id' => 'js:this.value'),
                ))); ?>
            <?php echo $form->dropDownListRow($model, 'subject_id', array(), array('class' => 'span5')); ?>
        </div>
        <div class="span7">

            <?php echo $form->dropDownListRow($model, 'cyclic_commission_id', CyclicCommission::getList(), array('class' => 'span6')); ?>
        </div>

        <div class="span3">

            <?php echo $form->textFieldRow($model, 'certificate_name', array('class' => 'span3')); ?>
        </div>
        <div class="span3">

            <?php echo $form->textFieldRow($model, 'diploma_name', array('class' => 'span3')); ?>
        </div>
    </div>
    <hr/>
    <div class="row">
        <?php for ($i = 0; $i < 8; $i++): ?>
            <div class="span6 semester">
                <h4><?php echo $i + 1; ?>-й семестр: <?php echo $model->plan->semesters[$i]; ?> тижнів</h4>

                <div class="hours">
                    <div>
                        <?php echo $form->numberField($model, "total[$i]", array('placeholder' => 'Загальна кількість', 'style' => 'width:140px')); ?>
                        <?php echo CHtml::numberField("classes_$i", '', array('placeholder' => 'Аудиторні', 'readonly' => true, 'style' => 'width:140px')); ?>
                        <?php echo $form->numberField($model, "weeks[$i]", array('placeholder' => 'Годин на тиждень', 'style' => 'width:140px')); ?>
                    </div>
                    <div>
                        <?php echo $form->numberField($model, "lectures[$i]", array('placeholder' => 'Лекції', 'style' => 'width:140px')); ?>
                        <?php echo $form->numberField($model, "labs[$i]", array('placeholder' => 'Лабораторні', 'style' => 'width:140px')); ?>
                        <?php echo $form->numberField($model, "practs[$i]", array('placeholder' => 'Практичні', 'style' => 'width:140px')); ?>
                    </div>
                </div>
                <div class="controls">
                    <div>
                        <?php echo $form->checkBoxRow($model, "control[$i][0]", array(), array('label' => 'Залік')); ?>
                        <?php echo $form->checkBoxRow($model, "control[$i][1]", array(), array('label' => 'Екзамен')); ?>
                    </div>
                    <div>
                        <?php echo $form->checkBoxRow($model, "control[$i][2]", array(), array('label' => 'ДПА')); ?>
                        <?php echo $form->checkBoxRow($model, "control[$i][3]", array(), array('label' => 'ДА')); ?>
                    </div>
                    <div>
                        <?php echo $form->checkBoxRow($model, "control[$i][4]", array(), array('label' => 'Курсова робота')); ?>
                        <?php echo $form->checkBoxRow($model, "control[$i][5]", array(), array('label' => 'Курсовий проект')); ?>
                    </div>
                </div>
                <div class="clear"></div>
                <div class="line">
                    <hr/>
                </div>
            </div>
        <?php endfor; ?>
    </div>
    <?php $this->renderPartial('//formButtons', array('model' => $model)); ?>
    <?php $this->endWidget(); ?>
</div>
<script>
    var weeks = [<?php echo implode(', ',$model->plan->semesters);?>];

    function calcClasses() {
        for (i = 0; i < 8; i++) {
            if ($("#WorkSubject_weeks_" + i).val()) {
                $("#classes_" + i).val(weeks[i] * parseInt($("#WorkSubject_weeks_" + i).val()));
            }
        }
    }

    $(function () {
        $("input[id^='WorkSubject_weeks_']").change(function () {
            calcClasses();
        });
    });
</script>
