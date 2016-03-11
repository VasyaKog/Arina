<?php
Yii::import('application.behaviors.dateField.*');

/**
 * This is the model class for table "student".
 *
 * The followings are the available columns in table 'student':
 * @property integer $id
 * @property string $code
 * @property string $last_name
 * @property string $first_name
 * @property string $middle_name
 * @property integer $group_id
 * @property string $phone_number
 * @property string $mobile_number
 * @property string $mother_name
 * @property string $father_name
 * @property string $gender
 * @property string $official_address
 * @property string $characteristics
 * @property string $factual_address
 * @property string $birth_date
 * @property string $admission_date
 * @property string $tuition_payment
 * @property integer $admission_order_number
 * @property integer $admission_semester
 * @property string $entry_exams
 * @property string $education_document
 * @property boolean $contract
 * @property integer $math_mark
 * @property integer $ua_language_mark
 * @property string $mother_workplace
 * @property string $mother_position
 * @property string $mother_workphone
 * @property string $mother_boss_workphone
 * @property string $father_workplace
 * @property string $father_position
 * @property string $father_workphone
 * @property string $father_boss_workphone
 * @property integer $graduated
 * @property string $graduation_date
 * @property string $graduation_basis
 * @property integer $graduation_semester
 * @property integer $graduation_order_number
 * @property string $diploma
 * @property string $direction
 * @property string $misc_data
 * @property string $hobby
 * @property integer $sseed_id
 * @property string $document
 * @property string $identification_code
 * @property string $form_of_study_notes
 *
 * @property string $exemptionNames
 *
 * @property Exemption[] $exemptions
 * @property Group $group
 * @property ClassMark[] $marks
 * @property ClassAbsence[] $absences
 */
class Student extends ActiveRecord implements IDateContainable
{
    public $classes = array();
    public $exemptionNames = 'None';

    /**
     * Returns the static model of the specified AR class.
     * Please note that you should have this exact method in all your CActiveRecord descendants!
     * @param string $className active record class name.
     * @return Student the static model class
     */
    public static function model($className = __CLASS__)
    {
        return parent::model($className);
    }

    public function getGenderName()
    {
        return $this->gender ? Yii::t('terms', 'Female') : Yii::t('terms', 'Male');
    }

    public function behaviors()
    {
        return array(
            'EActiveRecordRelationBehavior' => array(
                'class' => 'vendor.yiiext.activerecord-relation-behavior.EActiveRecordRelationBehavior'
            ),
            'DateBehavior'
        );
    }

    /**
     * @return string the associated database table name
     */
    public function tableName()
    {
        return 'student';
    }

    /**
     * @return string
     */
    public function getFullName()
    {
        return "$this->last_name $this->first_name $this->middle_name";
    }

    /**
     * @return string
     */
    public function getShortFullName()
    {
        $v = mb_substr($this->first_name,0,1,'UTF-8');
        $v2 = mb_substr($this->middle_name,0,1,'UTF-8');
        return "$this->last_name " . $v . '. ' . $v2 . '.';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules()
    {
        return array(
            array('last_name, first_name, middle_name, group_id, gender', 'required'),
            array('group_id, admission_order_number, admission_semester, math_mark, ua_language_mark, graduated, graduation_semester, graduation_order_number, sseed_id', 'numerical', 'integerOnly' => true),
            array('code', 'length', 'max' => 12),
            array('last_name, first_name, middle_name', 'length', 'max' => 40),
            array('phone_number, mobile_number', 'length', 'max' => 15),
            array('mother_name, father_name, identification_code', 'length', 'max' => 60),
            array('gender', 'length', 'max' => 10),
            array('official_address', 'length', 'max' => 200),
            array('factual_address, entry_exams, misc_data, hobby', 'length', 'max' => 100),
            array('tuition_payment, contract, mother_workplace, mother_position, father_workplace, father_position, graduation_basis, diploma, direction', 'length', 'max' => 50),
            array('education_document, document', 'length', 'max' => 255),
            array('mother_workphone, mother_boss_workphone, father_workphone, father_boss_workphone', 'length', 'max' => 20),
            array('characteristics, birth_date, admission_date, graduation_date, exemptions', 'safe'),
            // The following rule is used by search().
            // @todo Please remove those attributes that should not be searched.
            array('id, code, last_name, first_name, middle_name, group_id, phone_number, mobile_number, mother_name, father_name, gender, official_address, characteristics, factual_address, birth_date, admission_date, tuition_payment, admission_order_number, admission_semester, entry_exams, education_document, contract, math_mark, ua_language_mark, mother_workplace, mother_position, mother_workphone, mother_boss_workphone, father_workplace, father_position, father_workphone, father_boss_workphone, graduated, graduation_date, graduation_basis, graduation_semester, graduation_order_number, diploma, direction, misc_data, hobby, exemptions, sseed_id, document, identification_code', 'safe', 'on' => 'search'),
        );
    }

    /**
     * @return array relational rules.
     */
    public function relations()
    {
        return array(
            'group' => array(self::BELONGS_TO, 'Group', 'group_id'),
            'marks' => array(self::HAS_MANY, 'ClassMark', 'student_id'),
            'absences' => array(self::HAS_MANY, 'ClassAbsence', 'student_id'),
            'student_has_exemption' => array(self::HAS_MANY, 'StudentExemption', 'student_id'),
            'exemptions' => array(self::MANY_MANY, 'Exemption', 'student_has_exemption(student_id, exemption_id)'),
        );
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels()
    {
        return array(
            'id' => Yii::t('student', 'ID'),
            'code' => Yii::t('student', 'Code'),
            'last_name' => Yii::t('student', 'Last Name'),
            'first_name' => Yii::t('student', 'First Name'),
            'middle_name' => Yii::t('student', 'Middle Name'),
            'group_id' => Yii::t('student', 'Group'),
            'phone_number' => Yii::t('student', 'Phone Number'),
            'mobile_number' => Yii::t('student', 'Mobile Number'),
            'mother_name' => Yii::t('student', 'Mother Name'),
            'father_name' => Yii::t('student', 'Father Name'),
            'gender' => Yii::t('student', 'Gender'),
            'official_address' => Yii::t('student', 'Official Address'),
            'characteristics' => Yii::t('student', 'Characteristics'),
            'factual_address' => Yii::t('student', 'Factual Address'),
            'birth_date' => Yii::t('student', 'Birth Date'),
            'admission_date' => Yii::t('student', 'Admission Date'),
            'tuition_payment' => Yii::t('student', 'Tuition Payment'),
            'admission_order_number' => Yii::t('student', 'Admission Order Number'),
            'admission_semester' => Yii::t('student', 'Admission Semester'),
            'entry_exams' => Yii::t('student', 'Entry Exams'),
            'education_document' => Yii::t('student', 'Education Document'),
            'contract' => Yii::t('student', 'Contract'),
            'math_mark' => Yii::t('student', 'Math Mark'),
            'ua_language_mark' => Yii::t('student', 'Ua Language Mark'),
            'mother_workplace' => Yii::t('student', 'Mother Workplace'),
            'mother_position' => Yii::t('student', 'Mother Position'),
            'mother_workphone' => Yii::t('student', 'Mother Workphone'),
            'mother_boss_workphone' => Yii::t('student', 'Mother Boss Workphone'),
            'father_workplace' => Yii::t('student', 'Father Workplace'),
            'father_position' => Yii::t('student', 'Father Position'),
            'father_workphone' => Yii::t('student', 'Father Workphone'),
            'father_boss_workphone' => Yii::t('student', 'Father Boss Workphone'),
            'graduated' => Yii::t('student', 'Graduated'),
            'graduation_date' => Yii::t('student', 'Graduation Date'),
            'graduation_basis' => Yii::t('student', 'Graduation Basis'),
            'graduation_semester' => Yii::t('student', 'Graduation Semester'),
            'graduation_order_number' => Yii::t('student', 'Graduation Order Number'),
            'diploma' => Yii::t('student', 'Diploma'),
            'direction' => Yii::t('student', 'Direction'),
            'misc_data' => Yii::t('student', 'Misc Data'),
            'hobby' => Yii::t('student', 'Hobby'),
            'exemptions' => Yii::t('student', 'Exemptions'),
            'exemptionNames' => Yii::t('student', 'Exemptions'),
            'sseed_id' => Yii::t('student', 'SSEED Id'),
            'document' => Yii::t('student', 'Document'),
            'identification_code' => Yii::t('student', 'Identification code'),
            'form_of_study_notes' => Yii::t('student', 'Form of study notes'),
            'fullName' => Yii::t('student', 'Full name'),
        );
    }

    /**
     * @return CActiveDataProvider
     */
    public function search()
    {
        $criteria = new CDbCriteria;

        $criteria->compare('code', $this->code, true);
        $criteria->compare('last_name', $this->last_name, true);
        $criteria->compare('first_name', $this->first_name, true);
        $criteria->compare('middle_name', $this->middle_name, true);
        $criteria->compare('group_id', $this->group_id);
        $criteria->compare('phone_number', $this->phone_number, true);
        $criteria->compare('mobile_number', $this->mobile_number, true);
        $criteria->compare('mother_name', $this->mother_name, true);
        $criteria->compare('father_name', $this->father_name, true);
        $criteria->compare('gender', $this->gender, true);
        $criteria->compare('official_address', $this->official_address, true);
        $criteria->compare('factual_address', $this->factual_address, true);
        $criteria->compare('birth_date', $this->birth_date, true);
        $criteria->compare('admission_date', $this->admission_date, true);
        $criteria->compare('tuition_payment', $this->tuition_payment, true);
        $criteria->compare('admission_order_number', $this->admission_order_number);
        $criteria->compare('admission_semester', $this->admission_semester);
        $criteria->compare('entry_exams', $this->entry_exams, true);
        $criteria->compare('education_document', $this->education_document, true);
        $criteria->compare('contract', $this->contract, true);
        $criteria->compare('math_mark', $this->math_mark);
        $criteria->compare('ua_language_mark', $this->ua_language_mark);
        $criteria->compare('mother_workplace', $this->mother_workplace, true);
        $criteria->compare('mother_position', $this->mother_position, true);
        $criteria->compare('mother_workphone', $this->mother_workphone, true);
        $criteria->compare('mother_boss_workphone', $this->mother_boss_workphone, true);
        $criteria->compare('father_workplace', $this->father_workplace, true);
        $criteria->compare('father_position', $this->father_position, true);
        $criteria->compare('father_workphone', $this->father_workphone, true);
        $criteria->compare('father_boss_workphone', $this->father_boss_workphone, true);
        $criteria->compare('graduated', false);
        $criteria->compare('graduation_date', $this->graduation_date, true);
        $criteria->compare('graduation_basis', $this->graduation_basis, true);
        $criteria->compare('graduation_semester', $this->graduation_semester);
        $criteria->compare('graduation_order_number', $this->graduation_order_number);
        $criteria->compare('diploma', $this->diploma, true);
        $criteria->compare('direction', $this->direction, true);
        $criteria->compare('misc_data', $this->misc_data, true);
        $criteria->compare('hobby', $this->hobby, true);
        $criteria->compare('sseed_id', $this->sseed_id, true);

        if (!empty($this->exemptions)) {
            $ids = implode(', ', $this->exemptions);
            $criteria->with = array(
                'student_has_exemption' => array(
                    'condition' => "exemption_id IN ($ids)",
                )
            );
            $criteria->together = true;
        }

        return new CActiveDataProvider($this, array(
            'criteria' => $criteria,
            'pagination' => array(
                'pageSize' => 20,
            ),
        ));
    }

    protected function afterFind()
    {
        $names = array();
        $list = array();
        foreach ($this->exemptions as $item) {
            $list[] = $item->id;
            $names[] = $item->title;
        }
        $this->exemptions = $list;
        if (!$this->exemptionNames = implode(', ', $names)) {
            $this->exemptionNames = Yii::t('base', 'None');
        }
        $this->birth_date  = date('m/d/Y', strtotime($this->birth_date));
        $this->admission_date  = date('m/d/Y', strtotime($this->admission_date));
        $this->graduation_date  = date('m/d/Y', strtotime($this->graduation_date));
    }

    protected function afterSave()
    {
        if (empty($this->exemptions)) {
            $this->exemptions = array();
        }

        if ($this->isNewRecord) {
            UserGenerator::generateUser($this->id, User::TYPE_STUDENT);
        }
        return parent::afterSave();
    }

    protected function beforeSave()
    {
        if(parent::beforeSave()) {
            $this->birth_date = date('Y-m-d', strtotime($this->birth_date));
            $this->admission_date = date('Y-m-d', strtotime($this->admission_date));
            $this->graduation_date = date('Y-m-d', strtotime($this->graduation_date));
            return true;
        } else {
            return false;
        }
    }


    public function getDateFields()
    {
        return array(
            'birth_date',
            'admission_date',
            'graduation_date'
        );
    }
}
