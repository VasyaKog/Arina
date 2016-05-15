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
        if ((isset($_POST['ReportHours']['teacher_id'])) and ($_POST['ReportHours']['teacher_id']!="") and
            (isset($_POST['ReportHours']['years'])) and ($_POST['ReportHours']['years'])!=""){
            $excel = Yii::app()->getComponent('excel');
            $data = JournalRecord::model()->findAll();
            $datarez = array();
            $years=StudyYear::model()->findByPk(array('id'=>$_POST['ReportHours']['years']));
            foreach ($data as $item) {
                if ($item->teacher_id == $_POST['ReportHours']['teacher_id']) {
                    if ((substr($item->date, 0, 4) == $years->begin and intval(substr($item->date, 5, 2)) >= 9) or
                        (substr($item->date, 0, 4) == $years->end and intval(substr($item->date, 5, 2)) < 9)) {
                        array_push($datarez, $item);
                    }
                }
            }
            if (Yii::app()->user->checkAccess('student')) {
                throw new CHttpException(403, Yii::t('yii', 'You are not authorized to perform this action.'));
            }
            $excel->getDocument($datarez, 'TeacherHoursList');
            return;
        }

        $this->render('index',array('model' => $model));
    }
}
?>