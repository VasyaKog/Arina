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
         * @var GroupReport $model_group
         * @var TeacherReport $model_teacher
         * @var ExcelMaker $excel
         * @var $data JournalRecord[]
         * @var $item JournalRecord
         * @var $temp Load
         */
        $model_group = new GroupReport();
        $model_teacher = new TeacherReport();
        if (isset($_POST['GroupReport']['group_id'])) {
            $excel = Yii::app()->getComponent('excel');
            $data = JournalRecord::model()->findAll();
            $datarez= array();
            foreach ($data as $item){
                $temp = $item->load;
                var_dump($temp);
            //    if ($item->load->group_id==$_POST['GroupReport']['group_id']){
            //        array_push($datarez,$item);
            //    }
            }
            var_dump($_POST['GroupReport']['group_id']);
            //data = JournalRecord::model()->findAllByAttributes(array('' => $_POST['GroupReport']['group_id']));
            //var_dump($data);
            //$excel->getDocument($data, 'GroupHoursList');
        }
        $this->render('index', 
            array('model_group' => $model_group,
                'model_teacher'=>$model_teacher)
        );
    }
}
?>