<?php
/**
 * Created by PhpStorm.
 * User: retard
 * Date: 5/10/2016
 * Time: 2:07 PM
 */
class ReportController extends Controller{

    public function actionIndex()
    {
        /**
         * @var GroupReport $model
         * @var ExcelMaker $excel
         * @var $data JournalRecord[]
         * @var $item JournalRecord
         */
        $model = new GroupReport();
        if (isset($_POST['GroupReport']['group_title'])) {
            $excel = Yii::app()->getComponent('excel');
            $data = JournalRecord::model()->findAll();
            $datarez= array();
            foreach ($data as $item){
                $item->load->group_id;
            }
            $data = JournalRecord::model()->findAllByAttributes(array('' => $_POST['GroupReport']['group_id']));
            var_dump($data);
            //$excel->getDocument($data, 'GroupHoursList');
        }
        $this->render('index', array('model' => $model));
    }
}
?>