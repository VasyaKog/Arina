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
            $loadMas=Load::model()->findAll();
            foreach($loadMas as $item){
                /**
                 * @var $item Load
                 */
                if($item->study_year_id==$_POST['JournalViewer']['studyYearId']&&$item->group_id==$_POST['JournalViewer']['groupId']){
                    $load=$item; break;
                }
            }
            if(isset($load)) $this->actionView($load->id);
            return;
        }

        $this->render('index', array(
            'model' => $model,
        ));




    }

    public function actionChangeGroupList(){
        $selectedyear=$_POST['JournalViewer']['studyYearId'];
        var_dump($selectedyear);
        if($selectedyear==0) {echo CHtml::tag('option',array('value'=>0),Yii::t('journal','Select study year'),true); return;}
        $data=Group::getGroupsByYearId($selectedyear);
        foreach($data as $item){
            echo CHtml::tag('option',array('value'=>$item->id),$item->title,true);
        }
    }

   /* public function actionChangeSubjectList(){
        $selectedyear=$_POST['JournalViewer']['studyYearId'];
        $selectedgroup=$_POST['JournalViewer']['groupId'];
        /**
         * @var $data Load[]
         **/
       /* $data=Load::model()->findAllByAttributes(array('group_id'=>$selectedgroup,'study_year_id'=>$selectedyear));
        $result=array();
        foreach
    }*/

    public function actionView($id)
    {
        $this->render('view', array(
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