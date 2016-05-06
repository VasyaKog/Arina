<?php

class AudienceController extends Controller
{
    public $name = 'Audiences';

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
        $model = new Audience;

        $this->ajaxValidation('audience-form', $model);

        if (isset($_POST['Audience'])) {
            $model->attributes = $_POST['Audience'];
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
        if(!Yii::app()->user->checkAccess('admin'))
        {
            throw new CHttpException(403, Yii::t('yii','You are not authorized to perform this action.'));
        }
        $model = Audience::model()->loadContent($id);


        $this->ajaxValidation('audience-form', $model);

        if (isset($_POST['Audience'])) {
            $model->attributes = $_POST['Audience'];
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
        if(!Yii::app()->user->checkAccess('admin'))
        {
            throw new CHttpException(403, Yii::t('yii','You are not authorized to perform this action.'));
        }
        $model = Audience::model()->loadContent($id);
        $model->delete();
        if (!Yii::app()->getRequest()->isAjaxRequest) {
            $this->redirect(array('index'));
        }
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
        /*$dataProvider = new CActiveDataProvider('Audience', array( 'criteria'=>array(
            'order'=>'number ASC',
        )));
        $this->render('index', array(
            'dataProvider' => $dataProvider,
        ));*/
        $model = new Audience('search');
        $model->unsetAttributes(); // clear any default values
        if (isset($_GET['Audience']))
            $model->attributes = $_GET['Audience'];

        $this->render('index', array(
            'model' => $model,
        ));
    }

}
