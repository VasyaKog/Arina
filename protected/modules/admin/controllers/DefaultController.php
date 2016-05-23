<?php
Yii::import('admin.components.*');

class DefaultController extends Controller
{
    public $defaultAction = 'run';

    public function actionIndex()
    {
        if (!Yii::app()->user->checkAccess('admin'))
        {
            throw new CHttpException(403, Yii::t('yii', 'You are not authorized to perform this action.'));
        }
        $this->render('index');
    }

    public function actionRun($args = '')
    {
        if (!Yii::app()->user->checkAccess('admin'))
        {
            throw new CHttpException(403, Yii::t('yii', 'You are not authorized to perform this action.'));
        }
        $console = new WebConsole();
        $console->init();
        $result = $console->run(explode(' ', trim($args)));
        $confirm = isset(Yii::app()->session['show-confirm']);
        $this->render('run', array('result' => $result, 'confirm' => $confirm, 'args' => $args));
    }

    public function actionConfirm($args)
    {
        if (!Yii::app()->user->checkAccess('admin'))
        {
            throw new CHttpException(403, Yii::t('yii', 'You are not authorized to perform this action.'));
        }
        Yii::app()->session['console-confirm'] = true;
        $this->actionRun($args);
    }

    /**
     * Specifies the access control rules.
     * This method is used by the 'accessControl' filter.
     * @return array access control rules
     */
    public function accessRules()
    {
        
        return array(
            array('allow',
                'users' => array('*'),
                'actions'=>array('index','confirm','run'),
            ),
        );
    }
}