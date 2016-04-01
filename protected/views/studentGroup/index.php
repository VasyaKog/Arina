<?php
/* @var $this StudentGroupController */
/* @var $dataProvider CActiveDataProvider */

$this->breadcrumbs=array(
	Yii::t('studentGroup','Student Groups'),
);

$this->menu=array(
	array(
		'type'=>'primary',
		'label'=>Yii::t('studentGroup','Create StudentGroup'),
		'url'=>array('create'),
	),
);
?>

<h1><?php echo Yii::t('studentGroup','Migration of students');?></h1>

<?php
$columns= array(
	array(
		'header'=> Yii::t('studentGroup','Student'),
		'type' => 'raw',
		'name'=> 'student.name',
		'value' => 'CHtml::link($data->student->getFullNameAndCode(),array("student/default/view/","id"=>$data->student_id))',
		'htmlOptions' => array('style' => 'width: 160px'),
	),
	array(
		'name' =>'date',

		'htmlOptions' => array('style' => 'width: 160px'),
	),
	array(
		'header'=> Yii::t('studentGroup','Group'),
		'type' => 'raw',
		'name'=> 'group.name',
		'value' => 'CHtml::link($data->group->title,array("group/view","id"=>$data->group_id))',
		'htmlOptions' => array('style' => 'width: 160px'),
	),
	array(
		'header'=> Yii::t('studentGroup','Type action'),
		'type' => 'raw',
		'name'=> 'type',
		'value' => 'CHtml::label($data->getTypes(),false)',
		'htmlOptions' => array('style' => 'width: 160px'),
	),
	array(
		'name' =>'comment',

		'htmlOptions' => array('style' => 'width: 160px'),
	),
	array(
		'header' => Yii::t('base', 'Actions'),
		'htmlOptions' => array('nowrap' => 'nowrap'),
		'class' => 'bootstrap.widgets.TbButtonColumn',
		'template' => '{update}{allmigration}{delete}{view}',
	)

);

$this->renderPartial('//tableList',
	array(
		'provider' => $dataProvider,
		'columns' => $columns,
	)
);
?>
