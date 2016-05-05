<?php
/* @var $this BenefitsController */
/* @var $dataProvider CActiveDataProvider */

$this->breadcrumbs=array(
	Yii::t('benefits','List Benefits'),
);

$this->menu=array(
	array(
		'type' => 'primary',
		'label' => Yii::t('benefits', 'Add new benefits'),
		'url' => $this->createUrl('create'),
	),
);
?>

<h2><?php echo Yii::t('benefits', 'Benefits list') ?></h2>

<?php
$columns = array(
	array(
		'name' => 'title',
		//		'header'=> Yii::t('benefits','name'),
		'htmlOptions' => array('style' => 'width: 160px'),
	),
/*	array(
		'name' => 'first_name',
		'htmlOptions' => array('style' => 'width: 160px'),
	),
	array(
		'name' => 'middle_name',
		'htmlOptions' => array('style' => 'width: 160px'),
	),
	array(
		'type' => 'raw',
		'name' => 'cyclic_commission_id',
		'value' => 'CHtml::link($data->cyclicCommission->title, array("cyclicCommission/view","id"=>$data->cyclic_commission_id))',
		'htmlOptions' => array(),
		'filter' => CHtml::listData(CyclicCommission::model()->findAll(), 'id', 'title')
	),
*/
	array(
		'header' => Yii::t('base', 'Actions'),
		'htmlOptions' => array('nowrap' => 'nowrap'),
		'class' => 'bootstrap.widgets.TbButtonColumn',
		'template' => '{update}{delete}{view}',
	)

);

$this->renderPartial('//tableList',
	array(
		'provider' => $dataProvider,
		'columns' => $columns,
		//'filter' => $model,
	)
);
