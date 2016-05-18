<?php
/**
 * Created by PhpStorm.
 * User: Arhangel
 * Date: 16.05.2016
 * Time: 10:04
 */
$this->breadcrumbs = array(
    Yii::t('present', 'Generate excel stat'),
);
?>

    <h1><?php echo Yii::t('present', 'Generate excel stat'); ?></h1>

<?php
$this->renderPartial('_form', array('model' => $model));
?>