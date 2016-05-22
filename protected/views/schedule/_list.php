	<?php
/**
 *
 * @var ScheduleController $this
 * @var \CActiveDataProvider $provider
 */
?>
<?php
$columns = array(
    array('name' => 'id'),
);
$this->renderPartial('//tableList', array('provider' => $provider, 'columns' => $columns));
?>