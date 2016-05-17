<?php
Yii::import('modules.load.models.*');
?>
<?
class SubjectController extends Controller{

    public function actionIndex()
    {
        /**
         * @var ReportHours $model
         * @var ExcelMaker $excel
         * @var $data JournalRecord[]
         * @var $group Group
         * @var $plan WorkPlan
         * @var $work_subject WorkSubject
         * @var $years StudyYear
         */
        $model = new ReportHours();
        if (isset($_POST['ReportHours']['years']) and $_POST['ReportHours']['years']!="" and
            isset($_POST['ReportHours']['teacher_id']) and $_POST['ReportHours']['teacher_id']!="" and
            isset($_POST['ReportHours']['subject_id']) and $_POST['ReportHours']['subject_id']!="" and
            isset($_POST['ReportHours']['group_id']) and $_POST['ReportHours']['group_id']!="") {
            $excel = Yii::app()->getComponent('excel');
            $data = JournalRecord::model()->findAll(array('order'=>'date ASC'));
            $group = Group::model()->findByPk(array('id'=>$_POST['ReportHours']['group_id']));
            $years=StudyYear::model()->findByPk(array('id'=>$_POST['ReportHours']['years']));
            $plan = WorkPlan::model()->findByAttributes(array('speciality_id'=>$group->speciality_id,'year_id'=>$years->id));
            $work_subject = WorkSubject::model()->findByAttributes(array('subject_id'=>$_POST['ReportHours']['subject_id'],
                'plan_id'=>$plan->id));

            $datarez =array();
            foreach ($data as $item) {
                if ($item->teacher_id == $_POST['ReportHours']['teacher_id'] and
                    $item->load->wp_subject_id == $work_subject->id and
                    $item->load->group_id == $_POST['ReportHours']['group_id'] and
                    $item->load->study_year_id == $_POST['ReportHours']['years'])
                    array_push($datarez, $item);
            }
            if (Yii::app()->user->checkAccess('student')) {
                throw new CHttpException(403, Yii::t('yii', 'You are not authorized to perform this action.'));
            }
            if (!empty($datarez))
                $excel->getDocument($datarez, 'SubjectHoursList');
            else
                echo "<script>alert(Yii:t('report','There are no data to generate a report'););</script>";
        }
        $this->render('index',
            array('model' => $model,)
        );
    }
}
?>