<?php

/**
 * This is the model class for table "group".
 *
 * The followings are the available columns in table 'group':
 * @property integer $id
 * @property string $title
 * @property integer $speciality_id
 * @property integer $monitor_id
 *
 * @property Student[] $students
 * @property Speciality $speciality
 */
class Group extends ActiveRecord
{
    /**
     * Returns the static model of the specified AR class.
     * Please note that you should have this exact method in all your CActiveRecord descendants!
     * @param string $className active record class name.
     * @return Group the static model class
     */
    public static function model($className = __CLASS__)
    {
        return parent::model($className);
    }

    public function getCourse($yearId = null)
    {
        $year = null;
        if (isset($yearId)) {
            $year = StudyYear::model()->findByPk($yearId);
        }
        if (!isset($year)) {
            $year = StudyYear::getCurrentYear();
        }
        $last_year = mb_substr($this->title, 3, 2, 'UTF-8');
        $value = $year->end - 2000 - $last_year;
        return $value;
    }

    public function getStudentArray()
    {
        /**
         * @var $list Student[]
         * @var $list2 Student[]
         */
        $list2 = array();
        $list = Student::model()->findAll();
        foreach ($list as $item) {
            $listgr = $item->getGroupListArray();
            if ($listgr == Yii::t('student', 'This group have not group')) continue;
            if (in_array($this->id, $listgr)) array_push($list2, Student::model()->findByPk($item->id));
        }
        return $list2;
    }

    public function getCuratorId()
    {
        /**
         * @var $list Teacher[]
         */
        $list = Teacher::model()->findAll();
        foreach ($list as $item) if (in_array($this->id, $item->getGroupListArray())) return $item->id;
        return -1;
    }

    public function getCuratorLink()
    {
        /**
         * @var $teacher Teacher[]
         */
        $i = $this->getCuratorId();
        if ($i >= 0) {
            $teacher = Teacher::model()->findAllByPk($i);
            return CHtml::link($teacher[0]->getFullName(), array('../teacher/view/'.$i));
        } else  return 'Fatal - this group have not curator';
    }


    public function getStudentsList()
    {
        /**
         * @var $list Student[]
         * @var $list2 Student[]
         */

        $list2 = $this->getStudentArray();
        return 0;
    }

    public static function getNameGroup($id)
    {
        /**
         * @var $list Group;
         */
        $list=self::model()->findByPk($id);
        return $list->title;
    }

    public static function getArrayStudentByGroupId($id,$date=null){
        $students=Student::model()->findAll();
        /*
         * $sArray Student[]
         */
        $sArray=array();
        foreach($students as $item){
            $item->getGroupListArray($date);
            if(in_array($id,$item->getGroupListArray())) array_push($sArray,$item);
        }
        return $sArray;
    }

    public static function getTreeList()
    {
        $list = array();
        /**
         * @var $speciality Speciality[]
         */
        $speciality = Speciality::model()->findAll();
        foreach ($speciality as $item) {
            $list[$item->title] = array();
            foreach ($item->groups as $group) {
                $list[$item->title][$group->id] = $group->title;
            }
        }
        return $list;
    }

    /**
     * @return string the associated database table name
     */
    public function tableName()
    {
        return 'group';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules()
    {
        return array(
            array('title, speciality_id','required'),
            array('monitor_id', 'required', 'on' => 'update'),
            array('speciality_id , monitor_id', 'numerical', 'integerOnly' => true),
            array('title', 'length', 'max' => 8),
            array('title', 'unique'),
        );
    }

    /**
     * @return array relational rules.
     */
    public function relations()
    {
        return array(
            'speciality' => array(self::BELONGS_TO, 'Speciality', 'speciality_id'),
            'curator' => array(self::MANY_MANY, 'Teacher', 'curator_group(teacher_id,group_id)'),
            'students' => array(self::MANY_MANY, 'Student', 'student_group(student_id,group_id)', 'order' => 'last_name, first_name, middle_name ASC'),
            'loads' => array(self::HAS_MANY, 'TeacherLoad', 'group_id'),
            'student_group' => array(self::HAS_MANY, 'StudentGroup', 'group_id'),
        );
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels()
    {
        return array(
            'id' => Yii::t('base', 'ID'),
            'title' => Yii::t('base', 'Title'),
            'speciality_id' => Yii::t('base', 'Speciality'),
            'curator_id' => Yii::t('group', 'Curator'),
            'curator' => Yii::t('group', 'Curator'),
            'monitor_id' => Yii::t('group', 'Monitor'),
        );
    }

    private $curator_old;
    private $monitor_old;

    protected function beforeSave()
    {
        /*
        if ($this->curator_id != $this->curator_old) {
            $auth = Yii::app()->authManager;
            $curator_old_user = User::model()->findByAttributes(
                array(
                    'identity_id' => $this->curator_old,
                    'identity_type' => User::TYPE_TEACHER
                )
            );
            $curator_new_user = User::model()->findByAttributes(
                array(
                    'identity_id' => $this->curator_id,
                    'identity_type' => User::TYPE_TEACHER
                )
            );

            if (isset($curator_old_user)) {
                $auth->revoke('curator', $curator_old_user->getAttribute('id'));
            }
            if (isset($curator_new_user)) {
                $auth->assign('curator', $curator_new_user->getAttribute('id'));
            }

            $monitor_old_user = User::model()->findByAttributes(
                array(
                    'identity_id' => $this->monitor_old,
                    'identity_type' => User::TYPE_STUDENT
                )
            );
            $monitor_new_user = User::model()->findByAttributes(
                array(
                    'identity_id' => $this->monitor_id,
                    'identity_type' => User::TYPE_STUDENT
                )
            );
            if (isset($monitor_old_user)) {
                $auth->revoke('prefect', $monitor_old_user->getAttribute('id'));
            }
            if (isset($monitor_new_user)) {
                $auth->assign('prefect', $monitor_new_user->getAttribute('id'));
            }
        }
        */
        return parent::beforeSave();
    }


    /**
     * @return int
     */
    public function getStudentsCount()
    {
        return count($this->getStudentArray());
    }

    /**
     * @return int
     */
    public function getBudgetStudentsCount()
    {
        /**
         * @var list Student[]
         */
        $list = $this->getStudentArray();
        $k = 0;
        if(empty($list)) return 0;
        foreach ($list as $item) {
            if(!isset($item->contract)) $k++; elseif($item->contract==0) $k++;

        }
        return $k;
    }

    /**
     * @return int
     */
    public function getContractStudentsCount()
    {
        /**
         * @var list Student[]
         */
        $list = $this->getStudentArray();
        $k = 0;
        foreach ($list as $item) {
            if(isset($item->contract)) {
                if ($item->contract == 1) $k++;
            }
        }
        return $k;
    }
}
