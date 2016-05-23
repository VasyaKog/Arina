<?/**
 * пошук по почті
 * <?php echo $form->textFieldRow($model, 'email', array('class' => 'span5', 'maxlength' => 255)); ?>
* <?php echo $form->textFieldRow($model, 'identity_type', array('class' => 'span5')); 
* <? echo $form->dropDownListRow($model, 'identity_type', RolesModel::getList(), array('class' => 'span5')); ?>
*
*<script>
 */
 /*                     

 */
?>


<?php $form = $this->beginWidget('bootstrap.widgets.TbActiveForm', array(
    'action' => Yii::app()->createUrl($this->route),
    'method' => 'get',
)); 
$index = 0;

?>

<?php echo $form->textFieldRow($model, 'id', array('class' => 'span1')); ?>

<?php echo $form->textFieldRow($model, 'username', array('class' => 'span3', 'maxlength' => 255)); ?>




<?php echo $form->dropDownListRow($model, 'identity_type', RolesModel::getList(), array('id' => 'type-select','empty' => 'Виберіть тип'));
?>

<script>
                        jQuery(function () {
                            var typeSelect = $('#type-select'),
                                student = $('#student');
                                employee = $('#employee');
                                employee.hide();
                                student.hide();
                            function typeSelectChange() {
                                if (typeSelect.val() === '1' || (typeSelect.val() === '2')) {
                                    employee.hide()
                                  employee.detach();
                                  student.appendTo('section');
                                   student.show();
                                } else {

                                    student.hide();
                                    student.detach();
                                  employee.appendTo('section');
                                   
                                    employee.show();
                                }

                            }

                            typeSelectChange();
                            typeSelect.change(typeSelectChange);
                        });
                    </script>
                   <section name="outputlist"></section>
                  
                <div id="student" class="hide">
                      <?php echo $form->dropDownListRow($model, 'identity_id', Student::getLists(),array('id' => 'type-select','empty' => 'Виберіть тип'),array('class' => 'span3'));  ?>
                 </div>
                 <div id="employee" class="hide">
                      <?php echo $form->dropDownListRow($model, 'identity_id', Employee::getList(),array('id' => 'type-select','empty' => 'Виберіть ініціали'),array('class' => 'span3'));  ?>
                 </div>



<?php echo $form->dropDownListRow($model, 'role', Active::getList(), array('id' => 'type-select','empty' => 'Виберіть статус'),array('class' => 'span3')); ?>




<div class="form-actions">
    <?php $this->widget(
        'bootstrap.widgets.TbButton',
        array(
            'buttonType' => 'submit',
            'type' => 'primary',
            'label' => Yii::t('base', 'Find')
        )
    ); ?>
</div>

<?php $this->endWidget(); ?>
