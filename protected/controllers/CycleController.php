<?php

class CycleController extends Controller
{
    public $name = 'Subject cycles';

    /**
     * Displays a particular model.
     * @param integer $id the ID of the model to be displayed
     */
    public function actionView($id)
    {
        if(Yii::app()->user->checkAccess('student'))
        {
            throw new CHttpException(403, Yii::t('yii','You are not authorized to perform this action.'));
        }
        $this->render('view', array(
            'model' => SubjectCycle::model()->loadContent($id),
        ));
    }

    /**
     * Creates a new model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     */
    public function actionCreate()
    {
        if(!Yii::app()->user->checkAccess('admin'))
        {
            throw new CHttpException(403, Yii::t('yii','You are not authorized to perform this action.'));
        }
        $model = new SubjectCycle;

        $this->ajaxValidation('subject-cycle-form', $model);

        if (isset($_POST['SubjectCycle'])) {
            $model->attributes = $_POST['SubjectCycle'];
            if ($model->save())
                $this->redirect(array('index',));
        }

        $this->render('create', array(
            'model' => $model,
        ));
    }

    /**
     * Updates a particular model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id the ID of the model to be updated
     */
    public function actionUpdate($id)
    {
        if(!Yii::app()->user->checkAccess('admin'))
        {
            throw new CHttpException(403, Yii::t('yii','You are not authorized to perform this action.'));
        }
        $model = SubjectCycle::model()->loadContent($id);

        $this->ajaxValidation('subject-cycle-form', $model);

        if (isset($_POST['SubjectCycle'])) {
            $model->attributes = $_POST['SubjectCycle'];
            if ($model->save())
                $this->redirect(array('index'));
        }

        $this->render('update', array(
            'model' => $model,
        ));
    }

    /**
     * Deletes a particular model.
     * If deletion is successful, the browser will be redirected to the 'admin' page.
     * @param integer $id the ID of the model to be deleted
     */
    public function actionDelete($id)
    {
       if(!Yii::app()->user->checkAccess('admin'))
        {
            throw new CHttpException(403, Yii::t('yii','You are not authorized to perform this action.'));
        }
        SubjectCycle::model()->loadContent($id)->delete();

        if (!isset($_GET['ajax']))
            $this->redirect(array('index'));
    }

    /**
     * Lists all models.
     */
    public function actionIndex()
    {
        if(Yii::app()->user->checkAccess('student'))
        {
            throw new CHttpException(403, Yii::t('yii','You are not authorized to perform this action.'));
        }
        $dataProvider = new CActiveDataProvider('SubjectCycle');
        $this->render('index', array(
            'dataProvider' => $dataProvider,
        ));
    }
}
