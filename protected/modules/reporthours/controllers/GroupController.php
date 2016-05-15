<?php
Yii::import('modules.load.models.*');
?>
<?
class GroupController extends Controller{

    public function actionIndex()
    {
        /**
         * @var ReportHours $model
         * @var ExcelMaker $excel
         * @var $data JournalRecord[]
         * @var $datarez string[]
         */
        $model = new ReportHours();
        if (isset($_POST['ReportHours']['group_id'])&&isset($_POST['ReportHours']['month'])) {
            $excel = Yii::app()->getComponent('excel');
            $data = JournalRecord::model()->findAll();
            $datarez =array();
            foreach ($data as $item){
                if ($item->load->group_id==$_POST['ReportHours']['group_id']&&substr($item->date,5,2)==$_POST['ReportHours']['month'])
                    array_push($datarez,$item);
            }
            //var_dump(Subject::model()->findByPk(array('id'=>$datarez[0]->load->wp_subject_id)));
            $excel->getDocument($datarez, 'GroupHoursList');

            return;
        }

        $this->render('index',
            array('model' => $model,)
        );
    }
}
?>