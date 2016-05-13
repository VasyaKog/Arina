<?php
/* @var $this MarkController */
/* @var $model Mark*/
/* @var $type JournalRecordType*/

$this->breadcrumbs = array(
    Yii::t('journal','Page of journal')=>array('/journal/default/views/'.$model->journal_record->load_id),
    Yii::t('journal','JournalRecord').':'.$model->journal_record->types->title.' '.$model->journal_record->date=>array('/journal/journalRecord/views','id'=>$model->journal_record->id),
    Yii::t('journal','Mark').":".$model->student->getFullName(),
);
$this->menu=array(
    array('label'=>Yii::t('journal','Update'), 'url'=>array('update', 'id'=>$model->id)),
    array('label'=>Yii::t('journal','Delete'), 'url'=>array('delete', 'id'=>$model->id), 'linkOptions'=>array('submit'=>array('delete','id'=>$model->id),'confirm'=>'Are you sure you want to delete this item?')),
);
?>
<div class="form">
<?php if($type->present) {
if($model->present!=0) {?>
    <div class="row">
<b><?php echo CHtml::encode($model->getAttributeLabel('present')); ?>:</b>
<?php echo CHtml::button(
    ($model->present)?Yii::t('journal','NP'):' ',
    array(
        'readonly'=>true,
    )
); }}?>
<br/>


        </div>
    <?php if($model->value_id!=0) {?>
    <div class="row">
        <b><?php echo CHtml::encode($model->getAttributeLabel('system_id')); ?>:</b>
        <?php echo CHtml::textField(
            $model->getAttributeLabel('system_id'),
            $model->system->title,
            array(
                'readonly'=>true,
            )
        ); ?>
        <br/>
        <?}?>
    </div>
    <?php if($model->value_id!=0) {?>
<div class="row">
<b><?php echo CHtml::encode($model->getAttributeLabel('value_id')); ?>:</b>
<?php echo CHtml::textField(
    $model->getAttributeLabel('value_id'),
    Evaluation::model()->findByPk($model->value_id)->title,
   array(
       'readonly'=>true,
   )
); ?>
<br/>
    </div>
    <?}?>
    <?php if($model->retake_value_id!=0) {?>
    <div class="row">
        <b><?php echo CHtml::encode($model->getAttributeLabel('retake_value_id')); ?>:</b>
        <?php echo CHtml::textField(
            $model->getAttributeLabel('retake_value_id'),
            Evaluation::model()->findByPk($model->retake_value_id)->title,
            array(
                'readonly'=>true,
            )
        ); ?>

        <br/>
    </div>
    <?}?>
    <?php if($type->ticket) {
    if(!is_null($model->ticket_numb)) {?>
    <div class="row">
        <b><?php echo CHtml::encode($model->getAttributeLabel('ticket_numb')); ?>:</b>
        <?php echo CHtml::textField(
            $model->getAttributeLabel('ticket_numb'),
            $model->ticket_numb,
            array(
                'readonly'=>true,
            )
        );?>
        </div>
        <?
        }?>
        <?
        if(!is_null($model->retake_ticket_numb)) {?>
        <div class="row">
            <b><?php echo CHtml::encode($model->getAttributeLabel('retake_ticket_numb')); ?>:</b>
            <?php echo CHtml::textField(
                $model->getAttributeLabel('retake_ticket_numb'),
                $model->retake_ticket_numb,
                array(
                    'readonly'=>true,
                )
            );?>
        </div>
        <?
    }
    }?>
    </div>