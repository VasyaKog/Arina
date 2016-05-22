<?php

/**
 * This is the model class for table "user".
 *
 * The followings are the available columns in table 'user':
 * @property integer $id
 * @property string $username
 * @property string $password
 * @property string $email
 * @property integer $role
 * @property integer $identity_id
 * @property integer $identity_type
 */
class User extends ActiveRecord
{
    protected $oldPasswordHash = null;

    const ROLE_GUEST = 0;
    const ROLE_ADMIN = 1;
    const ROLE_TEACHER = 2;
    const ROLE_STUDENT = 3;
    const ROLE_PREFECT = 4;
    const ROLE_CURATOR = 5;
    const ROLE_DEPARTMENT_HEAD = 6;
    const ROLE_ROOT = 7;

    const TYPE_STUDENT = 1;
    const TYPE_PREFECT = 2;
    const TYPE_TEACHER = 3;
    const TYPE_CYCHEAD = 4;
    const TYPE_INSPECTOR = 5;
    const TYPE_NAVCH = 6;
    const TYPE_DEPHEAD = 7;
    const TYPE_ZASTUPNIK = 8;
    const TYPE_DIRECTOR = 9;
    const TYPE_SUPER = 10;



    

    /**
     * @return string the associated database table name
     */
    public function tableName()
    {
        return 'user';
    }

    /**
     * Save current password
     */
    protected function afterFind()
    {
        $this->oldPasswordHash = $this->password;
        parent::afterFind();
    }

    protected function beforeSave()
    {
        if (!empty($this->password) && ($this->password != $this->oldPasswordHash)) {
            $this->password = md5($this->password);
        } else {
            $this->password = $this->oldPasswordHash;
        }
        return parent::beforeSave();
    }

    public static function getRoleList()
    {
        return array(
            self::ROLE_GUEST => Yii::t('user', 'Guest'),
        );
    }

    public function getName(){
        //var_dump($this);
        return (($this->identity_type==1) || ($this->identity_type==2))?$this->student->getFullName():$this->employee->getFullName();
       }
    

    /**
     * @return array validation rules for model attributes.
     */
    public function rules()
    {
        return array(
            array('username', 'required'),
            array('password', 'required', 'on' => 'insert'),
            array('username', 'unique', 'message' => 'Користувач з таким іменем вже існує.'),
            array('role, identity_id', 'numerical', 'integerOnly' => true),
            array('username, password, email', 'length', 'max' => 255),
            array('email', 'email'),
            array('id, username, password, email, role, identity_id', 'safe', 'on' => 'search'),
        );
    }

    /**
     * @return array relational rules.
     */
    public function relations()
    {
        return array(
            'employee' => array(self::BELONGS_TO, 'Employee', 'identity_id'),
            'roles' => array(self::BELONGS_TO, 'RolesModel', 'identity_type'),            
            'active' => array(self::BELONGS_TO, 'Active', 'role'),
            'student' => array(self::BELONGS_TO, 'Student', 'identity_id'),
            
            );
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels()
    {
        return array(
            'id' => 'ID',
            'username' => Yii::t('user', 'Username'),
            'password' => Yii::t('user', 'Password'),
            'email' => Yii::t('user', 'Email'),
            'role' => Yii::t('user', 'role'),
            'identity_id' => Yii::t('user', 'Identity ID'),
            'identity_type' => Yii::t('user', 'Identity Type'),
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
        $criteria->compare('username', $this->username, true);
        //$criteria->compare('password', $this->password, true);
        //$criteria->compare('email', $this->email, true);
        //$criteria->compare('title', $model->user, true);
        //$criteria->compare('', $this->roles->title);       
       // $criteria->compare('identity_type', $this->identity_type);        
        return new CActiveDataProvider($this, array(
            'criteria' => $criteria,            
        ));      
    }

    /**
     * Returns the static model of the specified AR class.
     * Please note that you should have this exact method in all your CActiveRecord descendants!
     * @param string $className active record class name.
     * @return User the static model class
     */
    public static function model($className = __CLASS__)
    {
        return parent::model($className);
    }


}