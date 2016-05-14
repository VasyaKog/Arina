<?php

class DefaultController extends Controller
{
    public function actionIndex()
    {
        $model = new PresentViewer();

        if(isset($_POST['PresentViewer']['studyYearId'])&&isset($_POST['PresentViewer']['groupId'])&&isset($_POST['PresentViewer']['subjectId'])){
            /**
             * @var $loadmas Load[]
             * @var $load Load
             **/
            $load=null;
            $load=Load::model()->findByAttributes(array('study_year_id'=>$_POST['PresentViewer']['studyYearId'],'group_id'=>$_POST['PresentViewer']['groupId'],'wp_subject_id'=>$_POST['PresentViewer']['subjectId']));
            if(!is_null($load)){

               // $this->actionViews($load->id);
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
        $selectedyear = $_POST['PresentViewer']['studyYearId'];
        var_dump($selectedyear);
        if ($selectedyear == 0) {
            echo CHtml::tag('option', array('value' => 0), Yii::t('present', 'Select Study Year'), true);
            return;
        }
        $data = Group::getGroupsByYearId($selectedyear);
        echo CHtml::tag('option', array('value' => 0), Yii::t('present', 'Select group'), true);
        foreach ($data as $item) {
            echo CHtml::tag('option', array('value' => $item->id), $item->title, true);
        }
    }


    public function actionChangeSubjectList(){

        $selectedyear=$_POST['PresentViewer']['studyYearId'];
        $selectedgroup=$_POST['PresentViewer']['groupId'];

      /**
         * @var $dat Load[]
        **/
        $dat=array();
        $dat=Load::model()->findAll();
        /**
         * @var $ws WorkSubject[]
         **/

        if ($_POST['PresentViewer']['groupId'] == 0) {
            echo CHtml::tag('option', array('value' => 0), Yii::t('present', 'Select group'), true);
            return;
        };
        echo CHtml::tag('option', array('value' => 0), Yii::t('present', 'Select subject'), true);
       foreach ($dat as $item){
           if($item->group_id==$selectedgroup&&$item->study_year_id==$selectedyear){
                echo CHtml::tag('option', array('value' => $item->wp_subject_id), WorkSubject::getNameSubject($item->wp_subject_id), true);
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