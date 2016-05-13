<?php
Yii::import('modules.load.models.*');
class TeacherReport extends CFormModel
{
    public $teacher_id;

    public function rules()
    {
        return array(
            array('teacher_id', 'required', 'on' => 'teacher'),
        );
    }
}