<?/**
 * пошук по почті
 * <?php echo $form->textFieldRow($model, 'email', array('class' => 'span5', 'maxlength' => 255)); ?>
* <?php echo $form->textFieldRow($model, 'identity_type', array('class' => 'span5')); 
 */
?>


<?php $form = $this->beginWidget('bootstrap.widgets.TbActiveForm', array(
    'action' => Yii::app()->createUrl($this->route),
    'method' => 'get',
)); 
$index = 0;

?>

<?php echo $form->textFieldRow($model, 'id', array('class' => 'span5')); ?>

<?php echo $form->textFieldRow($model, 'username', array('class' => 'span5', 'maxlength' => 255)); ?>


<? echo $form->dropDownListRow($model, 'identity_type', RolesModel::getList(), array('class' => 'span5'));
?>

<?php echo $form->textFieldRow($model, 'identity_id', array('class' => 'span5')); ?>


<?php echo $form->dropDownListRow($model, 'role', Active::getList(), array('class' => 'span5')); ?>




<div class="form-actions">
    <?php $this->widget(
        'bootstrap.widgets.TbButton',
        array(
            'buttonType' => 'submit',
            'type' => 'primary',
            'label' => Yii::t('base', 'Find')
        )
    ); ?>
</div>

<?php $this->endWidget(); ?>
