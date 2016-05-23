<?php
/**
 *
 * This is the model class for table "audience".
 *
 * The followings are the available columns in table 'audience':
 * @property integer $id
 * @property string $number
 * @property string $name
 * @property integer $type
 * @property integer $id_teacher
 *
 * @author Dmytro Karpovych <ZAYEC77@gmail.com>
 */
class Audience extends ActiveRecord
{
    const TYPE_LECTURE = 1;
    const TYPE_LABORATORY = 2;
    const TYPE_WORKSHOP = 3;
    const TYPE_GUM = 4;

    /**
     * Returns the static model of the specified AR class.
     * Please note that you should have this exact method in all your CActiveRecord descendants!
     * @param string $className active record class name.
     * @return Audience the static model class
     */
    public static function model($className = __CLASS__)
    {
        return parent::model($className);
    }

    /**
     * @return string
     */
    public function getTypeName()
    {
        $list = self::getTypeList();
        return $list[$this->type];
    }

    /**
     * Array for dropDownList
     * @return array
     */
    public static function getTypeList()
    {
        return array(
            self::TYPE_LECTURE => Yii::t('audience', 'Lecture'),
            self::TYPE_LABORATORY => Yii::t('audience', 'Laboratory'),
            self::TYPE_WORKSHOP => Yii::t('audience', 'Workshop'),
            self::TYPE_GUM => Yii::t('audience', 'Gum'),
        );
    }

    /**
     * @return string the associated database table name
     */
    public function tableName()
    {
        return 'audience';
    }

    public static function getNumber($id){
        $model=Audience::model()->findByPk($id);
        if(!is_null($model)) return $model->number;
        else return null;
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules()
    {
        return array(
            array('number, type', 'required'),
            array('type', 'numerical', 'integerOnly' => true),
            array('number', 'length', 'max' => 5),
            array('number', 'unique'),
            array('id_teacher', 'safe'),
            array('id, number, type', 'safe', 'on' => 'search'),
        );
    }

    /**
     * @return array relational rules.
     */
    public function relations()
    {
        return array();
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels()
    {
        return array(
            'id' => 'ID',
            'number' => Yii::t('audience', 'Number'),
            'type' => Yii::t('base', 'Type'),
            'typeName' => Yii::t('base', 'Type'),
            'id_teacher' => Yii::t('audience', 'Teacher'),
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
        $criteria = new CDbCriteria;

        $criteria->compare('id', $this->id);
        $criteria->compare('number', $this->number, true);
        $criteria->compare('type', $this->type);

        return new CActiveDataProvider($this, array(
            'criteria' => $criteria,
        ));
    }
}
