<?php
/**
 *
 * @var ReportController $this
 * @var ReportHours $model
 * @var TbActiveForm $form
 */
?>
<?php $form = $this->beginWidget(
    BoosterHelper::FORM,
    array(
        'id' => 'group-form',
        'type' => 'horizontal',
        'htmlOptions' => array('class' => 'well span10'),
        'enableAjaxValidation' => true,
    )
);
?>
<?php echo $form->textFieldRow($model, 'title'); ?>

<?php echo $form->dropDownListRow($model, 'speciality_id', Speciality::getList(Yii::app()->user->identityId), array('empty' => Yii::t('group', 'Select speciality'), 'class' => 'span6')); ?>
<?php
if (!$model->isNewRecord) {
    echo $form->dropDownListRow($model, 'monitor_id', $model->getStudentsList(), array('empty' => Yii::t('group', 'Select prefect'), 'class' => 'span6'));
}
?>
<?php $this->renderPartial('//formButtons', array('model' => $model)); ?>

<?php $this->endWidget(); ?>