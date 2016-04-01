<?php

/**
 * This is the model class for table "student_group".
 *
 * The followings are the available columns in table 'student_group':
 * @property integer $id
 * @property string $date
 * @property integer $type
 * @property integer $group_id
 * @property integer $student_id
 * @property string $comment
 */
class StudentGroup extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'student_group';
	}

	/**
	 * @return array types.
	 */

	public function getTypes(){
		return $this->type? Yii::t('terms','included'):Yii::t('terms','exclude');
	}


	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('date, type, group_id, student_id, comment', 'required'),
			array('type', 'numerical', 'integerOnly'=>true),
			array('group_id, student_id', 'length', 'max'=>10),
			array('comment', 'length', 'max'=>255),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, date, type, group_id, student_id, comment', 'safe', 'on'=>'search'),
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
			'student'=> array(self::BELONGS_TO,'Student','student_id'),
			'group' => array(self::BELONGS_TO,'Group','group_id'),
		);
	}



	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => Yii::t('studentGroup','ID'),
			'date' => Yii::t('studentGroup','Date record'),
			'type' => Yii::t('studentGroup','Type action'),
			'group_id' => Yii::t('studentGroup','Group'),
			'student_id' => Yii::t('studentGroup','Student'),
			'comment' => Yii::t('studentGroup','Comment'),
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
	protected function beforeSave() {
		if(parent::beforeSave()) {
			$this->date = date('Y-m-d', strtotime($this->date));//strtotime($this->date_start);
			return true;
		} else {
			return false;
		}
	}
	public function search()
	{
		// @todo Please modify the following code to remove attributes that should not be searched.

		$criteria=new CDbCriteria;

		$criteria->compare('id',$this->id,true);
		$criteria->compare('date',$this->date,true);
		$criteria->compare('type',$this->type);
		$criteria->compare('group_id',$this->group_id,true);
		$criteria->compare('student_id',$this->student_id,true);
		$criteria->compare('comment',$this->comment,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return StudentGroup the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

}
