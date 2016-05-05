<?php
/* @var $this StudentController */
/* @var $model Student */

$this->breadcrumbs = array(
    Yii::t("student", "Students") => array('index'),
    $model->id,
);

$this->widget(
    BoosterHelper::BUTTON_GROUP,
    array(
        'buttons' => array(
            array(
                'type' => BoosterHelper::TYPE_PRIMARY,
                'label' => Yii::t('student', 'Students list'),
                'url' => $this->createUrl('index'),
            ),
            array(
                'type' => BoosterHelper::TYPE_PRIMARY,
                'label' => Yii::t('student', 'Create Student'),
                'url' => $this->createUrl('create'),
            ),
            array(
                'type' => BoosterHelper::TYPE_PRIMARY,
                'label' => Yii::t('student', 'Update Student'),
                'url' => $this->createUrl('update', array('id' => $model->id)),
            ),
            array(
                'type' => BoosterHelper::TYPE_PRIMARY,
                'label' => Yii::t('student', 'Delete Student'),
                'url' => $this->createUrl('delete'),
                'icon' => 'icon-remove-sign',

                "htmlOptions" => array(
                    "submit" => array(
                        'delete',
                        'id' => $model->id,
                    ),
                    "confirm" => Yii::t('student', "Do you want to delete this item?"),
                ),
            ),
        ),
    )
);

?>

<h2><?php echo $model->getFullName(); ?></h2>

<?php $this->widget('booster.widgets.TbDetailView', array(
    'data' => $model,
    'attributes' => array(
        array(
            'name' => 'group',
            'type' => 'raw',
            'value' => $model->getGroupListLinks(),
        ),
        array(
            'name' => 'group_history',
            'type' => 'raw',
            'value' => $model->getGroupHistory(),
        ),
        'code',
        'exemptionNames',
        'phone_number',
        'mobile_number',
        'mother_name',
        'father_name',
        array(
            'name' => 'gender',
            'value' => $model->getGenderName(),
        ),
        'document',
        'identification_code',
        'official_address',
        'characteristics',
        'factual_address',
        array(
            'name' => 'birth_date',
            'value' => Yii::app()->getDateFormatter()->format('dd MMM y', $model->birth_date)
        ),
        array(
            'name' => 'admission_date',
            'value' => Yii::app()->getDateFormatter()->format('dd MMM y', $model->admission_date)
        ),
        'tuition_payment',
        'admission_order_number',
        'admission_semester',
        'entry_exams',
        'education_document',
        'contract',
        'math_mark',
        'ua_language_mark',
        'mother_workplace',
        'mother_position',
        'mother_workphone',
        'mother_boss_workphone',
        'father_workplace',
        'father_position',
        'father_workphone',
        'father_boss_workphone',
        'graduated',
        array(
            'name' => 'graduation_date',
            'value' => (($model->graduation_date != NULL && (strtotime($model->graduation_date) != strtotime('0000-00-00')) ) ? Yii::app()->getDateFormatter()->format('dd.MMM y', $model->graduation_date) : '')
        ),
        'graduation_basis',
        'graduation_semester',
        'graduation_order_number',
        'diploma',
        'direction',
        'misc_data',
        'hobby',
        'sseed_id',
    ),
)); ?>
