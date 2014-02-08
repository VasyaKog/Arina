<?php
/**
 *
 * @var GroupController $this
 * @var \Group $model
 */

$this->breadcrumbs = array(
    Yii::t('group', 'Groups') => array('index'),
    Yii::t('group', 'New group creating'),
);
?>
<header>
    <h2><?php echo Yii::t('group', 'New group creating'); ?></h2>
</header>
<?php
$this->renderPartial('_form', array('model' => $model));
?>