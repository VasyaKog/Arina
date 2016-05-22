<?php
Yii::import('studyPlan.models.*');

/**
 * @author Serhiy Vinichuk <serhiyvinichuk@gmail.com>
 */
class MainController extends Controller
{
    public $name = 'Навантаження';

    public function actionIndex()
    {
        $dataProvider = StudyYear::model()->getProvider();
        $this->render('index', array('dataProvider' => $dataProvider));
    }

    public function actionCreate()
    {
        if (!Yii::app()->user->checkAccess('dephead')&&!Yii::app()->user->checkAccess('admin')&&!Yii::app()->user->checkAccess('director')&&!Yii::app()->user->checkAccess('zastupnik'))
        {
            throw new CHttpException(403, Yii::t('yii', 'You are not authorized to perform this action.'));
        }
        if (isset($_POST['study_year'])) {
            $this->generateLoadFor($_POST['study_year']);
            $this->redirect($this->createUrl('index'));
        }

        $this->render('create');
    }

    public function actionDelete($id)
    {

        if (!Yii::app()->user->checkAccess('dephead')&&!Yii::app()->user->checkAccess('admin')&&!Yii::app()->user->checkAccess('director')&&!Yii::app()->user->checkAccess('zastupnik'))
        {
            throw new CHttpException(403, Yii::t('yii', 'You are not authorized to perform this action.'));
        }
        $model = Load::model()->loadContent($id);
        $year = $model->study_year_id;
        $model->delete();
        $this->redirect($this->createUrl('view', array('id' => $year)));
    }

    /**
     * @param integer $studyYear
     */
    protected function generateLoadFor($studyYear)
    {
         if (!Yii::app()->user->checkAccess('dephead')&&!Yii::app()->user->checkAccess('admin'))
        {
            throw new CHttpException(403, Yii::t('yii', 'You are not authorized to perform this action.'));
        }
        /** @var StudyYear $year */
        $year = StudyYear::model()->loadContent($studyYear);

        $command = Yii::app()->db->createCommand();
        $command->delete('load', 'study_year_id=' . $studyYear);

        foreach ($year->workPlans as $plan) {
            $groups = $plan->speciality->getGroupsByStudyYear($year->id);
            foreach ($plan->subjects as $subject) {
                foreach ($groups as $title => $course) {
                    $spring = $course * 2;
                    $fall = $spring - 1;
                    $group = Group::model()->find("title='$title'");
                    if (!empty($subject->total[$fall-1]) || !empty($subject->total[$spring-1])) {
                        $this->getNewLoad($year, $subject, $group, $course, Load::TYPE_LECTURES);

                        if ($subject->dual_practice && (!empty($subject->practs[$fall-1]) || !empty($subject->practs[$spring-1]))) {
                            $this->getNewLoad($year, $subject, $group, $course, Load::TYPE_PRACTS);
                        }

                        if ($subject->dual_labs && (!empty($subject->labs[$fall-1]) || !empty($subject->labs[$spring-1]))) {
                            $this->getNewLoad($year, $subject, $group, $course, Load::TYPE_LABS);
                        }

                    }
                }
            }
        }
    }

    /**
     * @param StudyYear $studyYear
     * @param WorkSubject $subject
     * @param Group $group
     * @param int $course
     * @param int $type
     */
    protected function getNewLoad($studyYear, $subject, $group, $course, $type)
    {
         if (!Yii::app()->user->checkAccess('dephead')&&!Yii::app()->user->checkAccess('admin'))
        {
            throw new CHttpException(403, Yii::t('yii', 'You are not authorized to perform this action.'));
        }
        $model = new Load();
        $model->study_year_id = $studyYear->id;
        $model->wp_subject_id = $subject->id;
        $model->group_id = $group->id;
        $model->type = $type;
        $model->course = $course;
        $consult = array();
        $consult[0] = $model->calcConsultation($course * 2 - 1);
        $consult[1] = $model->calcConsultation($course * 2);
        $model->consult = $consult;
        $students = array();
        $students[0] = $group->getStudentsCount();
        $students[1] = $group->getBudgetStudentsCount();
        $students[2] = $group->getContractStudentsCount();
        $model->students = $students;
        $model->save();
    }

    public function actionUpdate($id)
    {

       if (!Yii::app()->user->checkAccess('dephead')&&!Yii::app()->user->checkAccess('admin')&&!Yii::app()->user->checkAccess('director')&&!Yii::app()->user->checkAccess('zastupnik'))
        {
            throw new CHttpException(403, Yii::t('yii', 'You are not authorized to perform this action.'));
        }
        /** @var Load $model */
        $model = Load::model()->loadContent($id);

        if (isset($_POST['Load'])) {
            $model->setAttributes($_POST['Load'], false);
            if ($model->save()) {
                $this->redirect($this->createUrl('view', array('id' => $model->study_year_id)));
            }
        }

        $this->render('update', array('model' => $model));
    }

    public function actionView($id)
    {
        $model = new Load();

        if (isset($_GET['Load'])) {
            $model->setAttributes($_GET['Load'], false);
            $model->commissionId = $_GET['Load']['commissionId'];
        }

        $year = StudyYear::model()->loadContent($id);
        $this->render('view', array('model' => $model, 'dataProvider' => $model->search(), 'year' => $year));
    }

    public function actionGenerate($id)
    {
        if (!Yii::app()->user->checkAccess('dephead')&&!Yii::app()->user->checkAccess('admin')&&!Yii::app()->user->checkAccess('director')&&!Yii::app()->user->checkAccess('zastupnik'))
        {
            throw new CHttpException(403, Yii::t('yii', 'You are not authorized to perform this action.'));
        }
        $this->generateLoadFor($id);
        $this->redirect($this->createUrl('view', array('id' => $id)));
    }

    public function actionProject($id)
    {
        if (!Yii::app()->user->checkAccess('dephead')&&!Yii::app()->user->checkAccess('admin')&&!Yii::app()->user->checkAccess('director')&&!Yii::app()->user->checkAccess('zastupnik'))
        {
            throw new CHttpException(403, Yii::t('yii', 'You are not authorized to perform this action.'));
        }
        $model = new Load('project');
        $model->study_year_id = $id;
        $model->type = Load::TYPE_PROJECT;

        if (isset($_POST['Load'])) {
            $model->setAttributes($_POST['Load'], false);
            $model->course = $model->group->getCourse($model->study_year_id);
            if ($model->save()) {
                $this->redirect($this->createUrl('view', array('id' => $id)));
            }
        }

        $this->render('project', array('model' => $model));
    }

    public function actionEdit($id)
    {
         if (!Yii::app()->user->checkAccess('dephead')&&!Yii::app()->user->checkAccess('admin')&&!Yii::app()->user->checkAccess('director')&&!Yii::app()->user->checkAccess('zastupnik'))
        {
            throw new CHttpException(403, Yii::t('yii', 'You are not authorized to perform this action.'));
        }
        /** @var Load $model */
        $model = Load::model()->loadContent($id);
        $model->setScenario('project');
        $model->commissionId = $model->teacher->cyclic_commission_id;

        if (isset($_POST['Load'])) {
            $model->setAttributes($_POST['Load'], false);
            $model->course = $model->group->getCourse($model->study_year_id);
            if ($model->save()) {
                $this->redirect($this->createUrl('view', array('id' => $model->study_year_id)));
            }
        }

        $this->render('project', array('model' => $model));
    }

    public function actionDoc($id)
    {
        /** @var StudyYear $year */
        $year = StudyYear::model()->loadContent($id);
        $model = new LoadDocGenerateModel();
        if (isset($_POST['LoadDocGenerateModel'])) {
            $model->setAttributes($_POST['LoadDocGenerateModel'], false);
            $model->yearId = $id;
            if ($model->validate()) {
                $model->generate();
            }
        }
        $this->render('doc', array(
            'model' => $model,
            'year'=>$year,
        ));
    }
} 