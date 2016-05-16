<?php

/**
 * This is the model class for table "study_year".
 *
 * The followings are the available columns in table 'study_year':
 * @property integer $id
 * @property string $title
 * @property int $begin
 * @property int $end
 *
 * @property WorkPlan[] $workPlans
 */
class StudyYear extends ActiveRecord
{
    /**
     * @return string the associated database table name
     */
    public function tableName()
    {
        return 'study_year';
    }

    public static function getCurrentYear()
    {
        $year = new self();
        $now = new DateTime();
        $month = (int)$now->format('m');
        $nowYear = (int)$now->format('Y');
        if ($month >= 1 AND $month <= 6) {
            $year->begin = $nowYear - 1;
            $year->end = $nowYear;
        } else {
            $year->begin = $nowYear;
            $year->end = $nowYear + 1;
        }
        return $year;
    }


    public function equal($mEnd)
    {
        $l = $this -> begin;
        $r = $this -> end;

        if ($l != ($r-1))
            $this -> addError($mEnd, Yii::t('studyYears', 'Years are not correct!'));
    }

    public function rules()
    {
        return array(
            array('begin', 'required'),
            array('end', 'required'),
            array('begin', 'length', 'max' => 4),
            array('end', 'length', 'max' => 4),
            array('begin', 'numerical', 'integerOnly' => true),
            array('end', 'numerical', 'integerOnly' => true),
            array('begin', 'unique'),
            array('end', 'equal'),

        );
    }

    /**
     * @return array relational rules.
     */
    public function relations()
    {
        // NOTE: you may need to adjust the relation name and the related
        // class name for the relations automatically generated below.
        return array(
            'workPlans' => array(self::HAS_MANY, 'WorkPlan', 'year_id'),
        );
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels()
    {
        return array(
            'id' => 'ID',
            'begin' => Yii::t('studyYears', 'Start of the study year'),
            'end' => Yii::t('studyYears', 'End of the study year'),
        );
    }

    /**
     * Retrieves a list of models based on the current search/filter conditions.
     *
     * Typical usecase:
     * - Initialize the model fields with values from filter form.
     * - Execute this method to get CActiveDataProvider instance which will filter
     * models according to data in model fields.
     * - Pass data provider to CGridView, CListView or any similar widget.
     *
     * @return CActiveDataProvider the data provider that can return the models
     * based on the search/filter conditions.
     */
    public function search()
    {
        // @todo Please modify the following code to remove attributes that should not be searched.

        $criteria = new CDbCriteria;

        $criteria->compare('id', $this->id);
        $criteria->compare('begin', $this->begin, true);
        $criteria->compare('end', $this->end, true);

        return new CActiveDataProvider($this, array(
            'criteria' => $criteria,
        ));
    }

    /**
     * Returns the static model of the specified AR class.
     * Please note that you should have this exact method in all your CActiveRecord descendants!
     * @param string $className active record class name.
     * @return StudyYear the static model class
     */
    public static function model($className = __CLASS__)
    {
        return parent::model($className);
    }

    /**
     * @return array
     */

    public function getTitle()
    {
        return "$this->begin/$this->end";
    }



    public function setTitle($title)
    {
        $this->begin = substr($title,0,3);
        $this->end = substr($title,5);
    }

    public static function getList()
    {
        return CHtml::listData(StudyYear::model()->findAll(), 'id', 'title');
    }

    public static function getTitleById($id)
    {
        /**
         * @var $item StudyYear
         */
        $item=StudyYear::model()->findByPk(array('id'=>$id));
        return "$item->begin/$item->end";
    }

}
