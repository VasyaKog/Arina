<?php
/**
 * Created by PhpStorm.
 * User: retard
 * Date: 5/10/2016
 * Time: 2:07 PM
 */
class ReportController extends Controller{
    public function actionIndex(){
        $this->render('index');
    }
    
    public function actionMakeGroupList(){
    /**
     *@var $excel ExcelMaker
     */
        $excel = Yii::app()->getComponent('excel');
        $data = JournalRecord::model()->findAll();
        $excel->getDocument($data, 'GroupHoursList');
    }
}
?>