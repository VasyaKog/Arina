<?php

/**
 * This is the model class for table "mark".
 *
 * The followings are the available columns in table 'mark':
 * @property string $id
 * @property string $journal_record_id
 * @property integer $present
 * @property string $fail_no_present_id
 * @property string $date
 * @property string $value_id
 * @property string $retake_date
 * @property string $retake_value_id
 * @property integer $ticket_numb
 * @property integer $retake_ticket_numb
 * @property string $system_id
 * @property integer $student_id
 * @property string $comment
 *
 * @property $journal_record JournalRecord
 * @property $system EvaluationSystem
 */
class Mark extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'mark';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('journal_record_id, student_id', 'required'),
			array('present, ticket_numb, retake_ticket_numb', 'numerical', 'integerOnly'=>true),
			array('journal_record_id, fail_no_present_id, value_id, retake_value_id, system_id, student_id', 'length', 'max'=>10),
			array('date, retake_date', 'safe'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, journal_record_id, present, fail_no_present_id, date, value_id, retake_date, retake_value_id, ticket_numb, retake_ticket_numb, system_id, student_id', 'safe', 'on'=>'search'),
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
			'system' => array(self::BELONGS_TO,'EvaluationSystem','system_id'),
			'journal_record'=>array(self::BELONGS_TO,'JournalRecord','journal_record_id'),
		);
	}


	public static function getLink($record_id,$student_id) {
		/**
		 * $mark Mark
		 **/
		$mark=Mark::model()->findByAttributes(array('student_id'=>$student_id,'journal_record_id'=>$record_id));
		if(!empty($mark)){
			$string='';
			if($mark->present==1) $string=$string.Yii::t('journal','NP').'/';
			if(isset($mark->value_id)) {
				if($mark->value_id!=0){
				$mark_string = Evaluation::getTitle($mark->value_id);
				$string=$string.$mark_string.'/';}
			}
			if(isset($mark->retake_value_id)){
				if($mark->retake_value_id!=0) {
					$mark_string = Evaluation::getTitle($mark->retake_value_id);
					$string=$string.$mark_string . '/';
				}
			};
			$string=substr($string,0,-1);
			return CHtml::link($string,array('mark/views/'.$mark->id));
		}
		else {
			return false;
		}
	}
	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'journal_record_id' => 'Journal Record',
			'present' => 'Present',
			'fail_no_present_id' => 'Fail No Present',
			'date' => 'Date',
			'value_id' => 'Value',
			'retake_date' => 'Retake Date',
			'retake_value_id' => 'Retake Value',
			'ticket_numb' => 'Ticket Numb',
			'retake_ticket_numb' => 'Retake Ticket Numb',
			'system_id' => 'System',
			'student_id' => 'Student',
			'comment'=>'Note'
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

		$criteria=new CDbCriteria;

		$criteria->compare('id',$this->id,true);
		$criteria->compare('journal_record_id',$this->journal_record_id,true);
		$criteria->compare('present',$this->present);
		$criteria->compare('fail_no_present_id',$this->fail_no_present_id,true);
		$criteria->compare('date',$this->date,true);
		$criteria->compare('value_id',$this->value_id,true);
		$criteria->compare('retake_date',$this->retake_date,true);
		$criteria->compare('retake_value_id',$this->retake_value_id,true);
		$criteria->compare('ticket_numb',$this->ticket_numb);
		$criteria->compare('retake_ticket_numb',$this->retake_ticket_numb);
		$criteria->compare('system_id',$this->system_id,true);
		$criteria->compare('student_id',$this->student_id,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return Mark the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
