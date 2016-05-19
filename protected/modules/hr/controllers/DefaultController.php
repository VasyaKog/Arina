<?php

class DefaultController extends Controller
{
    //public global $model;
    public function actionView($id)
    {
        $this->render('view', array(
            'model' =>  Employee::model()->loadContent($id),
        ));
    }

    public function actionExcelList()
    {
        /**@var $excel ExcelMaker */
        $excel = Yii::app()->getComponent('excel');
        $doc_name = 'Список працівників';
        $excel->getDocument(NULL,'employeesList', $doc_name);
    }

    public function actionExcelCard($id)
    {
        $excel = Yii::app()->getComponent('excel');
        $employee = Employee::model()->findByPk($id);
        $name = $employee->last_name .' '. $employee->first_name .' '. $employee->middle_name.' ';
        $excel->getDocument(NULL,'employeeCard',$name);

    }


    public function actionIndex()
    {
        $model = new Employee('search');
        $model->unsetAttributes(); // clear any default values
        if (isset($_GET['Employee'])) {
            $model->attributes = $_GET['Employee'];
        }

        $this->render('index', array(
            'model' => $model,
        ));
    }

    /**
     * Creates a new model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     */
    public function actionCreate()
    {
        $model = new Employee();

        $this->ajaxValidation('employee-form', $model);

        if (isset($_POST['Employee'])) {
            $model->setAttributes($_POST['Employee']);
            $model->short_name = $model->getShortName();
            if ($model->save()) {
                $this->redirect(array('index'));//, 'id' => $model->id));
            }
        }

        $this->render('create', array(
            'model' => $model,
        ));
    }

    public function actionUpdate($id)
    {
        /**
         * @var $model Student
         */
        $model = Employee::model()->loadContent($id);

        $this->ajaxValidation('student-form', $model);

        if (isset($_POST['Employee'])) {
            $model->attributes = $_POST['Employee'];
            if ($model->save())
                $this->redirect(array('view', 'id' => $model->id));
        }

        $this->render('update', array(
            'model' => $model,
        ));
    }


    public function actionDelete($id)
    {
        $model = Employee::model()->loadContent($id);
        $model->delete();
        // if AJAX request (triggered by deletion via admin grid view), we should not redirect the browser
        if (!isset($_GET['ajax']))
            $this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('admin'));
    }
}