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
        if (isset($_POST['ReportHours']['group_id']) and $_POST['ReportHours']['group_id']!="" and
            isset($_POST['ReportHours']['month']) and $_POST['ReportHours']['month']!="" and
            isset($_POST['ReportHours']['years']) and $_POST['ReportHours']['years']!="") {
            $excel = Yii::app()->getComponent('excel');
            $data = JournalRecord::model()->findAll();
            $years=StudyYear::model()->findByPk(array('id'=>$_POST['ReportHours']['years']));
            $datarez =array();
            foreach ($data as $item){
                if ($item->load->group_id==$_POST['ReportHours']['group_id']&&substr($item->date,5,2)==$_POST['ReportHours']['month']) {
                    if ((substr($item->date, 0, 4) == $years->begin and intval(substr($item->date, 5, 2)) >= 9) or
                        (substr($item->date, 0, 4) == $years->end and intval(substr($item->date, 5, 2)) < 9)) {
                        array_push($datarez, $item);
                    }
                }
            }
            if (Yii::app()->user->checkAccess('student')) {
                throw new CHttpException(403, Yii::t('yii', 'You are not authorized to perform this action.'));
            }
            if (!empty($datarez))
                $excel->getDocument($datarez, 'GroupHoursList');
            else
                echo "<script>alert(Yii:t('report','There are no data to generate a report'););</script>";
        }
        $this->render('index',
            array('model' => $model,)
        );
    }
}
?>