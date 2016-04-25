<?php
Yii::import('modules.load.models.*');
class JournalViewer extends CFormModel
{

    public $groupId;
    public $subjectId;
    public $isEmpty = true;
    public $studyYearId;

    public function rules()
    {
        return array(
            array('subjectId', 'required'),
            array('groupId', 'required', 'on' => 'group'),
            array('studyYearId','required'),
            array('studentId', 'required', 'on' => 'student'),
        );
    }

    public function attributeLabels()
    {
        return array(
            'subjectId' => Yii::t('terms', 'Subject'),
            'groupId' => Yii::t('terms', 'Group'),
            'studentId' => Yii::t('terms', 'Student'),
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

   /* public function getData()
    {
        switch ($this->scenario) {
            case 'student' :
                return $this->getStudentData();
            case 'group' :
                return $this->getGroupData();
            default :
                return array();
        }
    }

    protected function getStudentData()
    {
        return array();
    }

    protected function getGroupData()
    {
    	/*
        $criteria = new CDbCriteria(array(
            'with' => array(
                'marks',
                'absences',
                'load' => array('with' => array(
                    'teacher',
                    'group' => array('with' => array('students')),
                    'studyPlanSemester' => array('with' => array(
                        'plan' => array('with' => array(
                            'spSubjects' => array('with' => array('subject')))),
                    )
                    )
                )
                ),
            ),
        ));
        */
        /**
         * @var $group Group
         */
        /*group = Group::model()->findByPk($this->groupId);
        
        $classes = ActualClass::model()->with(array(
                'load' => array(
                    'condition' => "load.group_id=" . $this->groupId,
                ),
            )
        )->findAll();

        /**
         * @var $student Student
         */
       /* foreach ($group->students as $student) {
            foreach($classes as $item) {
                $tmp = array();
                foreach($student->marks as $mark) {
                    if ($mark->actual_class_id == $item->id) { $tmp['mark'] = $mark; break; }
                }
                foreach($student->absences as $absence) {
                    if ($absence->actual_class_id == $item->id) { $tmp['absence'] = $absence; break; }
                }
                $tmp['class'] = $item->id;
                if (isset($tmp))
                    $student->classes []= $tmp;
                else
                    $student->classes []= '';
            }
        }
        $list = array(
            'classes' => $classes,
            'students' => $group->students,
        );
        return $list;
    }
       */

}