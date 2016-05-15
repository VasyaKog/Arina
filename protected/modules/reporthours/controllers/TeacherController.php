<?php
Yii::import('modules.load.models.*');
?>
<?
class TeacherController extends Controller{
    public function actionIndex()
    {
        /**
         * @var ReportHours $model
         * @var ExcelMaker $excel
         * @var $data JournalRecord[]
         */
        $model = new ReportHours();
        if (isset($_POST['ReportHours']['teacher_id'])and $_POST['ReportHours']['teacher_id']!="") {
            $excel = Yii::app()->getComponent('excel');
            $data = JournalRecord::model()->findAllByAttributes(array('teacher_id'=>$_POST['ReportHours']['teacher_id']));
            //var_dump($data);
            $excel->getDocument($data, 'TeacherHoursList');
            return;
        }
        $this->render('index',array('model' => $model));
    }
}
?>