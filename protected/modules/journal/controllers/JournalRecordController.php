<?php

class JournalRecordController extends Controller
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
/*	public function accessRules()
	{
		return array(
			array('allow',  // allow all users to perform 'index' and 'view' actions
				'actions'=>array('index','views'),
				'users'=>array('*'),
			),
			array('allow', // allow authenticated user to perform 'create' and 'update' actions
				'actions'=>array('create','update'),
				'users'=>array('@'),
			),
			array('allow', // allow admin user to perform 'admin' and 'delete' actions
				'actions'=>array('admin','delete'),
				'users'=>array('admin'),
			),
			array('deny',  // deny all users
				'users'=>array('*'),
			),
		);
	}
*/

	/**
	 * Displays a particular model.
	 * @param integer $id the ID of the model to be displayed
	 */
	public function actionViews($id)
	{
		$model =$this->loadModel($id);
		$access=false;
		if (isset(Yii::app()->user->identityType)) {
			if (isset(Yii::app()->user->identityId)) {
				if (Yii::app()->user->identityType == User::TYPE_SUPER) {
					$access=true;
					$teacher=Teacher::model()->findByPk(Yii::app()->user->identityId);
					if ($model->teacher_id == Yii::app()->user->identityId) {
						$access = true;
					} elseif(in_array($model->group_id,$teacher->getGroupListArray())) {
						$access = true;
					}
				}
				if (Yii::app()->user->identityType == User::TYPE_TEACHER) {
					/**
					 * @var $teacher Teacher
					 */
					$teacher=Teacher::model()->findByPk(Yii::app()->user->identityId);
					if ($model->load->teacher_id == Yii::app()->user->identityId) {
						$access = true;
					} elseif(in_array($model->load->group_id,$teacher->getGroupListArray())) {
						$access = true;
					}
				}
				if (Yii::app()->user->identityType == User::TYPE_STUDENT) {
					$student=Student::model()->findByPk(Yii::app()->user->identityId);
					if(in_array($model->load->group_id,$student->getGroupListArray())&&in_array($student->id,JournalStudents::getAllStudentsInArray($model->journal_record->load))){
						$access=true;
						$t=false;
					} else {
						/** @var $group Group*/
						$group=Group::model()->findByPk($model->load->group_id);
						if($student->id==$group->monitor_id) $access=true;
					}
				}
				if (Yii::app()->user->identityType == User::TYPE_INSPECTOR) {
					$access=true;
					$teacher=Teacher::model()->findByPk(Yii::app()->user->identityId);
					if ($model->teacher_id == Yii::app()->user->identityId) {
						$access = true;
					} elseif(in_array($model->group_id,$teacher->getGroupListArray())) {
						$access = true;
					}
				}
				if (Yii::app()->user->identityType == User::TYPE_NAVCH) {
					$access=true;
					$teacher=Teacher::model()->findByPk(Yii::app()->user->identityId);
					if ($model->load->teacher_id == Yii::app()->user->identityId) {
						$access = true;
					} elseif(in_array($model->load->group_id,$teacher->getGroupListArray())) {
						$access = true;
					}
				}
				if (Yii::app()->user->identityType == User::TYPE_ZASTUPNIK) {
					$access=true;
					$teacher=Teacher::model()->findByPk(Yii::app()->user->identityId);
					if ($model->load->teacher_id == Yii::app()->user->identityId) {
						$access = true;
					} elseif(in_array($model->load->group_id,$teacher->getGroupListArray())) {
						$access = true;
					}
				}
				if (Yii::app()->user->identityType == User::TYPE_DIRECTOR) {
					$access=true;
					$teacher=Teacher::model()->findByPk(Yii::app()->user->identityId);
					if ($model->load->teacher_id == Yii::app()->user->identityId) {
						$access = true;
					} elseif(in_array($model->load->group_id,$teacher->getGroupListArray())) {
						$access = true;
					}
				}
			}
		}
		if(!$access)
		{
			throw new CHttpException(403, Yii::t('yii','You are not authorized to perform this action.'));
		}
		$this->render('views',array(
			'model'=>$this->loadModel($id),
		));
	}

	/**
	 * Creates a new model.
	 * If creation is successful, the browser will be redirected to the 'view' page.
	 */
	public function actionCreate($id)
	{
		$model=new JournalRecord;
		$model->load_id=$id;
		$access=false;
		if (isset(Yii::app()->user->identityType)) {
			if (isset(Yii::app()->user->identityId)) {
				if (Yii::app()->user->identityType == User::TYPE_SUPER) {
					$access=true;
					$teacher=Teacher::model()->findByPk(Yii::app()->user->identityId);
					if ($model->teacher_id == Yii::app()->user->identityId) {
						$access = true;
					} elseif(in_array($model->group_id,$teacher->getGroupListArray())) {
						$access = true;
					}
				}
				if (Yii::app()->user->identityType == User::TYPE_TEACHER) {
					/**
					 * @var $teacher Teacher
					 */
					$teacher=Teacher::model()->findByPk(Yii::app()->user->identityId);
					if ($model->load->teacher_id == Yii::app()->user->identityId) {
						$access = true;
					} elseif(in_array($model->load->group_id,$teacher->getGroupListArray())) {
						$access = true;
					}
				}
				if (Yii::app()->user->identityType == User::TYPE_INSPECTOR) {
					$access=true;
					$teacher=Teacher::model()->findByPk(Yii::app()->user->identityId);
					if ($model->teacher_id == Yii::app()->user->identityId) {
						$access = true;
					} elseif(in_array($model->group_id,$teacher->getGroupListArray())) {
						$access = true;
					}
				}
				if (Yii::app()->user->identityType == User::TYPE_NAVCH) {
					$access=true;
					$teacher=Teacher::model()->findByPk(Yii::app()->user->identityId);
					if ($model->load->teacher_id == Yii::app()->user->identityId) {
						$access = true;
					} elseif(in_array($model->load->group_id,$teacher->getGroupListArray())) {
						$access = true;
					}
				}
				if (Yii::app()->user->identityType == User::TYPE_ZASTUPNIK) {
					$access=true;
					$teacher=Teacher::model()->findByPk(Yii::app()->user->identityId);
					if ($model->load->teacher_id == Yii::app()->user->identityId) {
						$access = true;
					} elseif(in_array($model->load->group_id,$teacher->getGroupListArray())) {
						$access = true;
					}
				}
				if (Yii::app()->user->identityType == User::TYPE_DIRECTOR) {
					$access=true;
					$teacher=Teacher::model()->findByPk(Yii::app()->user->identityId);
					if ($model->load->teacher_id == Yii::app()->user->identityId) {
						$access = true;
					} elseif(in_array($model->load->group_id,$teacher->getGroupListArray())) {
						$access = true;
					}
				}
			}
		}
		if(!$access)
		{
			throw new CHttpException(403, Yii::t('yii','You are not authorized to perform this action.'));
		}
		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);
		$model->date=date('Y-m-d');
		$model->teacher_id=Yii::app()->user->identityId;
		$this->ajaxValidation('journal-record-form', $model);
		if(isset($_POST['JournalRecord']))
		{
			$model->attributes=$_POST['JournalRecord'];
			if($model->save())
				$this->redirect(array('views','id'=>$model->id));
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
		$model=$this->loadModel($id);
		$access=false;
		if (isset(Yii::app()->user->identityType)) {
			if (isset(Yii::app()->user->identityId)) {
				if (Yii::app()->user->identityType == User::TYPE_SUPER) {
					$access=true;
					$teacher=Teacher::model()->findByPk(Yii::app()->user->identityId);
					if ($model->teacher_id == Yii::app()->user->identityId) {
						$access = true;
					}
				}
				if (Yii::app()->user->identityType == User::TYPE_TEACHER) {
					/**
					 * @var $teacher Teacher
					 */
					$teacher=Teacher::model()->findByPk(Yii::app()->user->identityId);
					if ($model->load->teacher_id == Yii::app()->user->identityId) {
						$access = true;
					}
				}
				if (Yii::app()->user->identityType == User::TYPE_INSPECTOR) {
					$access=true;
					$teacher=Teacher::model()->findByPk(Yii::app()->user->identityId);
					if ($model->teacher_id == Yii::app()->user->identityId) {
						$access = true;
					}
				}
				if (Yii::app()->user->identityType == User::TYPE_NAVCH) {
					$access=true;
					$teacher=Teacher::model()->findByPk(Yii::app()->user->identityId);
					if ($model->load->teacher_id == Yii::app()->user->identityId) {
						$access = true;
					}
				}
				if (Yii::app()->user->identityType == User::TYPE_ZASTUPNIK) {
					$access=true;
					$teacher=Teacher::model()->findByPk(Yii::app()->user->identityId);
					if ($model->load->teacher_id == Yii::app()->user->identityId) {
						$access = true;
					}
				}
				if (Yii::app()->user->identityType == User::TYPE_DIRECTOR) {
					$access=true;
					$teacher=Teacher::model()->findByPk(Yii::app()->user->identityId);
					if ($model->load->teacher_id == Yii::app()->user->identityId) {
						$access = true;
					}
				}
			}
		}
		if(!$access)
		{
			throw new CHttpException(403, Yii::t('yii','You are not authorized to perform this action.'));
		}
		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);
		$this->ajaxValidation('journal-record-form', $model);
		if(isset($_POST['JournalRecord']))
		{
			$model->attributes=$_POST['JournalRecord'];
			if($model->save())
				$this->redirect(array('views','id'=>$model->id));
		}

		$this->render('update',array(
			'model'=>$model,
		));
	}

	/**
	 * Deletes a particular model.
	 * If deletion is successful, the browser will be redirected to the 'admin' page.
	 * @param integer $id the ID of the model to be deleted
	 */
	public function actionDelete($id)
	{
		/**
		 * @var $model JournalRecord
		 */
		$model=JournalRecord::model()->findByPk($id);
		$model->delete();
		$access=false;
		if (isset(Yii::app()->user->identityType)) {
			if (isset(Yii::app()->user->identityId)) {
				if (Yii::app()->user->identityType == User::TYPE_SUPER) {
					$access=true;
					$teacher=Teacher::model()->findByPk(Yii::app()->user->identityId);
					if ($model->teacher_id == Yii::app()->user->identityId) {
						$access = true;
					}
				}
				if (Yii::app()->user->identityType == User::TYPE_TEACHER) {
					/**
					 * @var $teacher Teacher
					 */
					$teacher=Teacher::model()->findByPk(Yii::app()->user->identityId);
					if ($model->load->teacher_id == Yii::app()->user->identityId) {
						$access = true;
					}
				}
				if (Yii::app()->user->identityType == User::TYPE_INSPECTOR) {
					$access=true;
					$teacher=Teacher::model()->findByPk(Yii::app()->user->identityId);
					if ($model->teacher_id == Yii::app()->user->identityId) {
						$access = true;
					}
				}
				if (Yii::app()->user->identityType == User::TYPE_NAVCH) {
					$access=true;
					$teacher=Teacher::model()->findByPk(Yii::app()->user->identityId);
					if ($model->load->teacher_id == Yii::app()->user->identityId) {
						$access = true;
					}
				}
				if (Yii::app()->user->identityType == User::TYPE_ZASTUPNIK) {
					$access=true;
					$teacher=Teacher::model()->findByPk(Yii::app()->user->identityId);
					if ($model->load->teacher_id == Yii::app()->user->identityId) {
						$access = true;
					}
				}
				if (Yii::app()->user->identityType == User::TYPE_DIRECTOR) {
					$access=true;
					$teacher=Teacher::model()->findByPk(Yii::app()->user->identityId);
					if ($model->load->teacher_id == Yii::app()->user->identityId) {
						$access = true;
					}
				}
			}
		}
		if(!$access)
		{
			throw new CHttpException(403, Yii::t('yii','You are not authorized to perform this action.'));
		}
		// if AJAX request (triggered by deletion via admin grid view), we should not redirect the browser
		///if(!isset($_GET['ajax']))
	//		$this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('admin'));
	}

	
	/**
	 * Returns the data model based on the primary key given in the GET variable.
	 * If the data model is not found, an HTTP exception will be raised.
	 * @param integer $id the ID of the model to be loaded
	 * @return JournalRecord the loaded model
	 * @throws CHttpException
	 */
	public function loadModel($id)
	{
		$model=JournalRecord::model()->findByPk($id);
		if($model===null)
			throw new CHttpException(404,'The requested page does not exist.');
		return $model;
	}

	/**
	 * Performs the AJAX validation.
	 * @param JournalRecord $model the model to be validated
	 */
	protected function performAjaxValidation($model)
	{
		if(isset($_POST['ajax']) && $_POST['ajax']==='journal-record-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}
}
