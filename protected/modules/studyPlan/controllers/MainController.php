<?php
/**
 * @author Serhiy Vinichuk <serhiyvinichuk@gmail.com>
 * @author makskarv <makskarv@mail.ru>
 */

class MainController extends Controller
{
    public $name = 'Навчальні плани';

    public function actionIndex()
    {
        $this->render('index');
    }

    public function actionResetGraph()
    {
        unset(Yii::app()->session['weeks']);
        unset(Yii::app()->session['graph']);
    }
} 