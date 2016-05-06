<?php

/**
 * This is the model class for table "actual_class".
 *
 * The followings are the available columns in table 'actual_class':
 * @property integer $id
 * @property string $name
 * @property string $title
 *
 */
class RolesModel extends ActiveRecord
{
    /**
     * @return string the associated database table name
     */
    public function tableName()
    {
        return 'roles';
    }

     public static function getList()
    {
        return CHtml::listData(self::model()->findAll(array('order' => 'id')), 'id', 'title');
    }

    public static function getListNames()
    {
        return CHtml::listData(self::model()->findAll(array('order' => 'id')), 'id', 'name');
    }

  /**
     * @return array relational rules.
     */
    public function relations()
    {
        return array(
            'user' => array(self::HAS_MANY, 'User', 'identity_type'),
            // 'employee' => array(self::BELONGS_TO, 'Employee', 'identity_id'),
            );
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels()
    {
        return array(
            'id' => Yii::t('role', 'ID'),            
            'name' => Yii::t('role', 'Name'),
            'title' => Yii::t('role', 'Title'),
        );
    }

    /**
     * Returns the static model of the specified AR class.
     * Please note that you should have this exact method in all your CActiveRecord descendants!
     * @param string $className active record class name.
     * @return ActualClass the static model class
     */
    public static function model($className = __CLASS__)
    {
        return parent::model($className);
    }
}
