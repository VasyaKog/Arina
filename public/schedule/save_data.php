<?php
/**
 * Created by PhpStorm.
 * User: kostia
 * Date: 08.06.15
 * Time: 23:01
 */
require_once "classes/queries.php";

$select = new Query();
$select->openConnect();

if (!($select->insertTimetable($_POST['arr'],"timetable",$_POST["year"],$_POST["sem"]))) echo 'error';
?>