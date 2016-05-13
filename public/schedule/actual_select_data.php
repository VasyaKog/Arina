<?php
/**
 * Created by PhpStorm.
 * User: kostia
 * Date: 22.06.15
 * Time: 0:33
 */

require_once "classes/queries.php";
$select = new Query();
$select->openConnect();
$q = $select->selectQuery(array("*"), "timetable", "`study_year_id`=".$_POST['id']." AND `semester`='".$_POST['sem']."' AND `day`=".$_POST['day']." AND `type`=".$_POST['type']);
$count_timetable = 0;
while ($us = mysql_fetch_array($q)){
    $count_timetable++;
}

$q = $select->selectQuery(array("*"), "actual_shedule", "`date`='".$_POST['date']."' AND `day`=".$_POST['day']." AND `type`=".$_POST['type']);
$count_actual_schedule = 0;
while ($us = mysql_fetch_array($q)){
    $count_actual_schedule++;
}

echo json_encode(array('count_timetable'=>$count_timetable, 'count_actual_schedule'=>$count_actual_schedule));
exit();
?>