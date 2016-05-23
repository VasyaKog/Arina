<?php

class DefaultController extends Controller
{
	/**
	 * @var string the default layout for the views. Defaults to '//layouts/column2', meaning
	 * using two-column layout. See 'protected/views/layouts/column2.php'.
	 */
	public $layout='//layouts/column1';

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
	 * Displays a particular model.
	 * @param integer $id the ID of the model to be displayed
	 */
	public function actionView($id)
	{
		 if (!Yii::app()->user->checkAccess('admin'))
        {
            throw new CHttpException(403, Yii::t('yii', 'You are not authorized to perform this action.'));
        }
        $model = $this->loadModel($id);
        $another_user_name = $model->master_user;   // name of another master user
        if (!empty($another_user_name))             // if it is here...
        {
            $another_user = User::model()->findByAttributes(array('username' => $model->master_user));  // get this user
            if ($another_user->identity_id)                 // find identification number for get more info about he/she
            {
                $another_teacher = Teacher::model()->findByAttributes(array('id' => $another_user->identity_id));
                                                                              // find model about additional information
                $model->another_master_fullname =                                                        //get full name
                    $another_teacher->last_name." ".$another_teacher->first_name." ".$another_teacher->middle_name;
            }
        }
		$this->render('view',array(
			'model'=>$model,
		));
	}

	/**
	 * Creates a new model.
	 * If creation is successful, the browser will be redirected to the 'view' page.
	 */
	public function actionCreate()
	{
		 if (!Yii::app()->user->checkAccess('admin'))
        {
            throw new CHttpException(403, Yii::t('yii', 'You are not authorized to perform this action.'));
        }
		$model=new FileShare;

		if(isset($_POST['FileShare']))
		{
            $model->file_name = CUploadedFile::getInstance($model,'upload_file');
            $model->master_user = null;
			if($model->save())
				$this->redirect(array('view','id'=>$model->id));
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
	public function actionUpdate($id)
	{
		 if (!Yii::app()->user->checkAccess('admin'))
        {
            throw new CHttpException(403, Yii::t('yii', 'You are not authorized to perform this action.'));
        }
		$model=$this->loadModel($id);

		if(isset($_POST['FileShare']))
		{
            if(!$_POST['FileShare']['file_lock'])
                $model->master_user = null;
            else
                $model->master_user = Yii::app()->user->name;

			if($model->save())
				$this->redirect(array('view','id'=>$model->id));
		}

        // Is file used by you or another user?
        if (($model->master_user != null) &&
            ($model->master_user != Yii::app()->user->name))
        {
            Yii::app()->user->setFlash('error', Yii::t('fileShare', 'File using by another user'));
            $this->redirect(array('index'));
        }
        else
        {
            $model->master_user = Yii::app()->user->name;
            $model->save();
            $model->file_lock = true;
		    $this->render('update',array(
		    	'model'=>$model,
		    ));
        }
	}

	/**
	 * Deletes a particular model.
	 * If deletion is successful, the browser will be redirected to the 'admin' page.
	 * @param integer $id the ID of the model to be deleted
	 */
	public function actionDelete($id)
	{
		 if (!Yii::app()->user->checkAccess('admin'))
        {
            throw new CHttpException(403, Yii::t('yii', 'You are not authorized to perform this action.'));
        }
		$this->loadModel($id)->delete();

		// if AJAX request (triggered by deletion via admin grid view), we should not redirect the browser
		if(!isset($_GET['ajax']))
			$this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('admin'));
	}

	/**
	 * Lists all models.
	 */
	public function actionIndex()
	{
		 if (!Yii::app()->user->checkAccess('admin'))
        {
            throw new CHttpException(403, Yii::t('yii', 'You are not authorized to perform this action.'));
        }
		$dataProvider = new CActiveDataProvider('FileShare');
		$this->render('index', array('dataProvider' => $dataProvider));
	}

	/**
	 * Manages all models.
	 */
	public function actionAdmin()

	{
		 if (!Yii::app()->user->checkAccess('admin'))
        {
            throw new CHttpException(403, Yii::t('yii', 'You are not authorized to perform this action.'));
        }
		$model=new FileShare('search');
		$model->unsetAttributes();  // clear any default values
		if(isset($_GET['FileShare']))
			$model->attributes = $_GET['FileShare'];

		$this->render('admin',array(
			'model'=>$model,
		));
	}

	/**
	 * Returns the data model based on the primary key given in the GET variable.
	 * If the data model is not found, an HTTP exception will be raised.
	 * @param integer $id the ID of the model to be loaded
	 * @return FileShare the loaded model
	 * @throws CHttpException
	 */
	public function loadModel($id)
	{
		
		$model=FileShare::model()->findByPk($id);
		if($model===null)
			throw new CHttpException(404,'The requested page does not exist.');
		return $model;
	}

	/**
	 * Performs the AJAX validation.
	 * @param FileShare $model the model to be validated
	 */
	protected function performAjaxValidation($model)
	{
		if(isset($_POST['ajax']) && $_POST['ajax']==='file-share-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}
}
