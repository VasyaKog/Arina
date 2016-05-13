<?php
Yii::import('modules.load.models.*');
class ReportHours extends CFormModel
{

    public $group_id;
    public $teacher_id;
    public $month;
    public $type_id;

    public function rules()
    {
        return array(
            array('teacher_id', 'required', 'on' => 'teacher'),
            array('group_id', 'required', 'on' => 'group'),
            array('month', 'required', 'on' => 'month'),
            array('type_id', 'required', 'on' => 'type'),
        );
    }

    public function attributeLabels()
    {
        return array(
            'subjectId' => Yii::t('terms', 'Subject'),
            'groupId' => Yii::t('terms', 'Group'),
            'studentId' => Yii::t('terms', 'Student'),
            'studyYearId' => Yii::t('terms', 'Study year'),
        );
    }
}