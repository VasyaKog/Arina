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
class Active extends ActiveRecord
{
    /**
     * @return string the associated database table name
     */
    public function tableName()
    {
        return 'active';
    }

     public static function getList()
    {
        return CHtml::listData(self::model()->findAll(array('order' => 'id')), 'id', 'name');
    }
    public function relations()
    {
        return array(
            'user' => array(self::HAS_MANY, 'User', 'role'),
            // 'employee' => array(self::BELONGS_TO, 'Employee', 'identity_id'),
            );
    }
     public function search()
    {
        // @todo Please modify the following code to remove attributes that should not be searched.

        $criteria = new CDbCriteria;

        
        $criteria->compare('name', $this->name, true);
        

        return new CActiveDataProvider($this, array(
            'criteria' => $criteria,
            'pagination' => array(
                'pageSize' => 20,
            ),
        ));
    }
    public function attributeLabels()
    {
        return array(
            'name' => Yii::t('active', 'name'),            
            
        
        );
    }

    

  /**
     * @return array relational rules.
     */
    

    /**
     * @return array customized attribute labels (name=>label)
     */
    

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
