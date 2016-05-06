<?php

/**
 * @author Serhiy Vinichuk <serhiyvinichuk@gmail.com>
 */
class WorkController extends Controller
{
    public $name = 'Робочий план';

    //CRUD for Work Plan
    public function actionIndex()
    {
        $dataProvider = WorkPlan::model()->getProvider();
        $this->render('index', array('dataProvider' => $dataProvider));
    }

    public function actionCreate()
    {
        if (!Yii::app()->user->checkAccess('dephead')&&!Yii::app()->user->checkAccess('admin'))
        {
            throw new CHttpException(403, Yii::t('yii', 'You are not authorized to perform this action.'));
        }
        $model = new WorkPlan();

        if (isset($_POST['WorkPlan'])) {
            $model->attributes = $_POST['WorkPlan'];
            $model->created = date('Y-m-d', time());

            if ($model->save()) {
                $this->redirect($this->createUrl('graph', array('id' => $model->id)));
            }
        }

        $this->render('create', array('model' => $model));
    }

    public function actionGraph($id)
    {
        $model = WorkPlan::model()->loadContent($id);
        $model->setScenario('graph');

        if (isset($_POST['yt0'])) {
            if (isset(Yii::app()->session['weeks'])) {
                $model->semesters = Yii::app()->session['weeks'];
                unset(Yii::app()->session['weeks']);
            }
            if (isset(Yii::app()->session['graph'])) {
                $model->graph = Yii::app()->session['graph'];
                unset(Yii::app()->session['graph']);
            }
            if ($model->save()) {
                $this->redirect($this->createUrl('subjects', array('id' => $model->id)));
            }
        }
        $this->render('graph', array('model' => $model));
    }

    public function actionView($id)
    {
        $model = WorkPlan::model()->loadContent($id);
        $this->render('view', array('model' => $model));
    }

    public function actionUpdate($id)
    {
        
        $model = WorkPlan::model()->loadContent($id);
        if (!Yii::app()->user->checkAccess('dephead')&&!Yii::app()->user->checkAccess('admin'))
        {
            throw new CHttpException(403, Yii::t('yii', 'You are not authorized to perform this action.'));
        }

        if (isset($_POST['WorkPlan'])) {
            $model->attributes = $_POST['WorkPlan'];
            if (isset(Yii::app()->session['weeks'])) {
                $model->semesters = Yii::app()->session['weeks'];
                unset(Yii::app()->session['weeks']);
            }
            if (isset(Yii::app()->session['graph'])) {
                $model->graph = Yii::app()->session['graph'];
                unset(Yii::app()->session['graph']);
            }
            if ($model->save()) {
                $this->redirect(array('index'));
            }
        }

        $this->render('update', array('model' => $model));

    }

    public function actionDelete($id)
    {
        if (!Yii::app()->user->checkAccess('dephead')&&!Yii::app()->user->checkAccess('admin'))
        {
            throw new CHttpException(403, Yii::t('yii', 'You are not authorized to perform this action.'));
        }
        WorkPlan::model()->loadContent($id)->delete();

        if (!isset($_GET['ajax'])) {
            $this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('index'));
        }
    }

    //CRUD for Work Subject
    public function actionSubjects($id)
    {
        $model = WorkPlan::model()->loadContent($id);
        $this->render('subjects', array('model' => $model));
    }

    public function actionAddSubject($id)
    {
        if (!Yii::app()->user->checkAccess('dephead')&&!Yii::app()->user->checkAccess('admin'))
        {
            throw new CHttpException(403, Yii::t('yii', 'You are not authorized to perform this action.'));
        }
        $model = new WorkSubject();
        $model->plan_id = $id;

        if (isset($_POST['WorkSubject'])) {
            $model->attributes = $_POST['WorkSubject'];
            if ($model->save())
                $this->redirect($this->createUrl('subjects', array('id' => $id)));
        }

        $this->render('subject_form', array('model' => $model, 'plan' => WorkPlan::model()->findByPk($id)));
    }

    public function actionEditSubject($id)
    {
        if (!Yii::app()->user->checkAccess('dephead')&&!Yii::app()->user->checkAccess('admin'))
        {
            throw new CHttpException(403, Yii::t('yii', 'You are not authorized to perform this action.'));
        }

        /** @var WorkSubject $model */
        $model = WorkSubject::model()->loadContent($id);

        if (isset($_POST['WorkSubject'])) {
            $model->attributes = $_POST['WorkSubject'];
            if ($model->save()) {
                $this->redirect($this->createUrl('view', array('id' => $model->plan_id)));
            }
        }

        $this->render('subject_form', array('model' => $model, 'plan' => $model->plan));
    }

    public function actionDeleteSubject($id)
    {
        if (!Yii::app()->user->checkAccess('dephead')&&!Yii::app()->user->checkAccess('admin'))
        {
            throw new CHttpException(403, Yii::t('yii', 'You are not authorized to perform this action.'));
        }
        WorkSubject::model()->loadContent($id)->delete();

        if (!isset($_GET['ajax'])) {
            $this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('index'));
        }
    }

    public function actionExecuteGraph()
    {
        if (!Yii::app()->user->checkAccess('dephead')&&!Yii::app()->user->checkAccess('admin'))
        {
            throw new CHttpException(403, Yii::t('yii', 'You are not authorized to perform this action.'));
        }
        $semesters = array();
        $groups = array();
        if (isset($_POST['graph']) && isset($_POST['groups'])) {
            $groups = $_POST['groups'];
            $g = $_POST['graph'];
            foreach ($g as $i => $v) {
                $findFirst = false;
                $findSecond = false;
                $counter = 0;
                foreach ($v as $j) {
                    if ($j == 'T') {
                        $counter++;
                    }
                    if ($j == ' ') {
                        break;
                    }
                    if (($j != 'T') && ($j != 'P') && (!$findFirst)) {
                        $findFirst = true;
                        $semesters[$i + 1][1] = $counter;
                        $counter = 0;
                    } elseif (($j == 'T') && ($findFirst)) {
                        $findSecond = true;
                    } elseif (($j != 'T') && ($j != 'P') && ($findSecond)) {
                        $semesters[$i + 1][2] = $counter;
                        break;
                    }
                }
            }
        }
        $weeks = array();
        $last = 0;
        $lastYear = array();
        $errors = array();
        $semestersForGroups = array();
        foreach ($groups as $course => $subGroups) {
            foreach ($subGroups as $groupId => $groupName) {
                if (!empty($lastYear) && ($course == $last)) {
                    $t = $semesters[$groupId + 1];
                    if (($t[2] != $lastYear[2]) || ($t[1] != $lastYear[1])) {
                        $errors[$groupName] = "Кількість тижнів для груп на одному курсі різна (група $groupName)";
                    }
                }
                $semestersForGroups[$groupName] = $semesters[$groupId + 1];
                if ($course <> $last) {
                    $last = $course;
                    foreach ($semesters[$groupId + 1] as $semester) {
                        $weeks[] = $semester;
                    }
                    $lastYear = $semesters[$groupId + 1];
                }
            }
        }
        Yii::app()->session['weeks'] = $weeks;
        Yii::app()->session['graph'] = $_POST['graph'];
        $this->renderPartial('semestersPlan', array('data' => $semestersForGroups, 'errors' => $errors));
    }

    public function actionMakeExcel($id)
    {

        /**@var $excel ExcelMaker */
        $excel = Yii::app()->getComponent('excel');
        $plan = WorkPlan::model()->loadContent($id);
        $excel->getDocument($plan, 'workPlan');
    }

} 