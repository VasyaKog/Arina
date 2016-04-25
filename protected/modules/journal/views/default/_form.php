<?php
/* @var $model JournalViewer */
/* @var $this DefaultController */
?>
<div id="journal-form">
    <?php
    $form = $this->beginWidget(BoosterHelper::FORM);
    echo $form->dropDownListRow(
        $model,
        'studyYearId',
        StudyYear::getList(),
        array(
            'empty'=>Yii::t('journal','Select Study Year'),
            'ajax'=> array(
                'type'=>'POST',
                'url'=>$this->createUrl('default/changeGroupList'),
                'update'=> '#JournalViewer_groupId',
            ))
    );
    echo $form->dropDownListRow(
        $model,
        'groupId',
        array(),
        array(
            'empty'=>'Select study year',
            'ajax'=> array(
                'type'=>'POST',
                'url'=>$this->createUrl('default/changeSubjectList'),
                'update'=> '#JournalViewer_subjectId',
    ))
    );
    echo $form->dropDownListRow($model, 'subjectId', array(), array('empty' => 'Select group'));
    echo CHtml::ajaxSubmitButton(Yii::t('base', 'Open'), array('index'), array('success' => 'myfunc'));
    $this->endWidget();
    ?>
<!--    --><?php //if (!$model->isEmpty): ?>
<!---->
<!--        --><?php //$data = $model->getData(); ?>
<!--        <div>-->
<!--            <table class="table table-bordered">-->
<!--                <caption>Журнал</caption>-->
<!--                <thead>-->
<!--                <tr>-->
<!--                    <th style="width: 100px">Студенти\Заняття:</th>-->
<!--                    --><?php //foreach ($data['classes'] as $item): ?>
<!--                        <th>--><?php //echo CHtml::link($item->date, $this->createUrl('/actualClass/update/', array('id' => $item->id)), array("target" => "_blank")); ?><!--</th>-->
<!--                    --><?php //endforeach; ?>
<!--                </tr>-->
<!--                </thead>-->
<!--                <tbody>-->
<!--                --><?php ///** @var $item Student */ ?>
<!--                --><?php //foreach ($data['students'] as $item): ?>
<!--                    <tr>-->
<!--                        <td>--><?php //echo $item->getFullName(); ?><!--</td>-->
<!--                        --><?php //foreach ($item->classes as $class): ?>
<!--                            <td>--><?php
//                                if (isset($class['mark']))
//                                    //echo $class->mark;
//                                    echo CHtml::link($class['mark']->mark, $this->createUrl('/classMark/update/', array('id' => $class['mark']->id)), array("target" => "_blank"));
//                                /*$this->widget('bootstrap.TbEditableField', array(
//                                        'type' => 'select2',
//                                        'model' => ClassMark::model(),
//                                        'attribute' => 'mark',
//                                        'url' => '',
//                                        'source' => array('2', '3', '4', '5', '.')
//                                ));*/
//                                else
//                                    echo CHtml::link('___', $this->createUrl('/classMark/createFromJournal/', array('studentId' => $item->id, 'actualClassId' => $class['class'])), array("target" => "_blank"));
//                                if (isset($class['absence']))
//                                    echo CHtml::link(' / Нб', $this->createUrl('/classAbsence/update/', array('id' => $class['absence']->id)), array("target" => "_blank"));
//                                else
//                                    echo CHtml::link(' / ___', $this->createUrl('/classAbsence/createFromJournal/', array('studentId' => $item->id, 'actualClassId' => $class['class'])), array("target" => "_blank"));
//
//
//                                ?><!--</td>-->
<!--                        --><?php //endforeach; ?>
<!--                    </tr>-->
<!--                --><?php //endforeach; ?>
<!--                </tbody>-->
<!--            </table>-->
<!--        </div>-->
<!---->
<!--    --><?php //endif; ?>
</div>