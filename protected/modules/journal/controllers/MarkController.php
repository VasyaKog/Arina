<?php

/**
 * Created by PhpStorm.
 * User: vasyl
 * Date: 23.04.16
 * Time: 10:24
 */
class MarkController extends Controller
{

    public function actionCreate($student_id,$journal_record_id){
        /**
         * $model Mark
         **/
        $model = new Mark();
        $model->student_id=$student_id;
        $model->journal_record_id=$journal_record_id;
        if(isset($_POST['Mark'])){
            $model->attributes=$_POST['Mark'];
            if($model->save()) {
                /**
                 * $journal_record JournalRecord
                 */
                $journal_record = $model->journal_record;
                var_dump($journal_record);
                $this->redirect('../default/view/'.$journal_record->load_id);
            }
        }
        $this->render('create',
            array(
                'model'=>$model,
            ));
    }
}