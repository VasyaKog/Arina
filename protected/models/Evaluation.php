<?php

/**
 * This is the model class for table "Evaluation".
 *
 * The followings are the available columns in table 'Evaluation':
 * @property integer $id
 * @property string $title
 * @property integer $system_id
 *
 * @property EvaluationSystem $evaluation_system
 */
class Evaluation extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'Evaluation';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('title, system_id', 'required'),
			array('title, system_id', 'length', 'max'=>11),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, title, system_id', 'safe', 'on'=>'search'),
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
			'evaluation_system'=>array(self::BELONGS_TO,'EvaluationSystem','system_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'title' => Yii::t('journal','Title'),
			'system_id' => Yii::t('journal','Evaluation System'),
		);
	}

	public static function getTitle($id)
	{
		/**
		 * @var $mark Evaluation
		 */
		$mark=Evaluation::model()->findByPk($id);
		return $mark->title;
	}

	public static function getListBySystemId($id){
		/**
		 * @var $arrayAll Evaluation[]
		 */
		/**
		 * @var $list array[];
		 */
		$list=array();
		$arrayAll=self::model()->findAllByAttributes(array('system_id'=>$id));
		foreach ($arrayAll as $key){
			$list[$key->id]=$key->title;
		}
		return $list;
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
		$criteria->compare('system_id',$this->system_id,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return Evaluation the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
