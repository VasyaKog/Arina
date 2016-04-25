<?php

class DefaultController extends Controller
{
/**
     * Displays a particular model.
     * @param integer $id the ID of the model to be displayed
     */
    public function actionView($id)
    {
        $this->render('view', array(
            'model' => Student::model()->loadContent($id),
        ));
    }

    /**
     * Creates a new model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     */
    public function actionCreate()
    {
        if(!Yii::app()->user->checkAccess('dephead')&&!Yii::app()->user->checkAccess('inspector')&&!Yii::app()->user->checkAccess('admin'))
        {
            throw new CHttpException(403, Yii::t('yii','You are not authorized to perform this action.'));
        }
        $model = new Student;

        $this->ajaxValidation('student-form', $model);

        if (isset($_POST['Student'])) {
            $model->attributes = $_POST['Student'];
            var_dump($model->attributes);
            if(!Yii::app()->user->checkAccess('manageGroup',
                array(
                    'id' => $model->group->speciality->department->head_id,
                    'type' => User::TYPE_TEACHER,
                ))
                &&
                !Yii::app()->user->checkAccess('admin')
                &&
                !Yii::app()->user->checkAccess('inspector',array('type' => User::TYPE_INSPECTOR))
            )
            {
                throw new CHttpException(403, Yii::t('yii','You are not authorized to perform this action.'));
            }
            if ($model->save()) {
                $this->redirect(array('view', 'id' => $model->id));
            }
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
        /**
         * @var $model Student
         */
        $model = Student::model()->loadContent($id);
        if (        
            !Yii::app()->user->checkAccess('manageGroup',
                array(
                    'id' => $model->group->speciality->department->head_id,
                    'type' => User::TYPE_TEACHER,
                )
            )&&
                !Yii::app()->user->checkAccess('admin')
                &&
                !Yii::app()->user->checkAccess('inspector')
                &&
                !Yii::app()->user->checkAccess('dephead')
        )
        {
            throw new CHttpException(403, Yii::t('yii', 'You are not authorized to perform this action.'));
        }
        $this->ajaxValidation('student-form', $model);

        if (isset($_POST['Student'])) {
            if(!isset($_POST['Student']['exemptions'])) {
                $_POST['Student']['exemptions'] = array();
            }
            $model->attributes = $_POST['Student'];
            if ($model->save())
                $this->redirect(array('view', 'id' => $model->id));
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
        $model = Student::model()->loadContent($id);
        if(!Yii::app()->user->checkAccess('manageGroup',
            array(
                'id' => $model->group->speciality->department->head_id,
                'type' => User::TYPE_TEACHER,
            )
        )&&!Yii::app()->user->checkAccess('admin')
            &&!Yii::app()->user->checkAccess('inspector'))

        {
            throw new CHttpException(403, Yii::t('yii','You are not authorized to perform this action.'));
        }
        $model->delete();
        // if AJAX request (triggered by deletion via admin grid view), we should not redirect the browser
        /*if (!isset($_GET['ajax']))
            $this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('admin'));*/
            $this->redirect(array('index'));
    }

    /**
     * Lists all models.
     */
    public function actionIndex()
    {

        $model = new Student('search');
        $model->unsetAttributes(); // clear any default values
        if (isset($_GET['Student'])) {
            $model->attributes = $_GET['Student'];
        }

        $this->render('index', array(
            'model' => $model,
        ));
    }

    public function actionGroup($id)
    {
        $group = Group::model()->findByPk($id);
        $groupName = $group->title;
        $provider = Student::model()->getProvider(array('criteria' => array('condition' => "group_id=$id")));
        $this->render(
            'group',
            array(
                'provider' => $provider,
                'groupName' => $groupName,
                'group' => $group,
                'id'=>$id,
            )
        );
    }
}