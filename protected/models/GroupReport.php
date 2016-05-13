<?php
Yii::import('modules.load.models.*');
class GroupReport extends CFormModel
{
    public $group_id;

    public function rules()
    {
        return array(
            array('group_id', 'required', 'on' => 'group'),
        );
    }
}