<?php
/* @var $this SiteController */

$this->pageTitle = Yii::app()->name;
/*
if (isset(Yii::app()->user->identityId)) {
    echo 'Identity id -> ' . Yii::app()->user->identityId . '<br>';
}
if (isset(Yii::app()->user->identityType)) {
    echo 'Identity type -> ' . Yii::app()->user->identityType . '<br>';
}
    echo 'Id -> ' . Yii::app()->user->getId() . '<br>';
*/
?>

<h1>Вітаємо вас в<i> <?php echo CHtml::encode(Yii::app()->name); ?></i></h1>

<h3>
    <?php
        if (isset(Yii::app()->user->identityType)) {
            if (isset(Yii::app()->user->identityId)) {
                if (Yii::app()->user->identityType == User::TYPE_TEACHER) {
                    echo Teacher::model()->findByAttributes(array('id'=>Yii::app()->user->identityId))->getFullName() . ', викладач<br>';
                }
                else if (Yii::app()->user->identityType == User::TYPE_STUDENT) {
                    echo Student::model()->findByAttributes(array('id'=>Yii::app()->user->identityId))->getFullName() . ', студент<br>';
                }
                else if (Yii::app()->user->identityType == User::TYPE_SUPER) {
                     echo Employee::model()->findByAttributes(array('id'=>Yii::app()->user->identityId))->getFullName() . '<br>';
                    echo 'Адміністратор<br>';
                }
                else if (Yii::app()->user->identityType == User::TYPE_INSPECTOR) {
                    echo 'Інспектор кадрів<br>';
                    echo Employee::model()->findByAttributes(array('id'=>Yii::app()->user->identityId))->getFullName() . '<br>';
                }
                else if (Yii::app()->user->identityType == User::TYPE_NAVCH) {
                    echo 'Секретар навчальної частини<br>';
                    echo Employee::model()->findByAttributes(array('id'=>Yii::app()->user->identityId))->getFullName() . '<br>';
                    
                }
                else if (Yii::app()->user->identityType == User::TYPE_ZASTUPNIK) {
                    echo 'Заступник директора коледжу<br>';
                    echo Employee::model()->findByAttributes(array('id'=>Yii::app()->user->identityId))->getFullName() . '<br>';
                    
                }
                else if (Yii::app()->user->identityType == User::TYPE_DIRECTOR) {
                    echo 'Директор коледжу<br>';
                    echo Employee::model()->findByAttributes(array('id'=>Yii::app()->user->identityId))->getFullName() . '<br>';
                    
                }
            }
        }
    ?>
</h3>
</h2>
<?php
    if (!Yii::app()->user->isGuest) {
        $user = User::model()->findByAttributes(array('id'=>Yii::app()->user->id));
        echo 'Користувач ' . CHtml::link($user->username, 'user/update/' . $user->id) . '<br>';
        if (Yii::app()->user->checkAccess('dephead')) {
            $department = Department::model()->findByAttributes(array('head_id'=>Yii::app()->user->identityId));
            if (isset($department))
                echo 'Відділення ' . CHtml::link($department->title, 'department/' . $department->id) . '<br>';
        }
        if (Yii::app()->user->checkAccess('cychead')) {
            $cyccomm = CyclicCommission::model()->findByAttributes(array('head_id'=>Yii::app()->user->identityId));
            if (isset($cyccomm))
                echo 'Циклова комісія ' . CHtml::link($cyccomm->title, 'cyclicCommission/' . $cyccomm->id) . '<br>';
        }
        if (Yii::app()->user->checkAccess('curator')) {
            $group = Group::model()->findByAttributes(array('curator_id'=>Yii::app()->user->identityId));
            if (isset($group))
                echo 'Група ' . CHtml::link($group->title, 'group/' . $group->id) . '<br>';
        }
        if (Yii::app()->user->checkAccess('prefect')) {
            $group = Group::model()->findByAttributes(array('monitor_id'=>Yii::app()->user->identityId));
            if (isset($group))
                echo 'Група ' . CHtml::link($group->title, 'group/' . $group->id) . '<br>';
        }
        else if (Yii::app()->user->checkAccess('student')) {
            /**
             * @var $student Student
             */
            $student = Student::model()->findByAttributes(array('id'=>Yii::app()->user->identityId));
            $groups=$student->getGroupListLinks();
            var_dump($groups);
        }
    }
?>
</h2>

<div style="height: 300px"></div>