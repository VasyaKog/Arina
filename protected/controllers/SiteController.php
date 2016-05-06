<?php

class SiteController extends Controller
{
    public $name = 'Home';

    /**
     * Declares class-based actions.
     */
    public function actions()
    {
        return array(
            // captcha action renders the CAPTCHA image displayed on the contact page
            'captcha' => array(
                'class' => 'CCaptchaAction',
                'backColor' => 0xFFFFFF,
            ),
            // page action renders "static" pages stored under 'protected/views/site/pages'
            // They can be accessed via: index.php?r=site/page&view=FileName
            'page' => array(
                'class' => 'CViewAction',
            ),
        );
    }

    public function accessRules()
    {
        return array(
            array('allow',
                'users' => array('*'),
            ),
        );
    }

    /**
     * This is the default 'index' action that is invoked
     * when an action is not explicitly requested by users.
     */
    public function actionIndex()
    {
        $this->render('index');
    }

    /**
     * This is the action to handle external exceptions.
     */
    public function actionError()
    {
        if ($error = Yii::app()->errorHandler->error) {
            if (Yii::app()->request->isAjaxRequest)
                echo $error['message'];
            else
                $this->render('error', $error);
        }
    }

    protected static function generateRandomString($length = 10)
    {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, strlen($characters) - 1)];
        }
        return $randomString;
    }

    /*
     * Операція "Що попові можна, то дякові - зась"
     */

    public function actionSetup()
    {
        /** @var CAuthManager $auth */
        $auth = Yii::app()->authManager;

        /*
         * -----------------------------+---------------------------+---------------------------+
         * Groups                       | Audiences                 | Teachers                  |
         *  C: Dep head                 |  C: Admin                 |  C: Dep head              |
         *  R: All                      |  R: All                   |  R: All                   |
         *  U: Dep head, Curator        |  U: Admin                 |  U: Dep head              |
         *  D: Dep head                 |  D: Admin                 |  D: Dep head              |
         * -----------------------------+---------------------------+---------------------------|
         * Cyclic commissions           | Study plans               | Loads                     |
         *  C: Dep head                 |  C: Dep head              |  C: Dep head, Cycle head  |
         *  R: All                      |  R: Dep head, Cycle head  |  R: All                   |
         *  U: Dep head                 |  U: Dep head              |  U: Dep head, Cycle head  |
         *  D: Dep head                 |  D: Dep head              |  D: Dep head, Cycle head  |
         * -----------------------------+---------------------------+---------------------------|
         * Students                     | Specialities              | Departments               |
         *  C: Dep head                 |  C: Dep head              |  C: Admin                 |
         *  R: All                      |  R: All                   |  R: All                   |
         *  U: Dep head, Curator        |  U: Dep head              |  U: Admin                 |
         *  D: Dep head                 |  D: Dep head              |  D: Admin                 |
         * -----------------------------+---------------------------+---------------------------|
         * Subjects                     | Subject cycles            | Exemptions                |
         *  C: Dep head                 |  C: Dep head              |  C: Admin                 |
         *  R: All                      |  R: All                   |  R: All                   |
         *  U: Dep head                 |  U: Dep head              |  U: Admin                 |
         *  D: Dep head                 |  D: Dep head              |  D: Admin                 |
         * -------------------------------------------------------------------------------------|
         * Marks/Absences/Actual classes                                                        |
         *  C: Teacher, Prefect                                                                 |
         *  R: Student (own), Teacher (own), Curator (own group), Dep head (own department)     |
         *  U: Teacher, Prefect                                                                 |
         *  D: Teacher                                                                          |
         * -------------------------------------------------------------------------------------+
         */

        /*
         * Operations
         */

        /* CRUD Operations */

        // Groups
        $auth->createOperation('createGroup','create a group');
        $auth->createOperation('readGroup','read a group');
        $auth->createOperation('updateGroup','update a group');
        $auth->createOperation('deleteGroup','delete a group');

        // Audiences
        $auth->createOperation('createAudience','create an audience');
        $auth->createOperation('readAudience','read an audience');
        $auth->createOperation('updateAudience','update an audience');
        $auth->createOperation('deleteAudience','delete an audience');

        // Teachers
        $auth->createOperation('createTeacher','create a teacher');
        $auth->createOperation('readTeacher','read a teacher');
        $auth->createOperation('updateTeacher','update a teacher');
        $auth->createOperation('deleteTeacher','delete a teacher');

        // Cyclic commissions
        $auth->createOperation('createCyclicCommission','create a cyclic commission');
        $auth->createOperation('readCyclicCommission','read an cyclic commission');
        $auth->createOperation('updateCyclicCommission','update a cyclic commission');
        $auth->createOperation('deleteCyclicCommission','delete a cyclic commission');

        // Study plans
        $auth->createOperation('createStudyPlan','create a study plan');
        $auth->createOperation('readStudyPlan','read a study plan');
        $auth->createOperation('updateStudyPlan','update a study plan');
        $auth->createOperation('deleteStudyPlan','delete a study plan');

        // Loads
        $auth->createOperation('createLoad','create a load');
        $auth->createOperation('readLoad','read a load');
        $auth->createOperation('updateLoad','update a load');
        $auth->createOperation('deleteLoad','delete a load');

        // Students
        $auth->createOperation('createStudent','create a group');
        $auth->createOperation('readStudent','read a group');
        $auth->createOperation('updateStudent','update a group');
        $auth->createOperation('deleteStudent','delete a group');

        // Specialities
        $auth->createOperation('createSpeciality','create a speciality');
        $auth->createOperation('readSpeciality','read a speciality');
        $auth->createOperation('updateSpeciality','update a speciality');
        $auth->createOperation('deleteSpeciality','delete a speciality');

        // Departments
        $auth->createOperation('createDepartment','create a department');
        $auth->createOperation('readDepartment','read a department');
        $auth->createOperation('updateDepartment','update a department');
        $auth->createOperation('deleteDepartment','delete a department');

        // Subjects
        $auth->createOperation('createSubject','create a subject');
        $auth->createOperation('readSubject','read a subject');
        $auth->createOperation('updateSubject','update a subject');
        $auth->createOperation('deleteSubject','delete a subject');

        // Subject cycles
        $auth->createOperation('createSubjectCycle','create a subject cycle');
        $auth->createOperation('readSubjectCycle','read a subject cycle');
        $auth->createOperation('updateSubjectCycle','update a subject cycle');
        $auth->createOperation('deleteSubjectCycle','delete a subject cycle');

        // Exemptions
        $auth->createOperation('createExemption','create an exemption');
        $auth->createOperation('readExemption','read an exemption');
        $auth->createOperation('updateExemption','update an exemption');
        $auth->createOperation('deleteExemption','delete an exemption');

        // Marks / Absences / Classes
        $auth->createOperation('createMAC','create a MAC item');
        $auth->createOperation('readMAC','read a MAC item');
        $auth->createOperation('updateMAC','update a MAC item');
        $auth->createOperation('deleteMAC','delete a MAC item');

        /* To do: Misc operations */

        //Study Years
        $auth->createOperation('createStudyYear','create a study year');
        $auth->createOperation('readStudyYear','read a study year');
        $auth->createOperation('updateStudyYear','update a study year');
        $auth->createOperation('deleteStudyYear','delete a study year');

        //Positions
        $auth->createOperation('createPosition','create a position');
        $auth->createOperation('readPosition','read a position');
        $auth->createOperation('updatePosition','update a position');
        $auth->createOperation('deletePosition','delete a position');

        /*
         * Tasks
         */


        $task=$auth->createTask('manageGroup','description');
            $task->addChild('createGroup');
            $task->addChild('readGroup');
            $task->addChild('updateGroup');
            $task->addChild('deleteGroup');
        $task=$auth->createTask('manageAudience','description');
            $task->addChild('createAudience');
            $task->addChild('readAudience');
            $task->addChild('updateAudience');
            $task->addChild('deleteAudience');
        $task=$auth->createTask('manageTeacher','description');
            $task->addChild('createTeacher');
            $task->addChild('readTeacher');
            $task->addChild('updateTeacher');
            $task->addChild('deleteTeacher');
        $task=$auth->createTask('manageCyclicCommission','description');
            $task->addChild('createCyclicCommission');
            $task->addChild('readCyclicCommission');
            $task->addChild('updateCyclicCommission');
            $task->addChild('deleteCyclicCommission');
        $task=$auth->createTask('manageStudyPlan','description');
            $task->addChild('createStudyPlan');
            $task->addChild('readStudyPlan');
            $task->addChild('updateStudyPlan');
            $task->addChild('deleteStudyPlan');
        $task=$auth->createTask('manageLoad','description');
            $task->addChild('createLoad');
            $task->addChild('readLoad');
            $task->addChild('updateLoad');
            $task->addChild('deleteLoad');
        $task=$auth->createTask('manageStudent','description');
            $task->addChild('createStudent');
            $task->addChild('readStudent');
            $task->addChild('updateStudent');
            $task->addChild('deleteStudent');
        $task=$auth->createTask('manageSpeciality','description');
            $task->addChild('createSpeciality');
            $task->addChild('readSpeciality');
            $task->addChild('updateSpeciality');
            $task->addChild('deleteSpeciality');
        $task=$auth->createTask('manageDepartment','description');
            $task->addChild('createDepartment');
            $task->addChild('readDepartment');
            $task->addChild('updateDepartment');
            $task->addChild('deleteDepartment');
        $task=$auth->createTask('manageSubject','description');
            $task->addChild('createSubject');
            $task->addChild('readSubject');
            $task->addChild('updateSubject');
            $task->addChild('deleteSubject');
        $task=$auth->createTask('manageSubjectCycle','description');
            $task->addChild('createSubjectCycle');
            $task->addChild('readSubjectCycle');
            $task->addChild('updateSubjectCycle');
            $task->addChild('deleteSubjectCycle');
        $task=$auth->createTask('manageExemption','description');
            $task->addChild('createExemption');
            $task->addChild('readExemption');
            $task->addChild('updateExemption');
            $task->addChild('deleteExemption');
        $task=$auth->createTask('manageMAC','description');
            $task->addChild('createMAC');
            $task->addChild('readMAC');
            $task->addChild('updateMAC');
            $task->addChild('deleteMAC');
        $task=$auth->createTask('manageStudyYear','description');
            $task->addChild('createStudyYear');
            $task->addChild('readStudyYear');
            $task->addChild('updateStudyYear');
            $task->addChild('deleteStudyYear');
        $task=$auth->createTask('managePosition','description');
            $task->addChild('createPosition');
            $task->addChild('readPosition');
            $task->addChild('updatePosition');
            $task->addChild('deletePosition');

        $task=$auth->createTask('manageOwnGroup','description' , 'return (Yii::app()->user->identityId==$params["id"] && Yii::app()->user->identityType==$params["type"]);');
            $task->addChild('manageGroup');
        $task=$auth->createTask('manageOwnMAC','description' , 'return (Yii::app()->user->identityId==$params["id"] && Yii::app()->user->identityType==$params["type"]);');
            $task->addChild('manageMAC');
        $task=$auth->createTask('manageOwnLoad','description' , 'return (Yii::app()->user->identityId==$params["id"] && Yii::app()->user->identityType==$params["type"]);');
            $task->addChild('manageLoad');
        $task=$auth->createTask('manageOwnStudyPlan','description' , 'return (Yii::app()->user->identityId==$params["id"] && Yii::app()->user->identityType==$params["type"]);');
            $task->addChild('manageStudyPlan');
        $task=$auth->createTask('manageOwnCycleSubject','description' , 'return (Yii::app()->user->identityId==$params["id"] && Yii::app()->user->identityType==$params["type"]);');
            $task->addChild('manageSubject');
        $task=$auth->createTask('manageOwnStudent','description' , 'return (Yii::app()->user->identityId==$params["id"] && Yii::app()->user->identityType==$params["type"]);');
            $task->addChild('manageStudent');
        $task=$auth->createTask('manageOwnSpeciality','description' , 'return (Yii::app()->user->identityId==$params["id"] && Yii::app()->user->identityType==$params["type"]);');
            $task->addChild('manageSpeciality');
        $task=$auth->createTask('manageOwnDepartment','description' , 'return (Yii::app()->user->identityId==$params["id"] && Yii::app()->user->identityType==$params["type"]);');
            $task->addChild('manageDepartment');
        $task=$auth->createTask('manageOwnCyclicCommission','description' , 'return (Yii::app()->user->identityId==$params["id"] && Yii::app()->user->identityType==$params["type"]);');
            $task->addChild('manageCyclicCommission');
        $task=$auth->createTask('manageOwnTeacher','description' , 'return (Yii::app()->user->identityId==$params["id"] && Yii::app()->user->identityType==$params["type"]);');
            $task->addChild('manageTeacher');

        /*
         * Roles
         */

        $role=$auth->createRole('student');
        $role->addChild('manageOwnMAC');

        $role=$auth->createRole('prefect');
        $role->addChild('student');
        $role->addChild('manageOwnStudent');


        $role=$auth->createRole('teacher');
        $role->addChild('manageOwnMAC');
        $role->addChild('manageOwnLoad');
      


        $role=$auth->createRole('curator');
        $role->addChild('teacher');
        $role->addChild('manageOwnGroup');
        $role->addChild('manageOwnStudent');

        $role=$auth->createRole('cychead');
        $role->addChild('curator');
        $role->addChild('manageOwnCycleSubject');
        $role->addChild('manageOwnCyclicCommission');
        $role->addChild('manageOwnTeacher');

        $role=$auth->createRole('dephead');
        $role->addChild('cychead');
        $role->addChild('manageOwnStudyPlan');
        $role->addChild('manageOwnSpeciality');
        $role->addChild('manageOwnDepartment');
        $role->addChild('manageStudent');


        $role=$auth->createRole('admin');
        $role->addChild('dephead');
        $role->addChild('manageAudience');
        $role->addChild('manageSpeciality');
        $role->addChild('manageDepartment');
        $role->addChild('manageGroup');
        $role->addChild('managePosition');
        $role->addChild('manageStudyYear');
        $role->addChild('manageStudent');
        $role->addChild('manageSubject');
        $role->addChild('manageSubjectCycle');
        $role->addChild('manageCyclicCommission');
        $role->addChild('manageTeacher');

        $role=$auth->createRole('inspector');
        $role->addChild('manageStudent');
        $role->addChild('manageTeacher');



        /*$auth->assign('admin',1);
        $auth->assign('dephead',91);
        $auth->assign('curator',93);
        $auth->assign('teacher',92);
        $auth->assign('dephead',92);
        $auth->assign('student',94);
        $auth->assign('prefect',95);
        //$auth->assign('inspector',860);
        //$auth->assign('trainingof',861);*/

        echo "OK";
        return;
    }

    /**
    +     * @throws CException
    +     */
    public function actionSchedule()
    {
        $data = array(
            'year' => null,
            'semester' => null,
        );
        if (Yii::app()->request->isPostRequest) {
           $data = array(
                'year' => Yii::app()->request->getPost('year'),
                'semester' => Yii::app()->request->getPost('semester'),
            );
            /**@var $excel ExcelMaker */
            $excel = Yii::app()->getComponent('excel');
            $excel->getDocument(['id' => $data['year'], 'semester' => $data['semester']], 'schedule');
        }
        $this->render('schedule', ['data' => $data]);
    }
}