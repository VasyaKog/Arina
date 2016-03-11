<?php
/**
 * @var TbActiveForm $form
 * @var \Teacher $model
 */
?>
<?php $form = $this->beginWidget(
    BoosterHelper::FORM,
    array(
        'id' => 'teacher-form',
        'type' => 'horizontal',
        'htmlOptions' => array('class' => 'well span10'),
        'enableAjaxValidation' => true,
    )
);
?>
<?php echo $form->textFieldRow($model, 'last_name'); ?>
<?php echo $form->textFieldRow($model, 'first_name'); ?>
<?php echo $form->textFieldRow($model, 'middle_name'); ?>
<?php if (!$model->isNewRecord) {
    echo $form->textFieldRow($model, 'short_name');
} ?>
<?php echo $form->dropDownListRow($model, 'cyclic_commission_id', CyclicCommission::getList(), array('empty' => Yii::t('teacher', 'Select cycle commission'))); ?>
    <div class="form-actions">
        <?php $this->widget(
            'bootstrap.widgets.TbButton',
            array('buttonType' => 'submit', 'type' => 'primary', 'label' => 'Відправити')
        ); ?>
        <?php $this->widget('bootstrap.widgets.TbButton', array('buttonType' => 'reset', 'label' => 'Сбросить')); ?>
    </div>

<?php $this->endWidget(); ?>