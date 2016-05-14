<?php
/**
 * Created by PhpStorm.
 * User: Arhangel
 * Date: 14.05.2016
 * Time: 14:08
 */
Yii::import('modules.load.models.*');
class PresentViewer extends CFormModel
{

    public $groupId;
    public $studyWeekId;
    public $isEmpty = true;
    public $studyYearId;

    public function rules()
    {
        return array(
            array('studyWeekId studyYearId', 'required'),
            array('groupId', 'required', 'on' => 'group'),
            array('studentId', 'required', 'on' => 'student'),
        );
    }

    public function attributeLabels()
    {
        return array(
            'studyWeekId' => Yii::t('present', 'Study Week'),
            'groupId' => Yii::t('terms', 'Group'),
            'studentId' => Yii::t('terms', 'Student'),
            'studyYearId'=>Yii::t('terms','Study year'),
        );
    }
    
    public static function getGroupListByYearId($yearId=null){
        $Groups=Group::getGroupsByYearId($yearId);
        /**
         *
         **/
        $res=array();
        foreach($Groups as $item){
            $res[$item->id]=$item->title;
        }
        return $res;
    }

    /**
     * @var $group Group
     */
    
}