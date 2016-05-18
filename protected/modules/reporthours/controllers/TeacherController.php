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
         * @var $years StudyYear
         */
        $model = new ReportHours();
        if ((isset($_POST['ReportHours']['teacher_id'])) and (isset($_POST['ReportHours']['years']))){
            if (($_POST['ReportHours']['teacher_id']=="") or ($_POST['ReportHours']['years'])==""){
                echo "<script language='javascript'>alert('Fill all fields, sorry, page will reload');</script>";
                $this->render('index', array('model' => $model,));
                return;
            }
            $excel = Yii::app()->getComponent('excel');
            $data = JournalRecord::model()->findAll();
            $datarez = array();
            $years=StudyYear::model()->findByPk(array('id'=>$_POST['ReportHours']['years']));
            foreach ($data as $item) {
                if ($item->teacher_id == $_POST['ReportHours']['teacher_id']  and
                    $item->load->study_year_id==$years->id) {
                        array_push($datarez, $item);
                }
            }
            if (Yii::app()->user->checkAccess('student')) {
                throw new CHttpException(403, Yii::t('yii', 'You are not authorized to perform this action.'));
            }
            if (!empty($datarez))
                $excel->getDocument($datarez, 'TeacherHoursList');
            else
                echo "<script language='javascript'>alert('There are no data to generate a report');</script>";
        }

        $this->render('index',array('model' => $model));
    }
}
?>