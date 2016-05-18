<?php
/**
 * Created by PhpStorm.
 * User: Arhangel
 * Date: 14.05.2016
 * Time: 14:08
 */
/**
 * @property integer $id
 */
Yii::import('modules.load.models.*');
class PresentViewer extends CFormModel
{

    public $groupId;
    public $studyMonthId;
    public $isEmpty = true;
    public $studyYearId;

    public function rules()
    {
        return array(
            array('studyMonthId studyYearId', 'required'),
            array('groupId', 'required', 'on' => 'group'),
            array('studentId', 'required', 'on' => 'student'),
        );
    }

    public function attributeLabels()
    {
        return array(
            'studyMonthId' => Yii::t('present', 'Study Month'),
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

    public static function getMonthList()
    {
        return array(
            Yii::t('present','Autumn semester')=>array(
                '9' =>Yii::t('base','September'),
                '10' => Yii::t('base','October'),
                '11' => Yii::t('base','November'),
                '12' => Yii::t('base','December'),
            ),
            Yii::t('present','Spring semester')=>array(
                '1' => Yii::t('base','January'),
                '2' => Yii::t('base','February'),
                '3' => Yii::t('base','March'),
                '4' => Yii::t('base','April'),
                '5' => Yii::t('base','May'),
                '6' => Yii::t('base','June'),
                '7' => Yii::t('base','July'),
                '8' => Yii::t('base','August'),
            ),
        );
    }
}