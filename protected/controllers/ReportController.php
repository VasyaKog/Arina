<?php
/**
 * Created by PhpStorm.
 * User: retard
 * Date: 5/10/2016
 * Time: 2:07 PM
 */
Yii::import('modules.load.models.*');
?>
<?
class ReportController extends Controller{

    public function actionIndex()
    {
        /**
         * @var ReportHours $model
         * @var ExcelMaker $excel
         * @var $data JournalRecord[]
         * @var $item JournalRecord
         * @var $temp string
         * @var $datarez JournalRecord[]
         */
        $model = new ReportHours();
        if (isset($_POST['ReportHours']['group_id'])&&isset($_POST['ReportHours']['month'])) {
            $excel = Yii::app()->getComponent('excel');
            $data = JournalRecord::model()->findAll();
            $datarez =array();
            foreach ($data as $item){
                if (substr($item->date,5,2)==$_POST['ReportHours']['month'])
                    array_push($datarez,$item);

            //    if ($item->load->group_id==$_POST['GroupReport']['group_id']){
            //        array_push($datarez,$item);
            //    }
                //var_dump($item->date);
            }
            var_dump($datarez);
            //var_dump($_POST['ReportHours']['group_id']);
            //var_dump($_POST['ReportHours']['month']);

            //data = JournalRecord::model()->findAllByAttributes(array('' => $_POST['GroupReport']['group_id']));
            //var_dump($data);
            //$excel->getDocument($data, 'GroupHoursList');
        }
        $this->render('index', 
            array('model' => $model,)
        );
    }
}
?>