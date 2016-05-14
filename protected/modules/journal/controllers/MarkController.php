<?php

/**
 * Created by PhpStorm.
 * User: vasyl
 * Date: 23.04.16
 * Time: 10:24
 */
class MarkController extends Controller
{

    public function actionChangeMarkList(){
        /**
         * @var Evaluation[] $Evaluations
         */
        $Evaluations=Evaluation::model()->findAllByAttributes(array('system_id'=>$_POST['Mark']['system_id']));
        echo CHtml::tag('option', array('value' => 0), Yii::t('journal', 'Select evolution'), true);
        foreach($Evaluations as $Evaluation){
            echo CHtml::tag('option', array('value' => $Evaluation->id), $Evaluation->title, true);
        }
    }

    public function actionCreate($student_id,$journal_record_id){
        /**
         * $model Mark
         **/
        $model = new Mark();
        $model->student_id=$student_id;
        $model->journal_record_id=$journal_record_id;
        $journal_record = $model->journal_record;
        $type=$model->journal_record->types;
        if(isset($_POST['Mark'])){
            $model->attributes=$_POST['Mark'];
            if(isset($model->value_id))
                $model->date=date('Y-m-d');
            if(isset($model->retake_value_id))
                $model->retake_date=date('Y-m-d');
            if($model->save()) {
                /**
                 * $journal_record JournalRecord
                 */
                $this->redirect('views/'.$model->id);
            }
        }
        $this->render('create',
            array(
                'model'=>$model,
                'type'=>$type,
            ));
    }

    public function actionViews($id){
        /**
         * @var $model Mark
         */
        $model=Mark::model()->findByPk($id);
        $type=$model->journal_record->types;
        $this->render('view',
            array(
                'model'=>$model,
                'type'=>$type,
            ));
    }

    public function actionUpdate($id){
        /**
         * @var $model Mark
         */

        $model=Mark::model()->findByPk($id);   
        $type=$model->journal_record->types;
        if(isset($_POST['Mark']))
        {
            $model->attributes=$_POST['Mark'];
            if($model->save())
                $this->redirect('../views/'.$model->id);
        }
        $this->render('update',
            array(
                'model'=>$model,
                'type'=>$type,
            ));
    }
}