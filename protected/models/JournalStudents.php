<?php

/**
 * This is the model class for table "journal_students".
 *
 * The followings are the available columns in table 'journal_students':
 * @property string $id
 * @property string $date
 * @property string $student_id
 * @property string $load_id
 * @property integer $type
 *
 * @property student Student
 */
class JournalStudents extends CActiveRecord
{
	public $layout='//layouts/column2';

	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'journal_students';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('date, student_id, load_id, type', 'required'),
			array('type', 'numerical', 'integerOnly'=>true),
			array('student_id, load_id', 'length', 'max'=>10),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, student_id, load_id, type', 'safe', 'on'=>'search'),
		);
	}
	public function getTypes(){
		return $this->type? Yii::t('terms','included'):Yii::t('terms','exclude');
	}
	/**
	 * @return array relational rules.
	 */
	public function relations()
	{
		// NOTE: you may need to adjust the relation name and the related
		// class name for the relations automatically generated below.
		return array(
			'student'=>array(self::BELONGS_TO,'Student','student_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'date' => Yii::t('base','Date'),
			'student_id' => Yii::t('student','Student'),
			'load_id' => Yii::t('load','Load'),
			'type' => Yii::t('studentGroup','Type action'),
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

	/**
	 * @param $load Load
	 */
	public static function getAllStudentsInList($load){
		/**
		 * @var $students Student[]
		 * @var $records JournalStudents[]
		 * @var $student Student[]
		 */
		$records=JournalStudents::model()->findAllByAttributes(array('load_id'=>$load->id));
		$studentsid = array();
		$students=array();
		foreach($records as $record) {
			if(!in_array($record->student_id,$studentsid)) array_push($studentsid, $record->student_id);
		}
		foreach($studentsid as $item){
			array_push($students,Student::model()->findByPk($item));
		}
		return $students;
	}
	public static function getAllStudentsInArray($load){
		/**
		 * @var $students Student[]
		 * @var $records JournalStudents[]
		 * @var $student Student[]
		 */
		$records=JournalStudents::model()->findAllByAttributes(array('load_id'=>$load->id));
		$studentsid = array();
		$students=array();
		foreach($records as $record) {
			if(!in_array($record->student_id,$studentsid)) array_push($studentsid, $record->student_id);
		}
		return $students;
	}
	public function search()
	{
		// @todo Please modify the following code to remove attributes that should not be searched.

		$criteria=new CDbCriteria;

		$criteria->compare('id',$this->id,true);
		//$criteria->compare('date',$this->date,true);
		$criteria->compare('student_id',$this->student_id,true);
		$criteria->compare('load_id',$this->load_id,true);
		$criteria->compare('type',$this->type);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return JournalStudents the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
