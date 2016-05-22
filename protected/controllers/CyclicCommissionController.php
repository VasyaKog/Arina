<?php

class CyclicCommissionController extends Controller
{
    public $name = 'Cyclic commissions';

    /**
     * Lists all models.
     */
    public function actionIndex()
    {
        if(Yii::app()->user->checkAccess('student')&&Yii::app()->user->checkAccess('prefect'))
        {
            throw new CHttpException(403, Yii::t('yii','You are not authorized to perform this action.'));
        }
        $model = new CyclicCommission('search');
        $model->unsetAttributes();
        if (isset($_GET['CyclicCommission']))

            foreach ($_GET['CyclicCommission'] as $key => $value)
                $model->$key = $value;
        $this->render('index', array(
            'dataProvider' => $model->search(),
            'model' => $model,
        ));
    }

    /**
     * Displays a particular model.
     * @param integer $id the ID of the model to be displayed
     */
    public function actionView($id)
    {
        if(Yii::app()->user->checkAccess('student')&&Yii::app()->user->checkAccess('prefect'))
        {
            throw new CHttpException(403, Yii::t('yii','You are not authorized to perform this action.'));
        }
        $this->render('view', array(
            'model' => CyclicCommission::model()->loadContent($id),
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
        $model = new CyclicCommission;

        $this->ajaxValidation('cyclic-commission-form', $model);

        if (isset($_POST['CyclicCommission'])) {
            $model->attributes = $_POST['CyclicCommission'];
            if ($model->save())
                $this->redirect(array('index'));
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
        $model = CyclicCommission::model()->loadContent($id);

       if (!Yii::app()->user->checkAccess('admin'))
        {
            throw new CHttpException(403, Yii::t('yii', 'You are not authorized to perform this action.'));
        }
        $this->ajaxValidation('cyclic-commission-form', $model);

        if (isset($_POST['CyclicCommission'])) {
            $model->attributes = $_POST['CyclicCommission'];
            if ($model->save())
                $this->redirect(array('index'));
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
        if (!Yii::app()->user->checkAccess('admin'))
        {
            throw new CHttpException(403, Yii::t('yii', 'You are not authorized to perform this action.'));
        }
        if (Yii::app()->request->isPostRequest) {
            CyclicCommission::model()->loadContent($id)->delete();

            if (!isset($_GET['ajax']))
                $this->redirect(array('index'));
        } else
            throw new CHttpException(400, Yii::t('base', 'Invalid request. Please do not repeat this request again.'));
    }
}
