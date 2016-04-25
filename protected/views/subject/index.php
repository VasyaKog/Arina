<?php
/**
 * @var $this \SubjectController
 * @var $dataProvider CActiveDataProvider
 * @var $form TbActiveForm
 * @var $model Subject
 */

$this->breadcrumbs = array(
    Yii::t('base', 'Subjects'),
);

$this->menu = array(
    array(
        'label' => Yii::t('subject', 'Create subject'),
        'url' => array('create'),
        'type' => BoosterHelper::TYPE_PRIMARY
    ),
);
?>

<?php $form = $this->beginWidget(
    BoosterHelper::FORM,
    array('id' => 'subject-form', 'htmlOptions' => array('class' => 'well'), 'method' => 'GET')
); ?>
<?php echo $form->dropDownListRow(
    $model,
    'specialityId',
    CHtml::listData(Speciality::model()->findAll(), 'id', 'title'),
    array('class' => 'span6', 'empty' => '')
); ?>
<?php echo $form->dropDownListRow(
    $model,
    'cycleId',
    CHtml::listData(SubjectCycle::model()->findAll(), 'id', 'title'),
    array('class' => 'span6', 'empty' => '')
); ?>
<div class="form-actions">
    <?php echo TbHtml::submitButton(Yii::t('base', 'Filter'),array('class'=>'btn btn-primary')); ?>
    <?php echo TbHtml::link('Скасувати', $this->createUrl('index'), array('class' => 'btn btn-info')); ?>
</div>
<?php $this->endWidget(); ?>




<h2><?php echo Yii::t('subject', 'Subject list'); ?></h2>

<?php
$columns= array(
    'title',
    'code',
    'short_name',
    array(
        'header' => Yii::t('base', 'Actions'),
        'class' => 'bootstrap.widgets.TbButtonColumn',
        'template' => '{view}{update}{delete}',
    ),
);
$this->renderPartial(
    '//tableList',
    array('columns' => $columns, 'provider' => $dataProvider, 'filter' => $model)
);
?>
