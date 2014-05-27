<?php

/**
 * @author Dmytro Karpovych <ZAYEC77@gmail.com>
 */
class SubjectController extends Controller
{
    public $name = 'Subjects';

    /**
     * Creates a new model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     */
    public function actionCreate()
    {
        $model = new Subject;

        if (isset($_POST['Subject'])) {
            $model->attributes = $_POST['Subject'];
            if ($model->save())
                $this->redirect(array('index'));
        }

        if (!Yii::app()->request->isAjaxRequest)
            unset(Yii::app()->session['subject']);

        $this->render('create', array(
            'model' => $model,
        ));
    }

    public function actionAddRelation($id = null)
    {
        if (isset($_POST['SubjectRelation'])) {
            if (!isset(Yii::app()->session['subject'])) {
                Yii::app()->session['subject'] = array('add' => array(), 'delete' => array());
            }
            $subject = Yii::app()->session['subject'];
            $obj = new SubjectRelation();
            $obj->setAttributes(CMap::mergeArray(array('subject_id' => $id), $_POST['SubjectRelation']), false);
            $subject['add'][$obj->getId()] = $obj;
            for ($i = 0; $i < count($subject['delete']); $i++) {
                if ($obj->getId() == $subject['delete'][$i]['id']) {
                    unset($subject['delete'][$i]);
                    break;
                }
            }
            Yii::app()->session['subject'] = $subject;
            $this->renderPartial('_subjectRelation', array('id' => $id));
        }
    }

    public function actionRemoveRelation($id1, $id2, $id3)
    {
        if (!isset(Yii::app()->session['subject'])) {
            Yii::app()->session['subject'] = array('add' => array(), 'delete' => array());
        }
        $subject = Yii::app()->session['subject'];
        $subject['delete'][] = array('id' => $id1 . '.' . $id2 . '.' . $id3);
        Yii::app()->session['subject'] = $subject;
        $this->renderPartial('_subjectRelation', array('id' => $id1));
    }

    /**
     * Updates a particular model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id the ID of the model to be updated
     */
    public function actionUpdate($id)
    {
        $model = Subject::model()->loadContent($id);

        $this->ajaxValidation('subject-form', $model);

        if (isset($_POST['Subject'])) {
            $model->attributes = $_POST['Subject'];
            if ($model->save())
                $this->redirect(array('index'));
        }

        if (!Yii::app()->request->isAjaxRequest)
            unset(Yii::app()->session['subject']);
        $this->render('update', array(
            'model' => $model,
        ));
    }

    /**
     * @param $id
     * @throws CHttpException
     */
    public function actionDelete($id)
    {
        if (Yii::app()->request->isPostRequest) {
            Subject::model()->loadContent($id)->delete();
            unset(Yii::app()->session['subject']);
            if (!isset($_GET['ajax']))
                $this->redirect(array('index'));
        } else
            throw new CHttpException(400, Yii::t('base', 'Invalid request. Please do not repeat this request again.'));
    }

    /**
     * Lists all models.
     */
    public function actionIndex()
    {
        $speciality_id = 0;
        $cycle_id = 0;
        $conditions = array();
        $params = array();
        if (!empty($_GET['Speciality'])) {
            $speciality_id = $_GET['Speciality'];
            $conditions[] = 'relations.speciality_id = :spec';
            $params[':spec'] = $_GET['Speciality'];
        }
        if (!empty($_GET['Cycle'])) {
            $cycle_id = $_GET['Cycle'];
            $conditions[] = 'relations.cycle_id = :cycle';
            $params[':cycle'] = $_GET['Cycle'];
        }
        $config = array('pagination' => array('pageSize' => 20,));
        if (!empty($conditions)) {
            $config['criteria'] = array('with' => array('relations' => array('together' => true)), 'params' => $params, 'condition' => '(' . implode(') AND (', $conditions) . ')');
        }
        $dataProvider = new CActiveDataProvider('Subject', $config);
        $this->render('index', array(
            'dataProvider' => $dataProvider,
            'cycle_id' => $cycle_id,
            'speciality_id' => $speciality_id,
        ));
    }

    public function actionListByCycle($id)
    {
        $condition = "cycle_id = :cycle_id";
        $params = array(':cycle_id' => $id);
        if (isset($_GET['speciality_id'])) {

            $condition = "($condition) AND (speciality_id = :speciality_id)";
            $params[':speciality_id'] = $_GET['speciality_id'];
        }
        $relations = SubjectRelation::model()->findAll($condition, $params);
        echo CHtml::dropDownList('', '', CHtml::listData($relations, 'subject_id', 'subject.title'));
    }

    public function actionListBySpeciality($id)
    {
        $condition = "speciality_id = :speciality_id";
        $params = array(':speciality_id' => $id);
        if (isset($_GET['cycle_id'])) {

            $condition = "($condition) AND (cycle_id = :cycle_id)";
            $params[':cycle_id'] = $_GET['cycle_id'];
        }
        $relations = SubjectRelation::model()->findAll($condition, $params);
        echo CHtml::dropDownList('', '', CHtml::listData($relations, 'subject_id', 'subject.title'));
    }
}
