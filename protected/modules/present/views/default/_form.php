<?php
/* @var $model PresentViewer */
/* @var $this DefaultController */
?>
<div id="present-form">
    <?php
    $form = $this->beginWidget(BoosterHelper::FORM);
    echo $form->dropDownListRow(
        $model,
        'studyYearId',
        StudyYear::getList(),
        array(
            'empty'=>Yii::t('present','Select Study Year'),
            'ajax'=> array(
                'type'=>'POST',
                'url'=>$this->createUrl('default/changeGroupList'),
                'update'=> '#PresentViewer_groupId',
            ))
    );
    echo $form->dropDownListRow(
        $model,
        'groupId',
        array(),
        array(
            'empty'=>Yii::t('present','Select Study Year'),
            'ajax'=> array(
                'type'=>'POST',
                'url'=>$this->createUrl('default/changeSubjectList'),
                'update'=> '#PresentViewer_subjectId',
    ))
    );
    echo $form->dropDownListRow(
        $model,
        'studyWeekId',
        array(),
        array('empty' => Yii::t('present','Select Study Week')));
    ?>  
    <div class="row buttons">
        <?php $this->widget(
            'bootstrap.widgets.TbButton',
            array(
                'buttonType' => 'submit',
                'type' => 'primary',
                'label' => Yii::t('terms','Open'),
                )); ?>

    </div><?
    $this->endWidget();
    ?>
</div>