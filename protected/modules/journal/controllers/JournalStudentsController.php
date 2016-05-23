<?php

class JournalStudentsController extends Controller
{
    /**
     * @var string the default layout for the views. Defaults to '//layouts/column2', meaning
     * using two-column layout. See 'protected/views/layouts/column2.php'.
     */
    public $layout='//layouts/column2';

    /**
     * @return array action filters
     */
    public function filters()
    {
        return array(
            'accessControl', // perform access control for CRUD operations
            'postOnly + delete', // we only allow deletion via POST request
        );
    }

    /**
     * Specifies the access control rules.
     * This method is used by the 'accessControl' filter.
     * @return array access control rules
     */
//    public function accessRules()
//    {
//        return array(
//            array('allow',  // allow all users to perform 'index' and 'view' actions
//                'actions'=>array('index','view'),
//                'users'=>array('*'),
//            ),
//            array('allow', // allow authenticated user to perform 'create' and 'update' actions
//                'actions'=>array('create','update'),
//                'users'=>array('@'),
//            ),
//            array('allow', // allow admin user to perform 'admin' and 'delete' actions
//                'actions'=>array('admin','delete'),
//                'users'=>array('admin'),
//            ),
//            array('deny',  // deny all users
//                'users'=>array('*'),
//            ),
//        );
//    }

    /**
     * Displays a particular model.
     * @param integer $id the ID of the model to be displayed
     */
    public function actionView($id)
    {
        
        $this->render('view',array(
            'model'=>$this->loadModel($id),
        ));
    }

    /**
     * Creates a new model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     */
    public function actionCreate($id)
    {
        $model=new JournalStudents;
        $model->load_id=$id;
        $load=Load::model()->findByPk($id);
        $access=false;
        if (isset(Yii::app()->user->identityType)) {
            if (isset(Yii::app()->user->identityId)) {
                if (Yii::app()->user->identityType == User::TYPE_SUPER) {
                    $access=true;
                    /**
                     * @var $teacher Teacher
                     */
                    $teacher=Teacher::model()->findByPk(Yii::app()->user->identityId);
                    if(in_array($load->group_id,$teacher->getGroupListArray())) {
                        $access = true;
                    }
                }
                if (Yii::app()->user->identityType == User::TYPE_TEACHER) {
                    /**
                     * @var $teacher Teacher
                     */
                    $teacher=Teacher::model()->findByPk(Yii::app()->user->identityId);
                    if(in_array($load->group_id,$teacher->getGroupListArray())) {
                        $access = true;
                    }
                }
                if (Yii::app()->user->identityType == User::TYPE_STUDENT) {
                    $student=Student::model()->findByPk(Yii::app()->user->identityId);
                    /** @var $group Group*/
                    $group=Group::model()->findByPk($load->group_id);
                    if($student->id==$group->monitor_id) $access=true;
                }
            }
            if (Yii::app()->user->identityType == User::TYPE_INSPECTOR) {
                $access=true;
                if(in_array($load->group_id,$teacher->getGroupListArray())) {
                    $access = true;
                }
            }
            if (Yii::app()->user->identityType == User::TYPE_NAVCH) {
                $access=true;
                $teacher=Teacher::model()->findByPk(Yii::app()->user->identityId);
                if(in_array($load->group_id,$teacher->getGroupListArray())) {
                    $access = true;
                    $t = false;
                }
            }
            if (Yii::app()->user->identityType == User::TYPE_ZASTUPNIK) {
                $access=true;
                $teacher=Teacher::model()->findByPk(Yii::app()->user->identityId);
                if(in_array($load->group_id,$teacher->getGroupListArray())) {
                    $access = true;
                }
            }
            if (Yii::app()->user->identityType == User::TYPE_DIRECTOR) {
                $access=true;
                $teacher=Teacher::model()->findByPk(Yii::app()->user->identityId);
                if(in_array($load->group_id,$teacher->getGroupListArray())) {
                    $access = true;
                }
            }
        }
        if(!$access)
        {
            throw new CHttpException(403, Yii::t('yii','You are not authorized to perform this action.'));
        }

        $model->date=date('Y-m-d');
        // Uncomment the following line if AJAX validation is needed
        // $this->performAjaxValidation($model);

        if(isset($_POST['JournalStudents']))
        {
            $model->attributes=$_POST['JournalStudents'];
            if($model->save())
                $this->redirect(array('/journal/journalStudents/','id'=>$model->load_id));
        }

        $this->render('create',array(
            'model'=>$model,
        ));
    }

    /**
     * Updates a particular model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id the ID of the model to be updated
     */
  /*  public function actionUpdate($id)
    {
        $model=$this->loadModel($id);

        // Uncomment the following line if AJAX validation is needed
        // $this->performAjaxValidation($model);

        if(isset($_POST['JournalStudents']))
        {
            $model->attributes=$_POST['JournalStudents'];
            if($model->save())
                $this->redirect(array('view','id'=>$model->id));
        }

        $this->render('update',array(
            'model'=>$model,
        ));
    }
*/
    /**
     * Deletes a particular model.
     * If deletion is successful, the browser will be redirected to the 'admin' page.
     * @param integer $id the ID of the model to be deleted
     */
    public function actionDelete($id)
    {
        $model=$this->loadModel($id);

        $load=Load::model()->findByPk($model->load_id);
        $access=false;
        if (isset(Yii::app()->user->identityType)) {
            if (isset(Yii::app()->user->identityId)) {
                if (Yii::app()->user->identityType == User::TYPE_SUPER) {
                    $access=true;
                    /**
                     * @var $teacher Teacher
                     */
                    $teacher=Teacher::model()->findByPk(Yii::app()->user->identityId);
                    if(in_array($load->group_id,$teacher->getGroupListArray())) {
                        $access = true;
                    }
                }
                if (Yii::app()->user->identityType == User::TYPE_TEACHER) {
                    /**
                     * @var $teacher Teacher
                     */
                    $teacher=Teacher::model()->findByPk(Yii::app()->user->identityId);
                    if(in_array($load->group_id,$teacher->getGroupListArray())) {
                        $access = true;
                    }
                }
                if (Yii::app()->user->identityType == User::TYPE_STUDENT) {
                    $student=Student::model()->findByPk(Yii::app()->user->identityId);
                    /** @var $group Group*/
                    $group=Group::model()->findByPk($load->group_id);
                    if($student->id==$group->monitor_id) $access=true;
                }
            }
            if (Yii::app()->user->identityType == User::TYPE_INSPECTOR) {
                $access=true;
                if(in_array($load->group_id,$teacher->getGroupListArray())) {
                    $access = true;
                }
            }
            if (Yii::app()->user->identityType == User::TYPE_NAVCH) {
                $access=true;
                $teacher=Teacher::model()->findByPk(Yii::app()->user->identityId);
                if(in_array($load->group_id,$teacher->getGroupListArray())) {
                    $access = true;
                    $t = false;
                }
            }
            if (Yii::app()->user->identityType == User::TYPE_ZASTUPNIK) {
                $access=true;
                $teacher=Teacher::model()->findByPk(Yii::app()->user->identityId);
                if(in_array($load->group_id,$teacher->getGroupListArray())) {
                    $access = true;
                }
            }
            if (Yii::app()->user->identityType == User::TYPE_DIRECTOR) {
                $access=true;
                $teacher=Teacher::model()->findByPk(Yii::app()->user->identityId);
                if(in_array($load->group_id,$teacher->getGroupListArray())) {
                    $access = true;
                }
            }
        }
        if(!$access)
        {
            throw new CHttpException(403, Yii::t('yii','You are not authorized to perform this action.'));
        }
        $this->loadModel($id)->delete();

        // if AJAX request (triggered by deletion via admin grid view), we should not redirect the browser
        if(!isset($_GET['ajax']))
            $this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('admin'));
    }

    /**
     * Lists all models.
     */
    /**
     * @param $load_id integer
     */
    public function actionIndex($id)
    {
        /**
         * @var $load Load
         */

        $mas=JournalStudents::model()->findAllByAttributes(array('load_id'=>$id));
        $load=Load::model()->findByPk($id);
        $access=false;
        if (isset(Yii::app()->user->identityType)) {
            if (isset(Yii::app()->user->identityId)) {
                if (Yii::app()->user->identityType == User::TYPE_SUPER) {
                    $access=true;
                    /**
                     * @var $teacher Teacher
                     */
                    $teacher=Teacher::model()->findByPk(Yii::app()->user->identityId);
                    if ($load->teacher_id == Yii::app()->user->identityId) {
                        $access = true;
                    } elseif(in_array($load->group_id,$teacher->getGroupListArray())) {
                        $access = true;
                    }
                }
                if (Yii::app()->user->identityType == User::TYPE_TEACHER) {
                    /**
                     * @var $teacher Teacher
                     */
                    $teacher=Teacher::model()->findByPk(Yii::app()->user->identityId);
                    if ($load->teacher_id == Yii::app()->user->identityId) {
                        $access = true;
                    } elseif(in_array($load->group_id,$teacher->getGroupListArray())) {
                        $access = true;
                    }
                }
                if (Yii::app()->user->identityType == User::TYPE_STUDENT) {
                    $student=Student::model()->findByPk(Yii::app()->user->identityId);
                    /** @var $group Group*/
                        $group=Group::model()->findByPk($load->group_id);
                        if($student->id==$group->monitor_id) $access=true;
                    }
                }
                if (Yii::app()->user->identityType == User::TYPE_INSPECTOR) {
                    $access=true;
                    $teacher=Teacher::model()->findByPk(Yii::app()->user->identityId);
                    if ($load->teacher_id == Yii::app()->user->identityId) {
                        $access = true;
                    } elseif(in_array($load->group_id,$teacher->getGroupListArray())) {
                        $access = true;
                    }
                }
                if (Yii::app()->user->identityType == User::TYPE_NAVCH) {
                    $access=true;
                    $teacher=Teacher::model()->findByPk(Yii::app()->user->identityId);
                    if ($load->teacher_id == Yii::app()->user->identityId) {
                        $access = true;
                    } elseif(in_array($load->group_id,$teacher->getGroupListArray())) {
                        $access = true;
                        $t = false;
                    }
                }
                if (Yii::app()->user->identityType == User::TYPE_ZASTUPNIK) {
                    $access=true;
                    $teacher=Teacher::model()->findByPk(Yii::app()->user->identityId);
                    if ($load->teacher_id == Yii::app()->user->identityId) {
                        $access = true;
                    } elseif(in_array($load->group_id,$teacher->getGroupListArray())) {
                        $access = true;
                    }
                }
                if (Yii::app()->user->identityType == User::TYPE_DIRECTOR) {
                    $access=true;
                    $teacher=Teacher::model()->findByPk(Yii::app()->user->identityId);
                    if ($load->teacher_id == Yii::app()->user->identityId) {
                        $access = true;
                    } elseif(in_array($load->group_id,$teacher->getGroupListArray())) {
                        $access = true;
                    }
                }
            }
        if(!$access)
        {
            throw new CHttpException(403, Yii::t('yii','You are not authorized to perform this action.'));
        }
        $dataProvider=new CArrayDataProvider($mas);
        $this->render('index',array(
            'dataProvider'=>$dataProvider,
            'load_id'=>$id,
        ));
    }

    /**
     * Manages all models.
     */
  /*  public function actionAdmin()
    {
        $model=new JournalStudents('search');
        $model->unsetAttributes();  // clear any default values
        if(isset($_GET['JournalStudents']))
            $model->attributes=$_GET['JournalStudents'];

        $this->render('admin',array(
            'model'=>$model,
        ));
    }
*/
    public function actionChangeStudentList()
    {
        /** @var $students Student[] */
        /** @var $load Load */
        $load = Load::model()->findByPk($_POST['JournalStudents']['load_id']);
        $students = Group::model()->getArrayStudentByGroupId($load->group_id);
        $type = $_POST['JournalStudents']['type'];
        foreach ($students as $student) {
            if ($type == 0) {
               if (in_array($load->id, $student->getListArray())) {
                    echo CHtml::tag('option', array('value' => $student->id), $student->getFullNameAndCode(), true);
                }
            } else {
                if ($type == 1) {
                    if (!in_array($load->id, $student->getListArray())) {
                        echo CHtml::tag('option', array('value' => $student->id), $student->getFullNameAndCode(), true);
                    }
                }
            }
        }
    }

    /**
     * Returns the data model based on the primary key given in the GET variable.
     * If the data model is not found, an HTTP exception will be raised.
     * @param integer $id the ID of the model to be loaded
     * @return JournalStudents the loaded model
     * @throws CHttpException
     */
    public function loadModel($id)
    {
        $model=JournalStudents::model()->findByPk($id);
        if($model===null)
            throw new CHttpException(404,'The requested page does not exist.');
        return $model;
    }

    /**
     * Performs the AJAX validation.
     * @param JournalStudents $model the model to be validated
     */
    protected function performAjaxValidation($model)
    {
        if(isset($_POST['ajax']) && $_POST['ajax']==='journal-students-form')
        {
            echo CActiveForm::validate($model);
            Yii::app()->end();
        }
    }
}
