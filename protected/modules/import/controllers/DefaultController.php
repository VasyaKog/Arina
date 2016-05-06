<?php

class DefaultController extends Controller
{
    public function actionIndex()
    {
        if(!Yii::app()->user->checkAccess('inspector')&&!Yii::app()->user->checkAccess('admin'))
        {
            throw new CHttpException(403, Yii::t('yii','You are not authorized to perform this action.'));
        }
        $model = new FileModel();
        if (isset($_POST['FileModel'])) {
            $model->attributes = $_POST['FileModel'];
            $temp = CUploadedFile::getInstance($model, 'file');
            $temp->saveAs('files/' . $temp->name);
            $fileName = Yii::app()->basePath . '/../public/files/' . $temp;

            $excelReader = new ExcelReader();
            $excelReader->init();

            $students = $excelReader->importStudents($fileName);

            $dataProvider = new CArrayDataProvider(new Student());
            $dataProvider->setData($students);

            $this->render('view', array(
                'dataProvider' => $dataProvider,
            ));
        } else {
            $this->render('index', array(
                'model' => $model,
            ));
        }
    }
}