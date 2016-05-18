<?php
/**
 * Created by PhpStorm.
 * User: Arhangel
 * Date: 16.05.2016
 * Time: 10:16
 */

class ExcelController extends Controller
{
    public function actionExcelList()
    {
        if(!Yii::app()->user->checkAccess('inspector')&&!Yii::app()->user->checkAccess('admin')&&!Yii::app()->user->checkAccess('director')&&!Yii::app()->user->checkAccess('zastupnik')&&!Yii::app()->user->checkAccess('dephead'))
        {
            throw new CHttpException(403, Yii::t('yii','You are not authorized to perform this action.'));
        }
        /**@var $excel ExcelMaker */
        $excel = Yii::app()->getComponent('excel');
        $excel->getDocument(NULL, 'employeesList');
    }

    public function actionIndex()
    {
        $model = new PresentViewer();

        if(isset($_POST['PresentViewer']['studyYearId'])&&isset($_POST['PresentViewer']['groupId'])&&isset($_POST['PresentViewer']['studyMonthId'])){
            /**
             * @var $loadmas Load[]
             * @var $load Load
             **/
            $load=null;
            $load=Load::model()->findByAttributes(array('study_year_id'=>$_POST['PresentViewer']['studyYearId'],'group_id'=>$_POST['PresentViewer']['groupId'],'wp_subject_id'=>$_POST['PresentViewer']['studyMonthId']));
            if(!is_null($load)){
                $this->redirect('/present/default/views/'.$load->id);
            }
            return;
        }

        $this->render('index', array(
            'model' => $model,
        ));
    }

    public function actionChangeGroupList()
    {
        $selectedYear = $_POST['PresentViewer']['studyYearId'];
        var_dump($selectedYear);
        if ($selectedYear == 0) {
            echo CHtml::tag('option', array('value' => 0), Yii::t('present', 'Select Study Year'), true);
            return;
        }
        $data = Group::getGroupsByYearId($selectedYear);
        echo CHtml::tag('option', array('value' => 0), Yii::t('present', 'Select group'), true);
        foreach ($data as $item) {
            echo CHtml::tag('option', array('value' => $item->id), $item->title, true);
        }
    }


    public function actionChangeMonthList(){

        $selectedYear=$_POST['PresentViewer']['studyYearId'];
        $selectedGroup=$_POST['PresentViewer']['groupId'];

        /**
         * @var $dat Load[]
         **/
        $dat=array();
        $dat=PresentViewer::getMonthList();

        if ($_POST['PresentViewer']['groupId'] == 0) {
            echo CHtml::tag('option', array('value' => 0), Yii::t('present', 'Select group'), true);
            return;
        };
        echo CHtml::tag('option', array('value' => 0), Yii::t('present', 'Select Study Month'), true);
        foreach ($dat as $item){
            if($item->group_id==$selectedGroup&&$item->study_year_id==$selectedYear){
                echo CHtml::tag('option', array('value' => $item->id), PresentViewer::getMonthList($item->id), true);
            }
        }
    }

    public function actionViews($id)
    {
        $t=false;
        $access=false;
        /**
         * @var $load Load
         */
        $load=Load::model()->findByPk($id);
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
                    if ($load->teacher_id == Yii::app()->user->identityId) {
                        $access = true;
                        $t = true;
                    } elseif(in_array($load->group_id,$teacher->getGroupListArray())) {
                        $access = true;
                        $t = false;
                    }
                }
                else if (Yii::app()->user->identityType == User::TYPE_STUDENT) {
                    $student=Student::model()->findByPk(Yii::app()->user->identityId);
                    if(in_array($load->group_id,$student->getGroupListArray())&&in_array($student->id,JournalStudents::getAllStudentsInArray($load))){
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
                    if ($load->teacher_id == Yii::app()->user->identityId) {
                        $access = true;
                        $t = true;
                    } elseif(in_array($load->group_id,$teacher->getGroupListArray())) {
                        $access = true;
                        $t = false;
                    }
                }
                else if (Yii::app()->user->identityType == User::TYPE_NAVCH) {
                    $access=true;
                    $teacher=Teacher::model()->findByPk(Yii::app()->user->identityId);
                    if ($load->teacher_id == Yii::app()->user->identityId) {
                        $access = true;
                        $t = true;
                    } elseif(in_array($load->group_id,$teacher->getGroupListArray())) {
                        $access = true;
                        $t = false;
                    }
                }
                else if (Yii::app()->user->identityType == User::TYPE_ZASTUPNIK) {
                    $access=true;
                    $teacher=Teacher::model()->findByPk(Yii::app()->user->identityId);
                    if ($load->teacher_id == Yii::app()->user->identityId) {
                        $access = true;
                        $t = true;
                    } elseif(in_array($load->group_id,$teacher->getGroupListArray())) {
                        $access = true;
                        $t = false;
                    }
                }
                else if (Yii::app()->user->identityType == User::TYPE_DIRECTOR) {
                    $access=true;
                    $teacher=Teacher::model()->findByPk(Yii::app()->user->identityId);
                    if ($load->teacher_id == Yii::app()->user->identityId) {
                        $access = true;
                        $t = true;
                    } elseif(in_array($load->group_id,$teacher->getGroupListArray())) {
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
            'id' => $id,
        ));
    }
}