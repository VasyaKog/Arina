<?php
/** @var User $model  */
/** @var UserController $this  */
/** @var TbActiveForm $form */
$form = $this->beginWidget('bootstrap.widgets.TbActiveForm', array(
    'id' => 'user-form',
    'enableAjaxValidation' => false,
)); ?>

<p class="help-block">Поля, відмічені <span class="required">*</span>, обов'язкові для заповнення.</p>

<?php echo $form->errorSummary($model); ?>

<?php echo $form->textFieldRow($model, 'username', array('class' => 'span5', 'maxlength' => 255)); ?>

<?php echo $form->passwordFieldRow($model, 'password', array('class' => 'span5', 'maxlength' => 255)); ?>

<?php echo $form->textFieldRow($model, 'email', array('class' => 'span5', 'maxlength' => 255)); ?>

<?php
if (Yii::app()->user->checkAccess('admin')) {
    //echo $form->textFieldRow($model, 'role', array('class' => 'span5'));

      //echo $form->dropDownListRow($model, 'identity_type', $arr_role, array('id' => 'type-select'));
$list= Yii::app()->db->createCommand('select r.id from `AuthAssignment` a, `roles` r where a.itemname=r.name and a.userid=:id')->bindValue('id',$model->id)->queryAll();

$index = 0;
if (!empty($list))
{
	$index = $list[0]['id'];
}

echo $form->dropDownListRow($model, 'identity_type', RolesModel::getList(), array('id' => 'type-select', 'options' => array( $index =>array('selected'=>true))));
?>
<script>
                        jQuery(function () {
                            var typeSelect = $('#type-select'),
                                student = $('#student');
                                employee = $('#employee');
                                employee.hide();
                                student.hide();
                            function typeSelectChange() {
                                if (typeSelect.val() === '2') {
                                	employee.hide();
                                   student.show();
                                } else {
                                    student.hide();
                                    employee.show();
                                }

                            }

                            typeSelectChange();
                            typeSelect.change(typeSelectChange);
                        });
                    </script>
                    
                <div id="student" class="hide">
                      <?php echo $form->dropDownListRow($model, 'identity_id', Student::getList());  ?>
                 </div>
                 <div id="employee" class="hide">
                      <?php echo $form->dropDownListRow($model, 'identity_id', Employee::getList('id', 'fullname'));  ?>
                 </div>
      
    
<?php } 
echo $form->dropDownListRow($model, 'role', Active::getList(), array('id' => 'type-select', 'options' => array( $index =>array('selected'=>true))));?>
  
            

    


<?php $this->renderPartial('//formButtons', array('model' => $model)); ?>

<?php $this->endWidget(); ?>