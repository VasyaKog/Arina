<?php
/**
 * Created by PhpStorm.
 * User: Arhangel
 * Date: 20.05.2016
 * Time: 9:18
 */

/* @var $this NpController */
/* @var $model Mark*/
/* @var $type JournalRecordType*/

$this->breadcrumbs = array(
    Yii::t('present','Present')=>array('/present/'),
    Yii::t('present','Page of Present').':'.$model->journal_record->types->title.' '.$model->journal_record->date=>array('/present/pagePresent','id'=>$model->journal_record->id),
    Yii::t('present','Pre').":".$model->student->getFullName(),
);

$this->menu=array(array('label'=>Yii::t('base','Update'), 'url'=>array('update', 'id'=>$model->id)));

$this->menu = array(
    array(
        'type' => BoosterHelper::TYPE_PRIMARY,
        'label' => Yii::t('base', 'Update'),
        'url' => array('update', 'id'=>$model->id),
    )
)
?>

<div class="form">
    <?php if($type->present) {
    if($model->present!=0) {?>
    <div class="row">
        <b><?php echo CHtml::encode($model->getAttributeLabel('present')); ?>:</b>
        <?php echo CHtml::button(($model->present)?Yii::t('journal','NP'):' ', array('readonly'=>true)); ?>
        
        <br><br>
        <b><?php echo CHtml::encode($model->getAttributeLabel('comment')); ?>:</b>
        <?php echo CHtml::textField(
            $model->getAttributeLabel('comment'),
            $model->comment, 
            array('readonly'=>true,)
        ); ?>
        <?php }} ?>
    </div>
</div>