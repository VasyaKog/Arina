<?php

/**
 * GroupController
 *
 * @author Dmytro Karpovych <ZAYEC77@gmail.com>
 */
class GroupController extends Controller
{
    public $name = "Groups";

    /**
     *
     */
    public function actionIndex()
    {
        if(Yii::app()->user->checkAccess('student')&&Yii::app()->user->checkAccess('prefect'))
        {
            throw new CHttpException(403, Yii::t('yii','You are not authorized to perform this action.'));
        }
        $config = array(
            'criteria' => array(
                'with' => array(
                    'speciality',
                    'curator',
                ),
            ),
            'sort' => array(
                'defaultOrder' => 't.title ASC',
                'attributes' => array(
                    'speciality.title' => 'speciality.title',
                    'curator.name' => array(
                        'asc' => 'curator.last_name, curator.middle_name, curator.first_name',
                        'desc' => 'curator.last_name DESC, curator.middle_name DESC, curator.first_name DESC',
                    ),
                    '*'
                )
            ),
            'pagination' => array('pageSize' => 20,)
        );
        $speciality_id = 0;
        if (!empty($_GET['Speciality'])) {
            $config['criteria']['condition'] = 't.speciality_id = :spec_id';
            $config['criteria']['params']['spec_id'] = $_GET['Speciality'];
            $speciality_id = $_GET['Speciality'];
        }
        $provider = Group::model()->getProvider($config);
        $this->render('index', array('provider' => $provider, 'speciality_id' => $speciality_id));
    }

    public function actionMakeExcel($id)
    {
        if(Yii::app()->user->checkAccess('student')&&Yii::app()->user->checkAccess('prefect'))
        {
            throw new CHttpException(403, Yii::t('yii','You are not authorized to perform this action.'));
        }
        
        $excel = Yii::app()->getComponent('excel');
        $group = Group::model()->loadContent($id);
        $excel->getDocument($group, 'simpleGroupList');
    }

    public function actionDoc($id)
    {
        if(Yii::app()->user->checkAccess('student')&&Yii::app()->user->checkAccess('prefect'))
        {
            throw new CHttpException(403, Yii::t('yii','You are not authorized to perform this action.'));
        }
        $model = new GroupDocForm();
        $model->group = Group::model()->loadContent($id);
        if (isset($_POST['GroupDocForm'])) {
            $model->setAttributes($_POST['GroupDocForm'], false);
            if ($model->validate()) {
                $model->getDoc();
                Yii::app()->end();
            }
        }
        $this->render('doc', array('model' => $model));
    }

    /**
     *
     */
    public function actionCreate()
    {
         if(!Yii::app()->user->checkAccess('admin'))
        {
            throw new CHttpException(403, Yii::t('yii','You are not authorized to perform this action.'));
        }
        $model = new Group();

        $this->ajaxValidation('group-form', $model);

        if (isset($_POST['Group'])) {
            $model->attributes = $_POST['Group'];
            if ($model->save()) {
                $this->redirect(array('group/index'));
            }
        }

        $this->render('create', array('model' => $model));
    }

    /**
     * @param $id
     * @throws CHttpException
     */
    public function actionUpdate($id)
    {
        if (!Yii::app()->user->checkAccess('admin'))
        {
            throw new CHttpException(403, Yii::t('yii', 'You are not authorized to perform this action.'));
        }
        $model = Group::model()->loadContent($id);
        /*if (
            !Yii::app()->user->checkAccess('manageGroup',
                array(
                    'id' => $model->getCuratorId(),
                    'type' => User::TYPE_TEACHER,
                ))
            
            &&
            !Yii::app()->user->checkAccess('manageGroup',
                array(
                    'id' => $model->speciality->department->head_id,
                    'type' => User::TYPE_TEACHER,
                )
        {
            throw new CHttpException(403, Yii::t('yii', 'You are not authorized to perform this action.'));
        }
*/
        $this->ajaxValidation('group-form', $model);

        if (isset($_POST['Group'])) {
            $model->attributes = $_POST['Group'];
            if ($model->save()) {
                $this->redirect(array('group/index'));
            }
        }

        $this->render('update', array('model' => $model,));
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
        $model = Group::model()->loadContent($id);
        $this->render('view', array('model' => $model,));
    }

    /**
     * @param $id
     * @throws CHttpException
     */
    public function actionDelete($id)
    {
        $model = Group::model()->loadContent($id);
        if (!Yii::app()->user->checkAccess('admin'))
        {
            throw new CHttpException(403, Yii::t('yii', 'You are not authorized to perform this action.'));
        }
        $model->delete();
        if (!Yii::app()->getRequest()->isAjaxRequest) {
            $this->redirect(array('index'));
        }
    }

    public function actionSimpleList($id)
    {
        /**@var $excel ExcelMaker
         *@var $group Group
         */
        if(Yii::app()->user->checkAccess('student'))
        {
            throw new CHttpException(403, Yii::t('yii','You are not authorized to perform this action.'));
        }
        /**@var $excel ExcelMaker */
        $excel = Yii::app()->getComponent('excel');
        $group= Group::model()->findByPk($id);
        $excel->getDocument($group->getStudentArray(), 'groupList');
    }
}