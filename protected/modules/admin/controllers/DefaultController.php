<?php
Yii::import('admin.components.*');

class DefaultController extends Controller
{
    public $defaultAction = 'run';

    public function actionIndex()
    {
        
        $this->render('index');
    }

    public function actionRun($args = '')
    {
        $console = new WebConsole();
        $console->init();
        $result = $console->run(explode(' ', trim($args)));
        $confirm = isset(Yii::app()->session['show-confirm']);
        $this->render('run', array('result' => $result, 'confirm' => $confirm, 'args' => $args));
    }

    public function actionConfirm($args)
    {
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