<?php
/**
 * @author VasyaKog
 */
/**
 * This is the model class for table "journal_record".
 *
 * The followings are the available columns in table 'journal_record':
 * @property integer $id
 * @property integer $type_id
 * @property string $date
 * @property string $description
 * @property string $home_work
 * @property integer $load_id
 * @property string $teacher_id
 * @property integer $hours
 * @property integer $audience_id
 *
 * @property Mark[] $marks
 * @property Teacher $teacher
 * @property $types JournalRecordType
 * @property $load Load
 * @property $audience Audience
 * 
 */
class JournalRecord extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'journal_record';
	}

	public function getName(){

		if($this->types->date==1) return '<u>'.date("d",strtotime($this->date)).'</u><br>'.date("m",strtotime($this->date)); else return $this->types->title;
   	}

	public function getLink()
	{
		return CHtml::link($this->getName(),array('/journal/journalRecord/views/'.$this->id));
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			//array('type_id, date, description, home_work, load_id, teacher_id, n_pp, numer_in_day', 'required'),
			array('type_id','check_all'),
			array('type_id, load_id,audience_id numer_in_day,, hours', 'numerical', 'integerOnly'=>true),
			array('description, home_work', 'length', 'max'=>255),
			array('teacher_id', 'length', 'max'=>10),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, type_id, date, description, home_work, load_id, teacher_id, n_pp, numer_in_day', 'safe', 'on'=>'search'),
		);
	}


	public function check_all(){
		if(isset($this->types)){
			if($this->type_id==0) $this->addError('type_id', Yii::t('journal', 'Select EvaluationSystem'));
		} else $this->addError('type_id', Yii::t('journal', 'Select JournalRecordType'));
		if(isset($this->types->description)){
			if($this->types->description==1) {
				if (empty($this->description)) {
					$this->addError('description', Yii::t('journal', 'Description') . ' ' . Yii::t('journal', 'is required'));
				}
			}
		}
		if(isset($this->types->homework)){
			if($this->types->description==1) {
				if (empty($this->home_work)) {
					$this->addError('home_work', Yii::t('journal', 'Home task') . ' ' . Yii::t('journal', 'is required'));
				}
			}
		}
		/**
		 * @var $records JournalRecord[]
		 */
		$records=JournalRecord::model()->findAllByAttributes(array('load_id'=>$this->load_id));
		$countHours=0;
		foreach($records as $record){
			if($record->load_id==$this->type_id){
				$countHours+=$record->hours;
			}
		}
		$springSemester = $this->load->course * 2;
		$flag=true;
		if($this->type_id==2){
			if(($countHours+$this->hours)<=($this->load->getClasses($springSemester-1)+$this->load->getClasses($springSemester-1))){
				$flag=false;
			}
		}
		if ($flag==false){
			$this->addError('',Yii::t('journal','For this type lectures no credit hours'));
		}
//		if($this->hours>0){
//			if($this->audience_id==0){
//				$this->addError('',Yii::t('journal','Record that has hours must has audience'));
//			}
//		}
	}
	/**
	 * @return array relational rules.
	 */
	public function relations()
	{
		// NOTE: you may need to adjust the relation name and the related
		// class name for the relations automatically generated below.
		return array(
			'types'=>array(self::BELONGS_TO,'JournalRecordType','type_id'),
			'marks'=>array(self::HAS_MANY,'Mark','journal_record_id'),
			'teacher'=>array(self::BELONGS_TO,'Teacher','teacher_id'),
			'load'=>array(self::BELONGS_TO,'Load','load_id'),
			'audience'=>array(self::BELONGS_TO,'Audience','audience_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'type_id' => Yii::t('journal','type_id'),
			'date' => Yii::t('journal','Date create'),
			'description' => Yii::t('journal','Theme'),
			'home_work' => Yii::t('journal','Home task'),
			'load_id' => 'По навантаженю',
			'teacher_id' => Yii::t('base','Teacher'),
			'numer_in_day' => Yii::t('journal','Nubmer In Day'),
			'hours'=>Yii::t('journal','Hours count'),
			'audience_id'=>Yii::t('audience', 'Audience'),
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

		$criteria->compare('id',$this->id);
		$criteria->compare('type_id',$this->type_id);
		$criteria->compare('date',$this->date);
		$criteria->compare('description',$this->description,true);
		$criteria->compare('home_work',$this->home_work,true);
		$criteria->compare('load_id',$this->load_id);
		$criteria->compare('teacher_id',$this->teacher_id,true);
		$criteria->compare('n_pp',$this->n_pp,true);
		$criteria->compare('numer_in_day',$this->numer_in_day);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return JournalRecord the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
