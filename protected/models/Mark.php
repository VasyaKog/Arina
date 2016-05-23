<?php

/**
 * This is the model class for table "mark".
 *
 * The followings are the available columns in table 'mark':
 * @property string $id
 * @property string $journal_record_id
 * @property string $present
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
 * @property $student Student
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
			//array('present, ticket_numb, retake_ticket_numb', 'numerical', 'integerOnly'=>true),
			array('journal_record_id, fail_no_present_id, present, value_id, retake_value_id, system_id, student_id', 'length', 'max'=>10),
			//array('date, retake_date', 'safe'),
			array('value_id','check_all'),
			array('ticket_numb','check_ticket'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			//array('id, journal_record_id, present, fail_no_present_id, date, value_id, retake_date, retake_value_id, ticket_numb, retake_ticket_numb, system_id, student_id', 'safe', 'on'=>'search'),
		);
	}

	public function check_ticket(){
		$valid = true;
		if(isset($this->journal_record->types->ticket)) {
			if($this->journal_record->types->present==1){
				if(isset($this->present)) {
					if($this->present!=1) {
						if ($this->journal_record->types->ticket == 1) {
							$valid = false;
							if (isset($this->value_id)) {
								if ($this->value_id != 0) {
									if (isset($this->ticket_numb)) $valid = true;
									else $valid = false;
								}
							}
							if (isset($this->retake_value_id)) {
								if ($this->retake_value_id != 0) {
									if (isset($this->retake_ticket_numb)) $valid = true;
									else $valid = false;
								}
							}
						}
					} else $valid = true;
				}
			}
		}
		if (!$valid) {
			$this->addError('ticket_numb', Yii::t('journal','Select ticket numb or select present student'));
		}
	}

	public function check_all(){
		$valid=false;
		if($this->journal_record->types->present==1){
			if(isset($this->value_id))
			{
				if($this->value_id!=0) $valid=true;
				else
					if(isset($this->present))
					{

						if($this->present==1) $valid=true;
					}
			}  else
			if(isset($this->present))
			{

				if($this->present==1) $valid=true;
			}
		} else {
			if(isset($this->value_id))
			{
				if($this->value_id!=0) $valid=true;
			}
		}
		if (!$valid) {
			if($this->journal_record->types->present==1) {
				$this->addError('valid_id', Yii::t('journal','Select present or set mark'));
			} else $this->addError('valid_id', Yii::t('journal','Select mark'));

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
			'system' => array(self::BELONGS_TO,'EvaluationSystem','system_id'),
			'journal_record'=>array(self::BELONGS_TO,'JournalRecord','journal_record_id'),
			'student'=>array(self::BELONGS_TO,'Student','student_id'),
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

	public static function getLinkPr($record_id,$student_id) {
		/**
		 * $mark Mark
		 **/
		$mark=Mark::model()->findByAttributes(array('student_id'=>$student_id,'journal_record_id'=>$record_id));
		if(!empty($mark)){
			$string='';
			if($mark->present==1) $string=$string.Yii::t('journal','NP').'/';
			$string=substr($string,0,-1);
			return CHtml::link($string,array('np/views/'.$mark->id));
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
			'journal_record_id' => Yii::t('journal','Journal Record'),
			'present' => Yii::t('journal','Present'),
			'fail_no_present_id' => Yii::t('journal','Fail No Present'),
			'date' => Yii::t('base','Date'),
			'value_id' => Yii::t('journal','Mark'),
			'retake_date' => Yii::t('journal','Retake Date'),
			'retake_value_id' => Yii::t('journal','Retake Mark'),
			'ticket_numb' => Yii::t('journal','Ticket Numb'),
			'retake_ticket_numb' => Yii::t('journal','Retake Ticket Numb'),
			'system_id' => Yii::t('journal','Evaluation System'),
			'student_id' => Yii::t('journal','Student'),
			'comment'=>Yii::t('journal','Note')
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
		$criteria->compare('comment',$this->comment);

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
