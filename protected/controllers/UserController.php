<?php

class UserController extends Controller
{
    public $name = 'users';

    /**
     * @param $view
     * @return bool
     */
    protected function beforeRender($view)
    {
        switch ($view) {
            case 'login':
            case 'restore':
                $this->layout = "//layouts/login";
                break;
        }
        return parent::beforeRender($view);
    }

    /**
     *
     */
    public function actionLogin()
    {
        $model = new ELoginForm();

        $this->ajaxValidation('login-form', $model);

        if (isset($_POST['ELoginForm'])) {
            $model->attributes = $_POST['ELoginForm'];

            $list= Yii::app()->db->createCommand('select * from `user` a where a.username=:name')->bindValue('name',$model->username)->queryAll();

            if ($model->validate() && $model->login() && $list[0]['role']!=1) {
                Yii::app()->request->redirect(Yii::app()->homeUrl);
            }

            if ($model->validate() && $model->login() && $list[0]['role']!=0) {
                Yii::app()->user->logout();
                    throw new CHttpException(403, Yii::t('yii', 'Your account is baned.'));
            }
        }
                

        $this->render(
            'login',
            array('model' => $model,)
        );
    }

    /**
     *
     */
    public function actionLogout()
    {
        Yii::app()->user->logout();
        Yii::app()->request->redirect(Yii::app()->homeUrl);
    }

    /**
     *
     */
    public function actionRestore()
    {
        $model = new ERestorePasswordForm();

        $this->ajaxValidation('restore-form', $model);

        if (isset($_POST['ERestorePasswordForm']['username'])) {
            $username = $_POST['ERestorePasswordForm']['username'];
            $user = User::model()->findByAttributes(array('username' => $username));
            if (isset($user)) {
                
            }
        }

        $this->render(
            'restore',
            array('model' => $model,)
        );
    }

    /**
     * @return array action filters
     */
    public function filters()
    {
        return array(
            'accessControl', // perform access control for CRUD operations
        );
    }

    /**
     * @param $id
     * @throws CHttpException
     */
    public function actionView($id)
    {
        if (!Yii::app()->user->checkAccess('admin')) {
            throw new CHttpException(403, Yii::t('yii', 'You are not authorized to perform this action.'));
        }
        $this->render('view', array(
            'model' => $this->loadModel($id),
        ));
    }

    /**
     * Creates a new model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     */
    public function actionCreate()
    {
        if (!Yii::app()->user->checkAccess('admin')) {
            throw new CHttpException(403, Yii::t('yii', 'You are not authorized to perform this action.'));
        }
        $model = new User;
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
        ));
    }

    /**
     * @param $id
     * @throws CHttpException
     */
    public function actionUpdate($id)
    {    
        /** @var User $model */
        $model = $this->loadModel($id);
        $model->password = '';     

    
        if ($model->id!=Yii::app()->user->id && !Yii::app()->user->checkAccess('admin')) {
            throw new CHttpException(403, Yii::t('yii', 'You are not authorized to perform this action.'));
        }
        //$this->performAjaxValidation('user-form', $model);
        $arr_role = RolesModel::getListNames();

        if (isset($_POST['User'])) {
            $model->attributes = $_POST['User'];
           
           
           
            if ($model->save()){

                if (Yii::app()->user->checkAccess('admin')) { 
                     $i_t = $_POST['User']['identity_type'];
                    $model->identity_type=$i_t;
                 Yii::app()->db->createCommand("UPDATE `AuthAssignment` SET `itemname`= '".$arr_role[$i_t]."' WHERE `userid`='".$model->id."'")->query();
             }
                
                Yii::app()->getUser()->setFlash('success','Дані були успішно змінені');
                $this->redirect(array('update', 'id' => $model->id));
            }
        }
    

        $this->render('update', array(
            'model' => $model,
        ));
    }

    /**
     * @param $id
     * @throws CHttpException
     */
    public function actionDelete($id)
    {
        if (!Yii::app()->user->checkAccess('admin')) {
            throw new CHttpException(403, Yii::t('yii', 'You are not authorized to perform this action.'));
        }
        if (Yii::app()->request->isPostRequest) {
            $this->loadModel($id)->delete();
                Yii::app()->db->createCommand("Delete From `AuthAssignment` WHERE `userid`='".$id."'")->query();
            if (!isset($_GET['ajax']))
                $this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('admin'));
        } else
            throw new CHttpException(400, 'Invalid request. Please do not repeat this request again.');
    }

    /**
     * Lists all models.
     */
    public function actionIndex()
    {
        $dataProvider = new CActiveDataProvider('User');
        $this->render('index', array(
            'dataProvider' => $dataProvider,
        ));        
    }

    /**
     * Manages all models.
     */
    public function actionAdmin()
    {
        if (!Yii::app()->user->checkAccess('admin')) {
            throw new CHttpException(403, Yii::t('yii', 'You are not authorized to perform this action.'));
        }
        $model = new User('search');
        $model->unsetAttributes(); // clear any default values
        if (isset($_GET['User']))
            $model->attributes = $_GET['User'];

        $this->render('admin', array(
            'model' => $model,
        ));
    }

    /**
     * @param $id
     * @return array|CActiveRecord|mixed|null
     * @throws CHttpException
     */
    public function loadModel($id)
    {
        $model = User::model()->findByPk($id);
        if ($model === null)
            throw new CHttpException(404, 'The requested page does not exist.');
        return $model;
    }

    public function accessRules()
    {
        return CMap::mergeArray(
            array(
                array('allow',
                    'actions' => array('login', 'restore'),
                    'users' => array('*'),
                ),
            ),
            parent::accessRules()
        );
    }
}
