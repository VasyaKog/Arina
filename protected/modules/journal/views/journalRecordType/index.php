<?php
/* @var $this JournalRecordTypeController */
/* @var $dataProvider CActiveDataProvider */

$this->breadcrumbs=array(
	Yii::t('journal','Journal')=>$this->createUrl('/journal'),
	Yii::t('journal','Journal Record Types'),
);

$this->menu=array(
	array(
		'label'=>Yii::t('journal','Create JournalRecordType'),
		'url'=>array('create'),
		'type'=>BoosterHelper::TYPE_PRIMARY),
);
?>

<h1><?php echo Yii::t('journal','Journal Record Types');?></h1>

<?php
$columns =array(
	'title',
	array(
		'header'=>Yii::t('journal','Date'),
		'value'=>'JournalRecordType::getName($data->date)',
	),
	array(
		'header'=>Yii::t('journal','Description present'),
		'value'=>'JournalRecordType::getName($data->description)',
	),
	array(
		'header'=>Yii::t('journal','Homework present'),
		'value'=>'JournalRecordType::getName($data->homework)',
	),
	array(
		'header'=>Yii::t('journal','not present'),
		'value'=>'JournalRecordType::getName($data->present)',
	),
	array(
		'header'=>Yii::t('journal','N Pp'),
		'value'=>'JournalRecordType::getName($data->n_pp)'
	),
	array(
		'header'=>Yii::t('journal','Ticket'),
		'value'=>'JournalRecordType::getName($data->ticket)'
	),
	array(
		'header'=>Yii::t('journal','Reports'),
		'value'=>'JournalRecordType::getName($data->reports)'
	),
	array(
		'header' => Yii::t('base', 'Actions'),
		'class' => 'bootstrap.widgets.TbButtonColumn',
		'template' => '{update}{delete}',
	),
);


$this->renderPartial('//tableList', array(
	'provider'=>$dataProvider,
	'columns'=>$columns,
)); ?>
