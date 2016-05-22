<?php
/**
 * Created by PhpStorm.
 * User: Arhangel
 * Date: 21.05.2016
 * Time: 17:30
 */

class PresentExcelController extends Controller
{
    public function actionIndex()
    {
        $model = new presentExcel();

        if(isset($_POST['PresentViewer']['reportType'])){
            $this->actionViews($_POST['PresentViewer']['reportType']);
            return;
        }
        $reportType = $_POST['PresentViewer']['reportType'];
        $this->render('index', array(
            'model' => $model,
            'reportType' => $reportType,
        ));
    }

    public function actionViews($reportType)
    {
        $t=false;
        $access=true;
        /**
         * @var $load Load
         * @var $teacher_id journalRecord
         */
        $load=null;
        $load=Load::model()->findByAttributes(array('study_year_id' => $study_year_id,'group_id' => $group_id));
        $teacher_id=Load::model()->findByPk($load->teacher_id);
        /**
         * @var $student Student
         */
        $student=null;
        if (isset(Yii::app()->user->identityType)) {
            if (isset(Yii::app()->user->identityId)) {
                if (Yii::app()->user->identityType == User::TYPE_TEACHER) {
                    /**
                     * @var $teacher Teacher
                     */
                    $teacher=Teacher::model()->findByPk(Yii::app()->user->identityId);
                    if ($teacher_id == Yii::app()->user->identityId) {
                        $access = true;
                        $t = true;
                    } elseif(in_array($group_id,$teacher->getGroupListArray())) {
                        $access = true;
                        $t = false;
                    }
                }
                else if (Yii::app()->user->identityType == User::TYPE_STUDENT) {
                    $student=Student::model()->findByPk(Yii::app()->user->identityId);
                    if(in_array($group_id,$student->getGroupListArray())&&in_array($student->id,JournalStudents::getAllStudentsInArray($load))){
                        $access=true;
                        $t=false;
                    } else {
                        /** @var $group Group*/
                        $group=Group::model()->findByPk($load->group_id);
                        if($student->id==$group->monitor_id) $access=true;
                    }
                }
                else if (Yii::app()->user->identityType == User::TYPE_SUPER) {
                    $access=true;
                }
                else if (Yii::app()->user->identityType == User::TYPE_INSPECTOR) {
                    $access=true;
                    $teacher=Teacher::model()->findByPk(Yii::app()->user->identityId);
                    if ($teacher_id == Yii::app()->user->identityId) {
                        $access = true;
                        $t = true;
                    } elseif(in_array($group_id,$teacher->getGroupListArray())) {
                        $access = true;
                        $t = false;
                    }
                }
                else if (Yii::app()->user->identityType == User::TYPE_NAVCH) {
                    $access=true;
                    $teacher=Teacher::model()->findByPk(Yii::app()->user->identityId);
                    if ($teacher_id == Yii::app()->user->identityId) {
                        $access = true;
                        $t = true;
                    } elseif(in_array($group_id,$teacher->getGroupListArray())) {
                        $access = true;
                        $t = false;
                    }
                }
                else if (Yii::app()->user->identityType == User::TYPE_ZASTUPNIK) {
                    $access=true;
                    $teacher=Teacher::model()->findByPk(Yii::app()->user->identityId);
                    if ($teacher_id == Yii::app()->user->identityId) {
                        $access = true;
                        $t = true;
                    } elseif(in_array($group_id,$teacher->getGroupListArray())) {
                        $access = true;
                        $t = false;
                    }
                }
                else if (Yii::app()->user->identityType == User::TYPE_DIRECTOR) {
                    $access=true;
                    $teacher=Teacher::model()->findByPk(Yii::app()->user->identityId);
                    if ($teacher_id == Yii::app()->user->identityId) {
                        $access = true;
                        $t = true;
                    } elseif(in_array($group_id,$teacher->getGroupListArray())) {
                        $access = true;
                        $t = false;
                    }
                }
            }
        }
        if(!$access)
        {
            throw new CHttpException(403, Yii::t('yii','You are not authorized to perform this action.'));
        }

        $this->render('view', array(
            't'=>$t,
            'student_id_view'=>$student,
            'id' => $load->id,
        ));
    }
}