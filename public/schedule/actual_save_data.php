<?php
/**
 * Created by PhpStorm.
 * User: kostia
 * Date: 22.06.15
 * Time: 20:13
 */
require_once "classes/queries.php";

$select = new Query();
$select->openConnect();

if (!($select->insertActualSchedule($_POST['arr'],"actual_shedule",$_POST["date"]))) echo 'error';
?>