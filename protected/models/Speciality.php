<?php
Yii::import('application.behaviors.dateField.*');

/**
 * This is the model class for table "speciality".
 *
 * The followings are the available columns in table 'speciality':
 * @property integer $id
 * @property string $title
 * @property integer $department_id
 * @property string $number
 * @property string $accreditation_date
 * @property string $qualification
 * @property string $apprenticeship
 * @property string $discipline
 * @property string $direction
 * @property string $education_form
 *
 * @property Group[] $groups
 * @property Department $department
 * @property StudyPlan[] $studyPlans
 * @property WorkPlan[] $workPlans
 */
class Speciality extends ActiveRecord implements IDateContainable
{
    /**
     * @param null $headId
     * @return array for dropDownList
     */
    public static function getList($headId = null)
    {
        if (isset($id)){
            /** @var $department Department */
            if (Yii::app()->user->checkAccess('dephead')) {
                $department = Department::model()->findByAttributes(array('head_id'=>$id));
            } else if (Yii::app()->user->checkAccess('curator')) {
                $department = Group::model()->findByAttributes(array('curator_id'=>$id))->speciality->department;
            }

            if (isset($department)){
                return CHtml::listData($department->specialities, 'id','title');
            }
            return array();
        } else {
            return self::getListAll('id', 'title');
        }
    }

    /**
     * Returns the static model of the specified AR class.
     * Please note that you should have this exact method in all your CActiveRecord descendants!
     * @param string $className active record class name.
     * @return Speciality the static model class
     */
    public static function model($className = __CLASS__)
    {
        return parent::model($className);
    }

    /**
     * @return string the associated database table name
     */
    public function tableName()
    {
        return 'speciality';
    }

    /**
     * @param $yearId
     * @return array
     */
    public function getGroupsByStudyYear($yearId)
    {
        $list = array();
        foreach ($this->groups as $group) {
            $list[$group->title] = $group->getCourse($yearId);
        }
        array_multisort($list);
        return $list;
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules()
    {
        return array(
            array('title, number', 'unique'),
            array('title, department_id, number, accreditation_date, qualification, apprenticeship, discipline, direction, education_form', 'required'),
            array('department_id', 'numerical', 'integerOnly' => true),
            array('title', 'length', 'max' => 90),
            array('number', 'length', 'max' => 15),
            array('id, title, department_id, number, accreditation_date', 'safe', 'on' => 'search'),
        );
    }

    /**
     * @return array relational rules.
     */
    public function relations()
    {
        return array(
            'groups' => array(self::HAS_MANY, 'Group', 'speciality_id', 'order' => 'title DESC'),
            'department' => array(self::BELONGS_TO, 'Department', 'department_id'),
            'studyPlans' => array(self::HAS_MANY, 'StudyPlan', 'speciality_id'),
            'workPlans' => array(self::HAS_MANY, 'WorkPlan', 'speciality_id'),
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
            'department_id' => Yii::t('department', 'Department'),
            'department' => Yii::t('department', 'Department'),
            'number' => Yii::t('speciality', 'Number'),
            'accreditation_date' => Yii::t('speciality', 'Last accreditation date'),
            'qualification' => Yii::t('speciality', 'Qualification'),
            'apprenticeship' => Yii::t('speciality', 'Apprenticeship'),
            'discipline' => Yii::t('speciality', 'Discipline'),
            'direction' => Yii::t('speciality', 'Direction'),
            'education_form' => Yii::t('speciality', 'Education Time'),
        );
    }

    /**
     * Retrieves a list of models based on the current search/filter conditions.
     *
     * Typical usecase:
     * - Initialize the model fields with values from filter form.
     * - Execute this method to get CActiveDataProvider instance which will filter
     * mo   dels according to data in model fields.
     * - Pass data provider to CGridView, CListView or any similar widget.
     *
     * @return CActiveDataProvider the data provider that can return the models
     * based on the search/filter conditions.
     */
    public function search()
    {
        $criteria = new CDbCriteria;

        $criteria->compare('id', $this->id);
        $criteria->compare('title', $this->title, true);
        $criteria->compare('department_id', $this->department_id);
        $criteria->compare('number', $this->number, true);
        $criteria->compare('accreditation_date', $this->accreditation_date, true);
        $criteria->with = array('department');
        return new CActiveDataProvider($this, array(
            'criteria' => $criteria,
            'sort' => array(
                'defaultOrder' => 't.number ASC',
                'attributes' => array(
                    'department.title' => 'department.title',
                    '*'
                ),
            ),
            'pagination' => array('pageSize' => 20,)
        ));
    }

    public function behaviors()
    {
        return array(
            'DateBehavior',
        );
    }

    public function getDateFields()
    {
        return array(
            'accreditation_date'
        );
    }
}
