<?php

/**
 * @author Serhiy Vinichuk <serhiyvinichuk@gmail.com>
 * This is the model class for table "sp_semester".
 *
 * The followings are the available columns in table 'sp_semester':
 * @property integer $id
 * @property integer $sp_plan_id
 * @property integer $semester_number
 * @property integer $weeks_count
 *
 * The followings are the available model relations:
 * @property Plan $plan
 */
class Semester extends ActiveRecord
{
    /**
     * @return string the associated database table name
     */
    public function tableName()
    {
        return 'sp_semester';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules()
    {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('sp_plan_id, semester_number, weeks_count', 'required'),
            array('sp_plan_id, semester_number, weeks_count', 'numerical', 'integerOnly' => true),
            array('semester_number', 'checkSemester', 'on' => 'create'),
            // The following rule is used by search().
            // @todo Please remove those attributes that should not be searched.
            array('id, sp_plan_id, semester_number, weeks_count', 'safe', 'on' => 'search'),
        );
    }

    public function checkSemester($attribute, $params)
    {
        if (!$this->hasErrors()) {
            foreach ($this->plan->semesters as $item) {
                if ($item->semester_number == $this->semester_number)
                    $this->addError('semester_number', Yii::t('studyPlan', 'Semester already exists'));
            }
        }
    }

    /**
     * @return array relational rules.
     */
    public function relations()
    {
        // NOTE: you may need to adjust the relation name and the related
        // class name for the relations automatically generated below.
        return array(
            'plan' => array(self::BELONGS_TO, 'Plan', 'sp_plan_id'),
            'hours' => array(self::HAS_MANY, 'Hours', 'sp_semester_id'),
        );
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels()
    {
        return array(
            'id' => 'ID',
            'sp_plan_id' => 'Навчальний план',
            'semester_number' => 'Номер семестру',
            'weeks_count' => 'Кількість тижнів',
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
        $criteria->compare('sp_plan_id', $this->sp_plan_id);
        $criteria->compare('semester_number', $this->semester_number);
        $criteria->compare('weeks_count', $this->weeks_count);

        return new CActiveDataProvider($this, array(
            'criteria' => $criteria,
        ));
    }

    /**
     * Returns the static model of the specified AR class.
     * Please note that you should have this exact method in all your CActiveRecord descendants!
     * @param string $className active record class name.
     * @return Semester the static model class
     */
    public static function model($className = __CLASS__)
    {
        return parent::model($className);
    }
}
