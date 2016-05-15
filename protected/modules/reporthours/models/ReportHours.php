<?php
Yii::import('modules.load.models.*');
class ReportHours extends CFormModel
{

    public $group_id;
    public $teacher_id;
    public $month;
    public $years;

    public function rules()
    {
        return array(
            array('teacher_id', 'required', 'on' => 'teacher'),
            array('group_id', 'required', 'on' => 'group'),
            array('month', 'required', 'on' => 'month'),
            array('years', 'required', 'on' => 'year'),
        );
    }
}
?>