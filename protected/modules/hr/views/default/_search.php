<?php
/* @var $this DefaultController */
/* @var $model Employee */
/* @var $form TbActiveForm */
?>

<div class="wide form">

    <?php $form = $this->beginWidget(BoosterHelper::FORM, array(
        'action' => Yii::app()->createUrl($this->route),
        'method' => 'get',
        'type' => 'horizontal',
        'htmlOptions' => array('class' => 'well span10'),

    )); ?>

    <?php echo $form->textFieldRow($model, 'last_name', array('size' => 40, 'maxlength' => 40)); ?>

    <?php echo $form->textFieldRow($model, 'first_name', array('size' => 40, 'maxlength' => 40)); ?>

    <?php echo $form->textFieldRow($model, 'middle_name', array('size' => 40, 'maxlength' => 40)); ?>

    <?php echo $form->textFieldRow($model, 'short_name', array('size' => 40, 'maxlength' => 40)); ?>

    <?php echo $form->textFieldRow($model, 'gender', array('size' => 40, 'maxlength' => 40)); ?>

    <?php echo $form->textFieldRow($model, 'position_id', array('size' => 40, 'maxlength' => 40)); ?>    
    
    <?php echo $form->textFieldRow($model, 'birth_date', array('size' => 40, 'maxlength' => 40)); ?>   
    
    <?php echo $form->textFieldRow($model, 'nationality', array('size' => 40, 'maxlength' => 40)); ?>   
    
    <?php echo $form->textFieldRow($model, 'education', array('size' => 40, 'maxlength' => 40)); ?>   
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

</div><!-- search-form -->