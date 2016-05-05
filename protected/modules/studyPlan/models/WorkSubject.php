<?php

/**
 * @author Serhiy Vinichuk <serhiyvinichuk@gmail.com>
 * @copyright ХПК 2014
 *
 * This is the model class for table "sp_subject".
 *
 * The followings are the available columns in table 'sp_subject':
 * @property integer $id
 * @property integer $plan_id
 * @property integer $subject_id
 * @property array $total
 * @property array $lectures
 * @property array $labs
 * @property array $practs
 * @property array $weeks
 * @property array $control
 * @property integer $cyclic_commission_id
 * @property string $certificate_name
 * @property string $diploma_name
 * @property integer $project_hours
 * @property array $control_hours
 * @property bool $dual_labs
 * @property bool $dual_practice
 *
 * The followings are the available model relations:
 * @property WorkPlan $plan
 * @property Subject $subject
 * @property CyclicCommission $cycleCommission
 */class WorkSubject extends ActiveRecord
{
    const CONTROL_TEST = 0;
    const CONTROL_EXAM = 1;
    const CONTROL_DPA = 2;
    const CONTROL_DA = 3;
    const CONTROL_WORK = 4;
    const CONTROL_PROJECT = 5;


    /**
     * Returns the static model of the specified AR class.
     * Please note that you should have this exact method in all your CActiveRecord descendants!
     * @param string $className active record class name.
     * @return StudyPlan the static model class
     */
    public static function model($className = __CLASS__)
    {
        return parent::model($className);
    }


    public function getTitle()
    {
        return (!(empty($this->diploma_name) && empty($this->certificate_name)) ? '* ' : '') . $this->subject->title . (($this->dual_labs || $this->dual_practice) ? ' *' : '');
    }

    /**
     * @return array
     */
    public function rules()
    {
        return array(
            array(
                'subject_id, total, lectures, labs, practs, weeks, control, cyclic_commission_id, certificate_name, diploma_name, project_hours',
                'safe'
            ),
            array('total, lectures, labs, practs', 'default', 'value' => array('', '', '', '', '', '', '', '')),
        );
    }

    /**
     * @return string the associated database table name
     */
    public function tableName()
    {
        return 'wp_subject';
    }

    /**
     * @return array relational rules.
     */
    public function relations()
    {
        return array(
            'plan' => array(self::BELONGS_TO, 'WorkPlan', 'plan_id'),
            'cycleCommission' => array(self::BELONGS_TO, 'CyclicCommission', 'cyclic_commission_id'),
            'subject' => array(self::BELONGS_TO, 'Subject', 'subject_id'),
        );
    }

    public function attributeLabels()
    {
        return array(
            'id' => 'ID',
            'plan_id' => 'Plan',
            'subject_id' => Yii::t('terms', 'Subject'),
            'total' => 'Загальна кількість',
            'lectures' => 'Лекції',
            'labs' => 'Лабораторні',
            'practs' => 'Практичні',
            'classes' => 'Всього аудиторних',
            'selfwork' => 'Самостійна робота',
            'testSemesters' => 'Залік',
            'examSemesters' => 'Екзамен',
            'workSemesters' => 'Курсова робота',
            'projectSemesters' => 'Курсовий проект',
            'weeks' => 'Годин на тиждень',
            'cyclic_commission_id' => 'Циклова комісія',
            'certificate_name' => 'Назва в атестат',
            'diploma_name' => 'Назва в диплом',
            'project_hours' => 'Годин для курсового проектування',
        );
    }

    /**
     * @return array
     */
    public function behaviors()
    {
        return CMap::mergeArray(
            parent::behaviors(),
            array(
                'StrBehavior' => array(
                    'class' => 'application.behaviors.StrBehavior',
                    'fields' => array(
                        'total',
                        'lectures',
                        'labs',
                        'practs',
                        'weeks',
                    )
                ),
                'JSONBehavior' => array(
                    'class' => 'application.behaviors.JSONBehavior',
                    'fields' => array(
                        'control',
                        'control_hours',
                    )
                ),
            )
        );
    }

    /**
     * @param $semester
     * @return integer
     */
    public function getSelfwork($semester)
    {
        return $this->total[$semester] - $this->getClasses($semester);
    }

    /**
     * @param $semester
     * @return integer
     */
    public function getClasses($semester)
    {
        return $this->weeks[$semester] * $this->plan->semesters[$semester];
    }

    /**
     * @param $course
     * @return bool
     */
    public function presentIn($course)
    {
        $spring = $course * 2;
        $fall = $spring - 2;
        $spring--;
        return !empty($this->total[$fall]) ||
        !empty($this->weeks[$fall]) ||
        !empty($this->total[$spring]) ||
        !empty($this->weeks[$spring]);
    }

    /**
     * @return bool
     */
    public function hasProject()
    {
        for ($i = 0; $i < count($this->control); $i++)
            if ($this->control[$i][self::CONTROL_PROJECT] || $this->control[$i][self::CONTROL_WORK])
                return true;

        return false;
    }

    /**
     * @param int $year
     * @param bool $onlyProjects
     * @return array
     */
    public static function getListByYear($year, $onlyProjects = false)
    {
        /** @var StudyYear $model */
        $model = StudyYear::model()->loadContent($year);
        $subjects = array();

        if ($onlyProjects) {
            foreach ($model->workPlans as $plan)
                foreach ($plan->subjects as $subject)
                    if ($subject->hasProject())
                        $subjects[] = $subject;
        } else {
            foreach ($model->workPlans as $plan)
                $subjects = array_merge($subjects, $plan->subjects);
        }
        return CHtml::listData($subjects, 'id', 'subject.title');
    }

    public static function getNameSubject($id)
    {
        /**
         * @var $item WorkSubject
         **/
        $item=WorkSubject::model()->findByPk($id);
        /**
         * @var $item1 Subject
         **/
        $item1=Subject::model()->findByPk($item->subject_id);
        return $item1->title;
    }

} 