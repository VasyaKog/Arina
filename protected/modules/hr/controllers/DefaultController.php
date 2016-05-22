<?php

class DefaultController extends Controller
{
    public function actionView($id)
    {

        if(!Yii::app()->user->checkAccess('inspector')&&!Yii::app()->user->checkAccess('admin')&&!Yii::app()->user->checkAccess('director')&&!Yii::app()->user->checkAccess('zastupnik')&&!Yii::app()->user->checkAccess('dephead'))
        {
            throw new CHttpException(403, Yii::t('yii','You are not authorized to perform this action.'));
        }
        $this->render('view', array(
            'model' => Employee::model()->loadContent($id),
        ));
    }

    public function actionExcelList()
    {
        if(!Yii::app()->user->checkAccess('inspector')&&!Yii::app()->user->checkAccess('admin')&&!Yii::app()->user->checkAccess('director')&&!Yii::app()->user->checkAccess('zastupnik')&&!Yii::app()->user->checkAccess('dephead'))
        {
            throw new CHttpException(403, Yii::t('yii','You are not authorized to perform this action.'));
        }
        /**@var $excel ExcelMaker */
        $excel = Yii::app()->getComponent('excel');
        $excel->getDocument(NULL, 'employeesList');
    }


    public function actionIndex()
    {

             if(!Yii::app()->user->checkAccess('admin')
                &&
                !Yii::app()->user->checkAccess('inspector')&&
                !Yii::app()->user->checkAccess('director')&&
                !Yii::app()->user->checkAccess('zastupnik'))
            {
                throw new CHttpException(403, Yii::t('yii','You are not authorized to perform this action.'));
            }
        $model = new Employee('search');
        $model->unsetAttributes(); // clear any default values
        if (isset($_GET['Employee'])) {
            $model->attributes = $_GET['Employee'];
        }

        $this->render('index', array(
            'model' => $model,
        ));
    }

    /**
     * Creates a new model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     */
    public function actionCreate()
    {

        if(!Yii::app()->user->checkAccess('inspector')&&!Yii::app()->user->checkAccess('admin'))
        {
            throw new CHttpException(403, Yii::t('yii','You are not authorized to perform this action.'));
        }
        
        /*$model = new User();
        $arr_role = RolesModel::getListNames();

        if (isset($_POST['User'])) {
            $model->attributes = $_POST['User'];
            $i_t = $_POST['User']['identity_type'];
         
            $model->identity_type=$i_t;
            $model->role=1;
            if ($model->save()){
                
                 Yii::app()->db->createCommand("INSERT INTO `AuthAssignment`(`itemname`, `userid`) VALUES ('".$arr_role[$i_t]."','".$model->id."')")->query();
                
                  $this->redirect(array('view', 'id' => $model->id));
            }
        }
        $this->render('create', array(
            'model' => $model,
        ));*/


        $model = new Employee();
        $this->ajaxValidation('employee-form', $model);

        if (isset($_POST['Employee'])) {
            $model->setAttributes($_POST['Employee']);
            $model->short_name = $model->getShortName();
             if(!Yii::app()->user->checkAccess('admin')&&!Yii::app()->user->checkAccess('inspector')
            )
            {
                throw new CHttpException(403, Yii::t('yii','You are not authorized to perform this action.'));
            }
            if ($model->save()) {
                $this->redirect(array('index', 'id' => $model->id));
            }
        }

       

        $this->render('create', array(
            'model' => $model,
        ));
    }

    /*$model = new User;
        $arr_role = RolesModel::getListNames();
        //$this->performAjaxValidation('user-form', $model);

        if (isset($_POST['User'])) {
            $model->attributes = $_POST['User'];
            $i_t = $_POST['User']['identity_type'];
         
            $model->identity_type=$i_t;
            if ($model->save()){
                
                 Yii::app()->db->createCommand("INSERT INTO `AuthAssignment`(`itemname`, `userid`) VALUES ('".$arr_role[$i_t]."','".$model->id."')")->query();
                
                  $this->redirect(array('view', 'id' => $model->id));
            }
        }

        $this->render('create', array(
            'model' => $model,
        ));*/

    public function actionUpdate($id)
    {
        /**
         * @var $model Student
         */
        if(!Yii::app()->user->checkAccess('inspector')&&!Yii::app()->user->checkAccess('admin'))
        {
            throw new CHttpException(403, Yii::t('yii','You are not authorized to perform this action.'));
        }
        $model = Employee::model()->loadContent($id);

        $this->ajaxValidation('student-form', $model);

        if (isset($_POST['Employee'])) {
            $model->attributes = $_POST['Employee'];
            if(!Yii::app()->user->checkAccess('inspector')&&!Yii::app()->user->checkAccess('admin'))
        {
            throw new CHttpException(403, Yii::t('yii','You are not authorized to perform this action.'));
        }
            if ($model->save())
                $this->redirect(array('view', 'id' => $model->id));
        }

        $this->render('update', array(
            'model' => $model,
        ));
    }


    public function actionDelete($id)
    {
        if(!Yii::app()->user->checkAccess('admin'))
        {
            throw new CHttpException(403, Yii::t('yii','You are not authorized to perform this action.'));
        }
        $model = Employee::model()->loadContent($id);
        if(!Yii::app()->user->checkAccess('admin'))
        {
            throw new CHttpException(403, Yii::t('yii','You are not authorized to perform this action.'));
        }
        $model->delete();
        // if AJAX request (triggered by deletion via admin grid view), we should not redirect the browser
        if (!isset($_GET['ajax']))
            $this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('admin'));
    }
}