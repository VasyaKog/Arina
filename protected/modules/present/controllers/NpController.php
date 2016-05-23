<?php
/**
 * Created by PhpStorm.
 * User: Arhangel
 * Date: 20.05.2016
 * Time: 9:23
*/
class NpController extends Controller
{
    
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