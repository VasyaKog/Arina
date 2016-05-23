<?php

/**
 *
 * @author Dmytro Karpovych <ZAYEC77@gmail.com>
 */
class TeacherController extends Controller
{
    public $name = 'Teachers';

    /**
     *
     */
    public function actionIndex()
    {
        if(Yii::app()->user->checkAccess('student')&&Yii::app()->user->checkAccess('prefect'))
        {
            throw new CHttpException(403, Yii::t('yii','You are not authorized to perform this action.'));
        }

        $model = new Teacher('search');
        $model->unsetAttributes(); // clear any default values
        if (isset($_GET['Teacher'])) {
            foreach ($_GET['Teacher'] as $key => $item) {
                $model->$key = $item;
            }
        }

        $sort = new CSort();
        $sort->attributes = array(
            'fullName' => array(
                'asc' => 'last_name, first_name, middle_name ASC',
                'desc' => 'last_name, first_name, middle_name DESC',
            ),
            '*',
        );

        $provider = Teacher::model()->getProvider(
            array(
                'pagination' => array('pageSize' => 20,),
                'sort' => $sort,
                'criteria' => $model->search(),
            )
        );

        $this->render(
            'index',
            array(
                'provider' => $provider,
                'model' => $model,
            )
        );
    }

    /**
     * @param $id
     */
    public function actionView($id)
    {
       if(Yii::app()->user->checkAccess('student')&&Yii::app()->user->checkAccess('prefect'))
        {
            throw new CHttpException(403, Yii::t('yii','You are not authorized to perform this action.'));
        }
         if (!Yii::app()->user->checkAccess('admin')
        &&!Yii::app()->user->checkAccess('director')&&!Yii::app()->user->checkAccess('zastupnik')&&!Yii::app()->user->checkAccess('dephead')&&!Yii::app()->user->checkAccess('inspector')) 
         {
            throw new CHttpException(403, Yii::t('yii', 'You are not authorized to perform this action.'));
        }
           
        $model = Teacher::model()->loadContent($id);

        $this->render(
            'view',
            array(
                'model' => $model,
            )
        );
    }

    /**
     *
     */
    public function actionCreate()
    {
        if (!Yii::app()->user->checkAccess('inspector')&&!Yii::app()->user->checkAccess('admin')
        ) {
            throw new CHttpException(403, Yii::t('yii', 'You are not authorized to perform this action.'));
        }
        $model = new Teacher();

        $this->ajaxValidation('teacher-form', $model);

        if (isset($_POST['Teacher'])) {
            $model->attributes = $_POST['Teacher'];
            $model->short_name = $model->getNameWithInitials();

           /* if (!Yii::app()->user->checkAccess('manageTeacher',
                array(
                    'id' => $model->cyclicCommission->head_id,
                    'type' => User::TYPE_TEACHER,
                )
            )
            ) {
                throw new CHttpException(403, Yii::t('yii', 'You are not authorized to perform this action.'));
            }*/

            if ($model->save()) {
                $this->redirect(array('group/index'));
            }

            if ($model->save()) {
                $this->redirect(array('index'));
            }
        }

        $this->render('create', array('model' => $model));
    }

    /**
     * @param $id
     */
    public function actionDelete($id)
    {
        if (!Yii::app()->user->checkAccess('admin')) {
            throw new CHttpException(403, Yii::t('yii', 'You are not authorized to perform this action.'));
        }
        $model = Teacher::model()->loadContent($id);
        $model->delete();
        if (!Yii::app()->getRequest()->isAjaxRequest) {
            $this->redirect(array('index'));
        }
    }

    /**
     * @param $id
     */
    public function actionUpdate($id)
    {
        $model = Teacher::model()->loadContent($id);
        if (!Yii::app()->user->checkAccess('manageTeacher',
            array(
                'id' => $model->cyclicCommission->head_id,
                'type' => User::TYPE_TEACHER,
            )
        )&&!Yii::app()->user->checkAccess('admin')&&!Yii::app()->user->checkAccess('inspector'))
        {
            throw new CHttpException(403, Yii::t('yii', 'You are not authorized to perform this action.'));
        }
        $this->redirect(array('hr/default/update', 'id' => $id));
        /*//return;
        //return
        $this->ajaxValidation('teacher-form', $model);

        if (isset($_POST['Teacher'])) {
            $model->attributes = $_POST['Teacher'];
            if ($model->save()) {
                $this->redirect(array('index'));
            }
        }

        $this->render(
            'update',
            array(
                'model' => $model,
            )
        );*/
    }

    /**
     * @param $id
     */
    public function actionListByCycle($id)
    {   
        
       if(Yii::app()->user->checkAccess('student')&&Yii::app()->user->checkAccess('prefect'))
        {
            throw new CHttpException(403, Yii::t('yii','You are not authorized to perform this action.'));
        }

        $condition = "cyclic_commission_id = :cyclic_commission_id";
        $params = array(':cyclic_commission_id' => $id);
        $teachers = Teacher::model()->findAll($condition, $params);
        echo CHtml::dropDownList('', '', CHtml::listData($teachers, 'id', 'fullName'), array('empty'=>''));
    }
}