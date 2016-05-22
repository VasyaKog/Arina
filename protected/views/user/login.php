<?php
/**
 * @var UserController $this
 * @var TbActiveForm $form
 * @var ELoginForm $model
 * @author Dmytro Karpovych <ZAYEC77@gmail.com>
 */

$this->name = Yii::t('base', 'Log in');
?>

<div class="span5 offset3 text-center">
    <h2><?php echo Yii::t('base', 'Log in') ?></h2>

    <?php
    $form = $this->beginWidget(
        'bootstrap.widgets.TbActiveForm',
        array(
            'id' => 'login-form',
            'type' => 'vertical',
            'htmlOptions' => array('class' => 'well'),
            'enableAjaxValidation' => true,
        )
    ); ?>
    <?php echo $form->textFieldRow($model, 'username'); ?>
    <?php echo $form->passwordFieldRow($model, 'password'); ?>
    <?php echo $form->toggleButtonRow($model, 'rememberMe'); ?>
    <div class="form-actions">
        <?php $this->widget(
            'bootstrap.widgets.TbButtonGroup',
            array(
                'buttons' => array(
                    //array('url' => array('/user/restore'), 'label' => Yii::t('base', 'Restore password'), 'icon' => 'icon-repeat'),
                    array('buttonType' => 'submit', 'type' => 'primary', 'label' => Yii::t('base', 'Log in'), 'icon' => 'icon-ok'),
                    array('url' => array('/site/index'), 'label' => Yii::t('base', 'Home'), 'icon' => 'icon-align-justify'),
                ),
            )
        ); ?>
    </div>
    <?php $this->endWidget(); ?>
</div>