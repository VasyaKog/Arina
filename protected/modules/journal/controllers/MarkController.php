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
        $access = false;
        if (isset(Yii::app()->user->identityType)) {
            if (isset(Yii::app()->user->identityId)) {
                if (Yii::app()->user->identityType == User::TYPE_SUPER) {
                    $teacher = Teacher::model()->findByPk(Yii::app()->user->identityId);
                    if ($model->journal_record->teacher_id == Yii::app()->user->identityId) {
                        $access = true;
                    }
                }
                if (Yii::app()->user->identityType == User::TYPE_TEACHER) {
                    /**
                     * @var $teacher Teacher
                     */
                    $teacher = Teacher::model()->findByPk(Yii::app()->user->identityId);
                    if ($model->journal_record->load->teacher_id == Yii::app()->user->identityId) {
                        $access = true;
                    }
                }

            }
        }
        if(!$access)
        {
            throw new CHttpException(403, Yii::t('yii','You are not authorized to perform this action.'));
        }
        $type=$model->journal_record->types;
        $this->ajaxValidation('mark-form', $model);
        if(isset($_POST['Mark'])){
            $model->attributes=$_POST['Mark'];
            if(isset($model->value_id)){
                    if(is_null($model->date)){
                        if($model->value_id!=0)
                        {
                        $model->date=date('Y-m-d');
                        }
                    }
            }
            if(isset($model->retake_value_id)){
                if(is_null($model->retake_date)){
                    if($model->retake_value_id!=0)
                    {
                        $model->retake_date=date('Y-m-d');
                    }
                }
            }
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
        $access=false;
        if (isset(Yii::app()->user->identityType)) {
            if (isset(Yii::app()->user->identityId)) {
                if (Yii::app()->user->identityType == User::TYPE_SUPER) {
                    $access=true;
                    $teacher=Teacher::model()->findByPk(Yii::app()->user->identityId);
                    if ($model->journal_record->teacher_id == Yii::app()->user->identityId) {
                        $access = true;
                    } elseif(in_array($model->journal_record->group_id,$teacher->getGroupListArray())) {
                        $access = true;
                    }
                }
                if (Yii::app()->user->identityType == User::TYPE_TEACHER) {
                    /**
                     * @var $teacher Teacher
                     */
                    $teacher=Teacher::model()->findByPk(Yii::app()->user->identityId);
                    if ($model->journal_record->load->teacher_id == Yii::app()->user->identityId) {
                        $access = true;
                    } elseif(in_array($model->journal_record->load->group_id,$teacher->getGroupListArray())) {
                        $access = true;
                    }
                }
                if (Yii::app()->user->identityType == User::TYPE_STUDENT) {
                    $student=Student::model()->findByPk(Yii::app()->user->identityId);
                    if(in_array($model->journal_record->load->group_id,$student->getGroupListArray())&&in_array($student->id,JournalStudents::getAllStudentsInArray($model->journal_record->load))){
                        $access=true;
                        $t=false;
                    } else {
                        /** @var $group Group*/
                        $group=Group::model()->findByPk($model->journal_record->load->group_id);
                        if($student->id==$group->monitor_id) $access=true;
                    }
                }
                if (Yii::app()->user->identityType == User::TYPE_INSPECTOR) {
                    $access=true;
                    $teacher=Teacher::model()->findByPk(Yii::app()->user->identityId);
                    if ($model->journal_record->teacher_id == Yii::app()->user->identityId) {
                        $access = true;
                    } elseif(in_array($model->journal_record->group_id,$teacher->getGroupListArray())) {
                        $access = true;
                    }
                }
                if (Yii::app()->user->identityType == User::TYPE_NAVCH) {
                    $access=true;
                    $teacher=Teacher::model()->findByPk(Yii::app()->user->identityId);
                    if ($model->journal_record->load->teacher_id == Yii::app()->user->identityId) {
                        $access = true;
                    } elseif(in_array($model->journal_record->load->group_id,$teacher->getGroupListArray())) {
                        $access = true;
                    }
                }
                if (Yii::app()->user->identityType == User::TYPE_ZASTUPNIK) {
                    $access=true;
                    $teacher=Teacher::model()->findByPk(Yii::app()->user->identityId);
                    if ($model->journal_recor->load->teacher_id == Yii::app()->user->identityId) {
                        $access = true;
                    } elseif(in_array($model->journal_record->load->group_id,$teacher->getGroupListArray())) {
                        $access = true;
                    }
                }
                if (Yii::app()->user->identityType == User::TYPE_DIRECTOR) {
                    $access=true;
                    $teacher=Teacher::model()->findByPk(Yii::app()->user->identityId);
                    if ($model->journal_record->load->teacher_id == Yii::app()->user->identityId) {
                        $access = true;
                    } elseif(in_array($model->journal_record->load->group_id,$teacher->getGroupListArray())) {
                        $access = true;
                    }
                }
            }
        }
        if(!$access)
        {
            throw new CHttpException(403, Yii::t('yii','You are not authorized to perform this action.'));
        }
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
        $access = false;
        if (isset(Yii::app()->user->identityType)) {
            if (isset(Yii::app()->user->identityId)) {
                if (Yii::app()->user->identityType == User::TYPE_SUPER) {
                    $teacher = Teacher::model()->findByPk(Yii::app()->user->identityId);
                    if ($model->journal_record->teacher_id == Yii::app()->user->identityId) {
                        $access = true;
                    }
                }
                if (Yii::app()->user->identityType == User::TYPE_TEACHER) {
                    if ($model->journal_record->load->teacher_id == Yii::app()->user->identityId) {
                        $access = true;
                    }
                }

            }
        }
        if(!$access)
        {
            throw new CHttpException(403, Yii::t('yii','You are not authorized to perform this action.'));
        }
        $this->ajaxValidation('mark-form', $model);
        if(isset($_POST['Mark']))
        {

            $model->attributes=$_POST['Mark'];
            if(isset($model->value_id)){
                if(is_null($model->date)){
                    if($model->value_id!=0)
                    {
                        $model->date=date('Y-m-d');
                    }
                }
            }
            if(isset($model->retake_value_id)){
                if(is_null($model->retake_date)){
                    if($model->retake_value_id!=0)
                    {
                        $model->retake_date=date('Y-m-d');
                    }
                }
            }
            if($model->save())
                $this->redirect('../views/'.$model->id);
        }
        $this->render('update',
            array(
                'model'=>$model,
                'type'=>$type,
            ));
    }

    public  function actionDelete($id){
        /**
         * @var $model Mark
         */

        $model=Mark::model()->findByPk($id);
        $access = false;
        if (isset(Yii::app()->user->identityType)) {
            if (isset(Yii::app()->user->identityId)) {
                if (Yii::app()->user->identityType == User::TYPE_SUPER) {
                    $teacher = Teacher::model()->findByPk(Yii::app()->user->identityId);
                    if ($model->journal_record->teacher_id == Yii::app()->user->identityId) {
                        $access = true;
                    }
                }
                if (Yii::app()->user->identityType == User::TYPE_TEACHER) {
                    /**
                     * @var $teacher Teacher
                     */
                    $teacher = Teacher::model()->findByPk(Yii::app()->user->identityId);
                    if ($model->journal_record->load->teacher_id == Yii::app()->user->identityId) {
                        $access = true;
                    }
                }

            }
        }
        if(!$access)
        {
            throw new CHttpException(403, Yii::t('yii','You are not authorized to perform this action.'));
        }
    }
}
