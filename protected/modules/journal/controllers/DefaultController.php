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


        $this->render('index', array(
            'model' => $model,
        ));




    }

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

}