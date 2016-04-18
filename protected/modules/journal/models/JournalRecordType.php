<?php

/**
 * This is the model class for table "journal_record_type".
 *
 * The followings are the available columns in table 'journal_record_type':
 * @property string $id
 * @property string $title
 * @property boolean $description
 * @property boolean $homework
 * @property boolean $present
 * @property boolean $date
 * @property boolean $n_pp
 * @property boolean $ticket
 * @property integer $hours
 * @property boolean $reports
 * @property string $title_report
 */
class JournalRecordType extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'journal_record_type';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('title', 'required'),
			array('hours','numerical', 'integerOnly' => true),
			array('description, homework, present, date, n_pp, ticket,  reports', 'boolean'),
			array('title', 'length', 'max'=>15),
			array('title_report', 'length', 'max'=>20),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, title, description, homework, present, date, n_pp, ticket, hours, reports, title_report', 'safe', 'on'=>'search'),
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
			'records'=>array(self::HAS_MANY,'JournalRecord','type_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'title' => 'Title',
			'description' => 'Description',
			'homework' => 'Homework',
			'present' => 'Present',
			'date' => 'Date',
			'n_pp' => 'N Pp',
			'ticket' => 'Ticket',
			'hours' => 'Hours',
			'reports' => 'Reports',
			'title_report' => 'Title Report',
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
		$criteria->compare('title',$this->title,true);
		$criteria->compare('description',$this->description);
		$criteria->compare('homework',$this->homework);
		$criteria->compare('present',$this->present);
		$criteria->compare('date',$this->date);
		$criteria->compare('n_pp',$this->n_pp);
		$criteria->compare('ticket',$this->ticket);
		$criteria->compare('hours',$this->hours);
		$criteria->compare('reports',$this->reports);
		$criteria->compare('title_report',$this->title_report,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return JournalRecordType the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
