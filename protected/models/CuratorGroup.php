<?php

/**
 * This is the model class for table "curator_group".
 *
 * The followings are the available columns in table 'curator_group':
 * @property string $id
 * @property string $date
 * @property integer $type
 * @property string $teacher_id
 * @property string $group_id
 * @property string $comment
 */
class CuratorGroup extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'curator_group';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('date, type, teacher_id, group_id, comment', 'required'),
			array('type', 'numerical', 'integerOnly'=>true),
			array('teacher_id, group_id', 'length', 'max'=>10),
			array('comment', 'length', 'max'=>255),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, date, type, teacher_id, group_id, comment', 'safe', 'on'=>'search'),
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
			'curator'=>array(self::BELONGS_TO,'Teacher','teacher_id'),
			'group'=>array(self::BELONGS_TO,'Group','group_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'date' => Yii::t('studentGroup','Date record'),
			'type' => 'Type',
			'teacher_id' => 'Teacher',
			'group_id' => 'Group',
			'comment' => Yii::t('studentGroup','Comment'),
		);
	}

	public function getType()
	{
		return ($this->type) ? Yii::t('terms','Accepted'): Yii::t('terms','Deaccepted');
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
		$criteria->compare('date',$this->date,true);
		$criteria->compare('type',$this->type);
		$criteria->compare('teacher_id',$this->teacher_id,true);
		$criteria->compare('group_id',$this->group_id,true);
		$criteria->compare('comment',$this->comment,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return CuratorGroup the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
