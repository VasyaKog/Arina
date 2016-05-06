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
class AuthAssignModel extends ActiveRecord
{
    /**
     * @return string the associated database table name
     */
    public function tableName()
    {
        return 'AuthAssignment';
    }

     public static function getList()
    {
        return CHtml::listData(self::model()->findAll(array('order' => 'id')), 'id', 'itemname');
    }

  /**
     * @return array relational rules.
     */
    public function relations()
    {
        return array(
            //'user' => array(self::BELONGS_TO, 'User', 'userid'),
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
