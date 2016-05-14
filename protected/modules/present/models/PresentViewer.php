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
    public $subjectId;
    public $isEmpty = true;
    public $studyYearId;

    public function rules()
    {
        return array(
            array('subjectId studyYearId', 'required'),
            array('groupId', 'required', 'on' => 'group'),
            array('studentId', 'required', 'on' => 'student'),
        );
    }

    public function attributeLabels()
    {
        return array(
            'subjectId' => Yii::t('terms', 'Subject'),
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