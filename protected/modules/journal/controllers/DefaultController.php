<?php

class DefaultController extends Controller
{
    public function actionIndex()
    {
        $model = new JournalViewer();
      //  $model->setScenario('group');

      /*  if (Yii::app()->getRequest()->isAjaxRequest) {
            if (isset($_POST["JournalViewer"])) {
                $model->attributes = $_POST["JournalViewer"];
                if ($model->validate()) {
                    $model->isEmpty = false;
                }
                $this->renderPartial('_form', array(
                    'model' => $model,
                ));
            }

      */
        //
//            if (Yii::app()->user->checkAccess('student')) {
//                $this->studentView();
//                return;
//            } else if (Yii::app()->user->checkAccess('teacher')) {
//                $this->teacherView();
//                return;
//            } else if (Yii::app()->user->checkAccess('curator') || Yii::app()->user->checkAccess('prefect')) {
//                $this->curatorAndPrefectView();
//                return;
//            } else if (Yii::app()->user->checkAccess('admin')) {
//                $this->adminView();
//                return;
//            } else if (Yii::app()->user->checkAccess('dephead')) {
//                $this->depheadView();
//                return;
//            }

           // Yii::app()->end();
       // }
        if(isset($_POST['JournalViewer']['studyYearId'])&&isset($_POST['JournalViewer']['groupId'])&&isset($_POST['JournalViewer']['subjectId'])){
            /**
             * @var $loadmas Load[]
             * @var $load Load
             **/
            $load=null;
            $load=Load::model()->findByAttributes(array('study_year_id'=>$_POST['JournalViewer']['studyYearId'],'group_id'=>$_POST['JournalViewer']['groupId'],'wp_subject_id'=>$_POST['JournalViewer']['subjectId']));
            if(!is_null($load)){

               // $this->actionViews($load->id);
                $this->redirect('/journal/default/views/'.$load->id);
            }
            return;
        }

        $this->render('index', array(
            'model' => $model,
        ));




    }

    public function actionChangeGroupList()
    {
        $selectedyear = $_POST['JournalViewer']['studyYearId'];
        var_dump($selectedyear);
        if ($selectedyear == 0) {
            echo CHtml::tag('option', array('value' => 0), Yii::t('journal', 'Select Study Year'), true);
            return;
        }
        $data = Group::getGroupsByYearId($selectedyear);
        echo CHtml::tag('option', array('value' => 0), Yii::t('journal', 'Select group'), true);
        foreach ($data as $item) {
            echo CHtml::tag('option', array('value' => $item->id), $item->title, true);
        }
    }


    public function actionChangeSubjectList(){

        $selectedyear=$_POST['JournalViewer']['studyYearId'];
        $selectedgroup=$_POST['JournalViewer']['groupId'];

      /**
         * @var $dat Load[]
        **/
        $dat=array();
        $dat=Load::model()->findAll();
        /**
         * @var $ws WorkSubject[]
         **/

        if ($_POST['JournalViewer']['groupId'] == 0) {
            echo CHtml::tag('option', array('value' => 0), Yii::t('journal', 'Select group'), true);
            return;
        };
        echo CHtml::tag('option', array('value' => 0), Yii::t('journal', 'Select subject'), true);
       foreach ($dat as $item){
           if($item->group_id==$selectedgroup&&$item->study_year_id==$selectedyear){
                echo CHtml::tag('option', array('value' => $item->wp_subject_id), WorkSubject::getNameSubject($item->wp_subject_id), true);
                }
            }
        }

    public function actionViews($id)
    {
        $t=false;
        $access=true;
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
/*
    protected function adminView()
    {
        
    }

    protected function depHeadView()
    {
        // select all classes from groups from current department
        $dataProvider = ActualClass::model()->getProvider(
            array(
                'criteria' => array(
                    'with' => array('')
                )
            )
        );

        $this->render('dephead_view',array('dataProvider'=>$dataProvider));
    }

    protected function teacherView()
    {
        // select all classes with current teacher
        $dataProvider = ActualClass::model()->getProvider(
            array(
                'criteria' => array(
                    'with' => array('teacher', 'marks', 'absences', 'group', 'subject'),
                    'condition' => 'teacher_id=:teacherId',
                    'params' => array('teacherId' => Yii::app()->user->identityId),
                ),
                'pagination' => array(
                    'pageSize' => 20,
                ),
            )
        );
        $data = $dataProvider->getData();
        $this->render('teacher_view',array('dataProvider'=>$dataProvider));
    }

    protected function curatorAndPrefectView()
    {

    }

    protected function studentView()
    {

    }

*/
}