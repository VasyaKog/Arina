<?php
@session_start();
require_once "classes/queries.php";
require_once "classes/func.php";
if (count($_SESSION)<=1) header("Location:../index.php");

$select = new Query();
$select->openConnect();

$q = $select->selectQuery(array("*"), "study_year");
$years = array();
while ($us = mysql_fetch_array($q)){
    $tmp = array();
    $tmp['id'] = $us['id'];  $tmp['begin'] = $us['begin']; $tmp['end'] = $us['end'];
    $years[] = $tmp;
}
?>

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <!--<link rel="stylesheet" type="text/css" href="/assets/css/bootstrap.css">-->
    <link rel="stylesheet" type="text/css" href="css/style.css">
    <link rel="stylesheet" type="text/css" href="css/style_actual.css">
    <link rel="stylesheet" type="text/css" href="css/jquery.datetimepicker.css"/>
    <script type="text/javascript" src="js/jquery.js"></script>
    <script type="text/javascript" src="js/jquery.autocomplete.js"></script>
    <script type="text/javascript" src="js/jquery.datetimepicker.js"></script>
    <script type="text/javascript" src="js/function_actual.js"></script>
    <script type="text/javascript" src="js/save_actual_data.js"></script>

    <title>Формування фактичного розкладу</title>
</head>
<body>
    <script></script>
    <div id="wrapper">
        <?php if ((!isset($_GET['year']))&&(!isset($_GET['date']))&&(!isset($_GET['sem']))&&(!isset($_GET['day']))&&(!isset($_GET['type']))&&(!isset($_GET['edit']))){
        if (count($_GET)>0) header("Location: create_actual.php");
            ?>
            <script>var years = <?=json_encode($years)?>;</script>
            <fieldset>
                <legend>Виберіть параметри</legend>
                <div id="date_wrap">
                    <?php $date = date("d.m.Y",time() + 86400);?>
                <label>Дата: </label>
                <input type="text" id="data" value="<?=$date?>" readonly onchange="changeDate(this)"/> </div>
                <select id="type">
                    <option value="0">Чисельник</option>
                    <option value="1">Знаменник</option>
                </select>
                <?
                $date=explode(".", $date);
                $week = date("w", mktime(0, 0, 0, $date[1], $date[0], $date[2]))?>
                <select id="day">
                    <option value="1" <?php if($week==1) echo 'selected';?>>Понеділок</option>
                    <option value="2" <?php if($week==2) echo 'selected';?>>Вівторок</option>
                    <option value="3" <?php if($week==3) echo 'selected';?>>Середа</option>
                    <option value="4" <?php if($week==4) echo 'selected';?>>Четвер</option>
                    <option value="5" <?php if($week==5) echo 'selected';?>>Пятниця</option>
                </select>

                <button id="btn_shape" onclick="clickShape()">Формувати</button>
            </fieldset>

            <script>
                $('#data').datetimepicker({
                    lang:'uk',
                    timepicker:false,
                    format:'d.m.Y',
                    formatDate:'Y.m.d',
                    dayOfWeekStart: 1,
                    closeOnDateSelect: 0
                });

            </script>

        <?} else {
            $date = array();
            if(isset($_GET['year'])) {
                $perevirka = false;
                foreach($years as $yea) if($_GET['year']==$yea['id']) {$perevirka=true; break;}
                if(!$perevirka) header("Location: create_actual.php");
            } //перевірка на існуючий ID року
            else header("Location: create_actual.php");

            if(isset($_GET['date'])) {
                $perevirka = preg_match('~^0?(\d|[0-2]\d|3[0-2])\.0?(\d|1[0-2])\.(\d{4})$~', $_GET['date']);
                if($perevirka) {
                    $date = explode('.', $_GET['date']);
                    $perevirka = checkdate((int) $date[1], (int) $date[0],(int) $date[2]);
                }
                if(!$perevirka) header("Location: create_actual.php");
            }
            else header("Location: create_actual.php");

            if(isset($_GET['sem'])) {
                $perevirka = false;
                if(($_GET['sem']=='fill')||($_GET['sem']=='spring')) $perevirka=true;
                if(!$perevirka) header("Location: create_actual.php");
            }
            else header("Location: create_actual.php");

            if(isset($_GET['day'])) {
                $perevirka = false;
                for ($i=1; $i<=5; $i++) if($_GET['day']==$i){$perevirka=true; break;}
                if(!$perevirka) header("Location: create_actual.php");
            }
            else header("Location: create_actual.php");

            if(isset($_GET['type'])) {
                $perevirka = false;
                if(($_GET['type']==0)||($_GET['type']==1)) $perevirka=true;
                if(!$perevirka) header("Location: create_actual.php");
            }
            else header("Location: create_actual.php");

            if(isset($_GET['edit'])) {
                $perevirka = false;
                if(($_GET['edit']==0)||($_GET['edit']==1)) $perevirka=true;
                if(!$perevirka) header("Location: create_actual.php");
            }
            else header("Location: create_actual.php");

            $title="";
            switch ($_GET['day']){
                case 1: $title="Понеділок"; break;
                case 2: $title="Вівторок"; break;
                case 3: $title="Середа"; break;
                case 4: $title="Четвер"; break;
                case 5: $title="Пятниця"; break;
            }
            if($_GET['type']==0) $title.=", Чисельник"; else $title.=", Знаменник";
            ?>
            <h1>Формування фактичного розкладу на <?=$_GET['date']?> (<?=$title?>)</h1>

            <?php
            $func = new GlobalFunction($_GET['year']);
            if ($_GET['sem'] == 'fill') {
                $gr = $func->createGroups(0);
                $subj = $func->createSubject(0);
            }
            else {
                $gr = $func->createGroups(1);
                $subj = $func->createSubject(1);
            }
            $table_width = 0;
            foreach ($gr as $kurs)
                foreach ($kurs as $grupa) $table_width++;
            $table_width = $table_width * 307;

            $audience = $func->getAudience();
            $arr_audience = $func->getFullAudiences();
            $edit = false;
            if($_GET['edit']==1){
                $arr_data_timetable = $func->getActualSchedule($_GET['date'], $_GET['day']);
                $edit = true;
                if(count($arr_data_timetable)==0){ $arr_data_timetable = $func->getTimetable($_GET["year"], $_GET["sem"], $_GET["day"]); $edit = false; }
            }
            else $arr_data_timetable = $func->getTimetable($_GET["year"], $_GET["sem"], $_GET["day"]);
            ?>
            <script>
                var actual_date = <?=json_encode($_GET['date'])?>;
                var actual_day = <?=json_encode($_GET['day'])?>;
                var actual_type = <?=json_encode($_GET['type'])?>; </script>
            <div id="button_save" onclick="save_actual_schedule()">Зберегти</div>
            <div id="buttons_table">
                <div id="button_course1" onclick="hoverCourse(this)" style="background-color: #CB5151"
                     onmouseover="if(this.style.backgroundColor=='rgb(203, 81, 81)') this.style.backgroundColor='#ee5700'; else this.style.backgroundColor='#229435' "
                     onmouseout="if(this.style.backgroundColor=='rgb(238, 87, 0)') this.style.backgroundColor='#CB5151'; else this.style.backgroundColor='green'">
                    Сховати 1 курс
                </div>
                <div id="button_course2" onclick="hoverCourse(this)" style="background-color: #CB5151"
                     onmouseover="if(this.style.backgroundColor=='rgb(203, 81, 81)') this.style.backgroundColor='#ee5700'; else this.style.backgroundColor='#229435' "
                     onmouseout="if(this.style.backgroundColor=='rgb(238, 87, 0)') this.style.backgroundColor='#CB5151'; else this.style.backgroundColor='green'">
                    Сховати 2 курс
                </div>
                <div id="button_course3" onclick="hoverCourse(this)" style="background-color: #CB5151"
                     onmouseover="if(this.style.backgroundColor=='rgb(203, 81, 81)') this.style.backgroundColor='#ee5700'; else this.style.backgroundColor='#229435' "
                     onmouseout="if(this.style.backgroundColor=='rgb(238, 87, 0)') this.style.backgroundColor='#CB5151'; else this.style.backgroundColor='green'">
                    Сховати 3 курс
                </div>
                <div id="button_course4" onclick="hoverCourse(this)" style="background-color: #CB5151"
                     onmouseover="if(this.style.backgroundColor=='rgb(203, 81, 81)') this.style.backgroundColor='#ee5700'; else this.style.backgroundColor='#229435' "
                     onmouseout="if(this.style.backgroundColor=='rgb(238, 87, 0)') this.style.backgroundColor='#CB5151'; else this.style.backgroundColor='green'">
                    Сховати 4 курс
                </div>
                <button id="clear" onclick="if(confirm('Увага! Всі не збережені зміни буде втрачено. Продовжити?'))top.location.href='create_actual.php'">Відмінити</button>
            </div>

        <table class="rozklad" style="width: <?= $table_width + 35; ?>px;">
            <thead>
            <tr>
                <th class="number"></th>
                <?php
                foreach ($gr as $kurs)
                    foreach ($kurs as $grupa) {
                        ?>
                        <th id="id_group:<?= $grupa['id'] ?>" class="course:<?= $grupa['course'] ?>"
                            colspan="2"><?= $grupa['title'] ?></th>
                    <?php } ?></tr>
            </thead>
            <script>
                var data = <?=json_encode($gr)?>;
                var predmets = <?=json_encode($subj)?>;
                var auditor = <?=json_encode($audience)?>.split(' ');
                var arr_auditor = <?=json_encode($arr_audience)?>;
                var group;
                var para;
                var input_sel;
                var prev_input_sel;
                var input_value;
            </script>

            <tr class="row:0" id="tr_begin">
                <td rowspan="4" class="number">1-2</td>
                <?php foreach ($gr as $kurs) foreach ($kurs as $grupa) { ?>
                    <td class="id_group:<?= $grupa['id'] ?>" id="border_lft_rht" colspan="2">
                        <select class="subjects" id="para:1-2" onmouseover="getSubjects(this)" onclick="clickSubject(this)">
                            <option value="-1">Виберіть предмет</option>
                            <?php
                            foreach ($arr_data_timetable as $dat)
                                if (($dat["group_id"] == $grupa['id']) && ($dat["para"] == "1-2") && ($dat["type"] == $_GET['type'])) {
                                    $tmp_arr[] = $dat; ?>
                                    <option value="<?= $dat['subject_id'] ?>" selected><?= $dat['subject'] ?></option>
                                <?php } ?>
                        </select>
                    </td>
                <?php } ?>
            </tr>
            <tr class="row:0">
                <?php foreach ($gr as $kurs) foreach ($kurs as $grupa) { ?>
                    <td id="bd_left"
                        class="teacher:<?= $grupa['id'] ?>"><?php foreach ($tmp_arr as $dat) if (($dat["group_id"] == $grupa['id']) && ($dat["para"] == "1-2") && ($dat["type"] == $_GET["type"]) && ($dat["teacher1_name"] != '')) {
                            echo $dat['teacher1_name'];
                            break;
                        } ?></td>
                    <?php $audit = '';
                    foreach ($tmp_arr as $dat) if (($dat["group_id"] == $grupa['id']) && ($dat["para"] == "1-2") && ($dat["type"] == $_GET["type"]) && ($dat["audience1_number"] != '')) {
                        $audit = $dat['audience1_number'];
                        break;
                    } ?>
                    <td id="bd_right"><input class="audience:<?= $grupa['id'] ?>" id="audiens" type="text"
                                             onkeyup="input_sel=this"
                                             value="<?= $audit ?>" <?php if ($audit == '') echo 'readonly'; ?>/></td>
                <?php } ?>
            </tr>
            <tr class="row:0">
                <?php foreach ($gr as $kurs) foreach ($kurs as $grupa) { ?>
                    <td id="bd_left"
                        class="teacher_pract:<?= $grupa['id'] ?>"><?php foreach ($tmp_arr as $dat) if (($dat["group_id"] == $grupa['id']) && ($dat["para"] == "1-2") && ($dat["type"] == $_GET["type"]) && ($dat["teacher2_name"] != '')) {
                            echo $dat['teacher2_name'];
                            break;
                        } ?></td>
                    <?php $audit = '';
                    foreach ($tmp_arr as $dat) if (($dat["group_id"] == $grupa['id']) && ($dat["para"] == "1-2") && ($dat["type"] == $_GET["type"]) && ($dat["audience2_number"] != '')) {
                        $audit = $dat['audience2_number'];
                        break;
                    } ?>
                    <td id="bd_right"><input class="audience_pract:<?= $grupa['id'] ?>" id="audiens" type="text"
                                             onkeyup="input_sel=this"
                                             value="<?= $audit ?>" <?php if ($audit == '') echo 'readonly'; ?>/></td>
                <?php } ?>
            </tr>
            <tr class="row:0">
                <?php foreach ($gr as $kurs) foreach ($kurs as $grupa) { ?>
                    <td class="radio" id="border_radio" colspan="2">
                        <?php $radio = 'none'; foreach ($tmp_arr as $dat) if (($dat["group_id"] == $grupa['id']) && ($dat["para"] == "1-2") && ($dat["type"] == $_GET["type"])) {
                            $radio = 'label';
                            break;
                        } ?>
                        <label style="display: <?=$radio?>"><input type="radio" name="radio_0:<?= $grupa['id'] ?>" <?if(($edit)&&($dat["type_lesson"]==0)&&($dat["type_lesson"]!=null)) echo "checked";?>>Лекційне</label>
                        <label style="display: <?=$radio?>"><input type="radio" name="radio_0:<?= $grupa['id'] ?>" <?if(($edit)&&($dat["type_lesson"]==1)) echo "checked";?>>Практичне</label>
                        <label style="display: <?=$radio?>"><input type="radio" name="radio_0:<?= $grupa['id'] ?>" <?if(($edit)&&($dat["type_lesson"]==2)) echo "checked";?>>Лабораторне</label>
                    </td>
                <?php } $tmp_arr=array();?>
            </tr>


            <tr class="row:1" id="tr_begin">
                <td rowspan="4" class="number">3-4</td>
                <?php foreach ($gr as $kurs) foreach ($kurs as $grupa) { ?>
                    <td class="id_group:<?= $grupa['id'] ?>" id="border_lft_rht" colspan="2">
                        <select class="subjects" id="para:3-4" onmouseover="getSubjects(this)" onclick="clickSubject(this)">
                            <option value="-1">Виберіть предмет</option>
                            <?php
                            foreach ($arr_data_timetable as $dat)
                                if (($dat["group_id"] == $grupa['id']) && ($dat["para"] == "3-4") && ($dat["type"] == $_GET['type'])) {
                                    $tmp_arr[] = $dat; ?>
                                    <option value="<?= $dat['subject_id'] ?>" selected><?= $dat['subject'] ?></option>
                                <?php } ?>
                        </select>
                    </td>
                <?php } ?>
            </tr>
            <tr class="row:1">
                <?php foreach ($gr as $kurs) foreach ($kurs as $grupa) { ?>
                    <td id="bd_left"
                        class="teacher:<?= $grupa['id'] ?>"><?php foreach ($tmp_arr as $dat) if (($dat["group_id"] == $grupa['id']) && ($dat["para"] == "3-4") && ($dat["type"] == $_GET["type"]) && ($dat["teacher1_name"] != '')) {
                            echo $dat['teacher1_name'];
                            break;
                        } ?></td>
                    <?php $audit = '';
                    foreach ($tmp_arr as $dat) if (($dat["group_id"] == $grupa['id']) && ($dat["para"] == "3-4") && ($dat["type"] == $_GET["type"]) && ($dat["audience1_number"] != '')) {
                        $audit = $dat['audience1_number'];
                        break;
                    } ?>
                    <td id="bd_right"><input class="audience:<?= $grupa['id'] ?>" id="audiens" type="text"
                                             onkeyup="input_sel=this"
                                             value="<?= $audit ?>" <?php if ($audit == '') echo 'readonly'; ?>/></td>
                <?php } ?>
            </tr>
            <tr class="row:1">
                <?php foreach ($gr as $kurs) foreach ($kurs as $grupa) { ?>
                    <td id="bd_left"
                        class="teacher_pract:<?= $grupa['id'] ?>"><?php foreach ($tmp_arr as $dat) if (($dat["group_id"] == $grupa['id']) && ($dat["para"] == "3-4") && ($dat["type"] == $_GET["type"]) && ($dat["teacher2_name"] != '')) {
                            echo $dat['teacher2_name'];
                            break;
                        } ?></td>
                    <?php $audit = '';
                    foreach ($tmp_arr as $dat) if (($dat["group_id"] == $grupa['id']) && ($dat["para"] == "3-4") && ($dat["type"] == $_GET["type"]) && ($dat["audience2_number"] != '')) {
                        $audit = $dat['audience2_number'];
                        break;
                    } ?>
                    <td id="bd_right"><input class="audience_pract:<?= $grupa['id'] ?>" id="audiens" type="text"
                                             onkeyup="input_sel=this"
                                             value="<?= $audit ?>" <?php if ($audit == '') echo 'readonly'; ?>/></td>
                <?php } ?>
            </tr>
            <tr class="row:1">
                <?php foreach ($gr as $kurs) foreach ($kurs as $grupa) { ?>
                    <td class="radio" id="border_radio" colspan="2">
                        <?php $radio = 'none'; foreach ($tmp_arr as $dat) if (($dat["group_id"] == $grupa['id']) && ($dat["para"] == "3-4") && ($dat["type"] == $_GET["type"])) {
                            $radio = 'label';
                            break;
                        } ?>
                        <label style="display: <?=$radio?>"><input type="radio" name="radio_1:<?= $grupa['id'] ?>" <?if(($edit)&&($dat["type_lesson"]==0)&&($dat["type_lesson"]!=null)) echo "checked";?>>Лекційне</label>
                        <label style="display: <?=$radio?>"><input type="radio" name="radio_1:<?= $grupa['id'] ?>" <?if(($edit)&&($dat["type_lesson"]==1)) echo "checked";?>>Практичне</label>
                        <label style="display: <?=$radio?>"><input type="radio" name="radio_1:<?= $grupa['id'] ?>" <?if(($edit)&&($dat["type_lesson"]==2)) echo "checked";?>>Лабораторне</label>
                    </td>
                <?php } $tmp_arr=array();?>
            </tr>

            <tr class="row:2" id="tr_begin">
                <td rowspan="4" class="number">5-6</td>
                <?php foreach ($gr as $kurs) foreach ($kurs as $grupa) { ?>
                    <td class="id_group:<?= $grupa['id'] ?>" id="border_lft_rht" colspan="2">
                        <select class="subjects" id="para:5-6" onmouseover="getSubjects(this)" onclick="clickSubject(this)">
                            <option value="-1">Виберіть предмет</option>
                            <?php
                            foreach ($arr_data_timetable as $dat)
                                if (($dat["group_id"] == $grupa['id']) && ($dat["para"] == "5-6") && ($dat["type"] == $_GET['type'])) {
                                    $tmp_arr[] = $dat; ?>
                                    <option value="<?= $dat['subject_id'] ?>" selected><?= $dat['subject'] ?></option>
                                <?php } ?>
                        </select>
                    </td>
                <?php } ?>
            </tr>
            <tr class="row:2">
                <?php foreach ($gr as $kurs) foreach ($kurs as $grupa) { ?>
                    <td id="bd_left"
                        class="teacher:<?= $grupa['id'] ?>"><?php foreach ($tmp_arr as $dat) if (($dat["group_id"] == $grupa['id']) && ($dat["para"] == "5-6") && ($dat["type"] == $_GET["type"]) && ($dat["teacher1_name"] != '')) {
                            echo $dat['teacher1_name'];
                            break;
                        } ?></td>
                    <?php $audit = '';
                    foreach ($tmp_arr as $dat) if (($dat["group_id"] == $grupa['id']) && ($dat["para"] == "5-6") && ($dat["type"] == $_GET["type"]) && ($dat["audience1_number"] != '')) {
                        $audit = $dat['audience1_number'];
                        break;
                    } ?>
                    <td id="bd_right"><input class="audience:<?= $grupa['id'] ?>" id="audiens" type="text"
                                             onkeyup="input_sel=this"
                                             value="<?= $audit ?>" <?php if ($audit == '') echo 'readonly'; ?>/></td>
                <?php } ?>
            </tr>
            <tr class="row:2">
                <?php foreach ($gr as $kurs) foreach ($kurs as $grupa) { ?>
                    <td id="bd_left"
                        class="teacher_pract:<?= $grupa['id'] ?>"><?php foreach ($tmp_arr as $dat) if (($dat["group_id"] == $grupa['id']) && ($dat["para"] == "5-6") && ($dat["type"] == $_GET["type"]) && ($dat["teacher2_name"] != '')) {
                            echo $dat['teacher2_name'];
                            break;
                        } ?></td>
                    <?php $audit = '';
                    foreach ($tmp_arr as $dat) if (($dat["group_id"] == $grupa['id']) && ($dat["para"] == "5-6") && ($dat["type"] == $_GET["type"]) && ($dat["audience2_number"] != '')) {
                        $audit = $dat['audience2_number'];
                        break;
                    } ?>
                    <td id="bd_right"><input class="audience_pract:<?= $grupa['id'] ?>" id="audiens" type="text"
                                             onkeyup="input_sel=this"
                                             value="<?= $audit ?>" <?php if ($audit == '') echo 'readonly'; ?>/></td>
                <?php } ?>
            </tr>
            <tr class="row:2">
                <?php foreach ($gr as $kurs) foreach ($kurs as $grupa) { ?>
                    <td class="radio" id="border_radio" colspan="2">
                        <?php $radio = 'none'; foreach ($tmp_arr as $dat) if (($dat["group_id"] == $grupa['id']) && ($dat["para"] == "5-6") && ($dat["type"] == $_GET["type"])) {
                            $radio = 'label';
                            break;
                        } ?>
                        <label style="display: <?=$radio?>"><input type="radio" name="radio_2:<?= $grupa['id'] ?>" <?if(($edit)&&($dat["type_lesson"]==0)&&($dat["type_lesson"]!=null)) echo "checked";?>>Лекційне</label>
                        <label style="display: <?=$radio?>"><input type="radio" name="radio_2:<?= $grupa['id'] ?>" <?if(($edit)&&($dat["type_lesson"]==1)) echo "checked";?>>Практичне</label>
                        <label style="display: <?=$radio?>"><input type="radio" name="radio_2:<?= $grupa['id'] ?>" <?if(($edit)&&($dat["type_lesson"]==2)) echo "checked";?>>Лабораторне</label>
                    </td>
                <?php } $tmp_arr=array();?>
            </tr>


            <tr class="row:3" id="tr_begin">
                <td rowspan="4" class="number">7-8</td>
                <?php foreach ($gr as $kurs) foreach ($kurs as $grupa) { ?>
                    <td class="id_group:<?= $grupa['id'] ?>" id="border_lft_rht" colspan="2">
                        <select class="subjects" id="para:7-8" onmouseover="getSubjects(this)" onclick="clickSubject(this)">
                            <option value="-1">Виберіть предмет</option>
                            <?php
                            foreach ($arr_data_timetable as $dat)
                                if (($dat["group_id"] == $grupa['id']) && ($dat["para"] == "7-8") && ($dat["type"] == $_GET['type'])) {
                                    $tmp_arr[] = $dat; ?>
                                    <option value="<?= $dat['subject_id'] ?>" selected><?= $dat['subject'] ?></option>
                                <?php } ?>
                        </select>
                    </td>
                <?php } ?>
            </tr>
            <tr class="row:3">
                <?php foreach ($gr as $kurs) foreach ($kurs as $grupa) { ?>
                    <td id="bd_left"
                        class="teacher:<?= $grupa['id'] ?>"><?php foreach ($tmp_arr as $dat) if (($dat["group_id"] == $grupa['id']) && ($dat["para"] == "7-8") && ($dat["type"] == $_GET["type"]) && ($dat["teacher1_name"] != '')) {
                            echo $dat['teacher1_name'];
                            break;
                        } ?></td>
                    <?php $audit = '';
                    foreach ($tmp_arr as $dat) if (($dat["group_id"] == $grupa['id']) && ($dat["para"] == "7-8") && ($dat["type"] == $_GET["type"]) && ($dat["audience1_number"] != '')) {
                        $audit = $dat['audience1_number'];
                        break;
                    } ?>
                    <td id="bd_right"><input class="audience:<?= $grupa['id'] ?>" id="audiens" type="text"
                                             onkeyup="input_sel=this"
                                             value="<?= $audit ?>" <?php if ($audit == '') echo 'readonly'; ?>/></td>
                <?php } ?>
            </tr>
            <tr class="row:3">
                <?php foreach ($gr as $kurs) foreach ($kurs as $grupa) { ?>
                    <td id="bd_left"
                        class="teacher_pract:<?= $grupa['id'] ?>"><?php foreach ($tmp_arr as $dat) if (($dat["group_id"] == $grupa['id']) && ($dat["para"] == "7-8") && ($dat["type"] == $_GET["type"]) && ($dat["teacher2_name"] != '')) {
                            echo $dat['teacher2_name'];
                            break;
                        } ?></td>
                    <?php $audit = '';
                    foreach ($tmp_arr as $dat) if (($dat["group_id"] == $grupa['id']) && ($dat["para"] == "7-8") && ($dat["type"] == $_GET["type"]) && ($dat["audience2_number"] != '')) {
                        $audit = $dat['audience2_number'];
                        break;
                    } ?>
                    <td id="bd_right"><input class="audience_pract:<?= $grupa['id'] ?>" id="audiens" type="text"
                                             onkeyup="input_sel=this"
                                             value="<?= $audit ?>" <?php if ($audit == '') echo 'readonly'; ?>/></td>
                <?php } ?>
            </tr>
            <tr class="row:3">
                <?php foreach ($gr as $kurs) foreach ($kurs as $grupa) { ?>
                    <td class="radio" id="border_radio" colspan="2">
                        <?php $radio = 'none'; foreach ($tmp_arr as $dat) if (($dat["group_id"] == $grupa['id']) && ($dat["para"] == "7-8") && ($dat["type"] == $_GET["type"])) {
                            $radio = 'label';
                            break;
                        } ?>
                        <label style="display: <?=$radio?>"><input type="radio" name="radio_3:<?= $grupa['id'] ?>" <?if(($edit)&&($dat["type_lesson"]==0)&&($dat["type_lesson"]!=null)) echo "checked";?>>Лекційне</label>
                        <label style="display: <?=$radio?>"><input type="radio" name="radio_3:<?= $grupa['id'] ?>" <?if(($edit)&&($dat["type_lesson"]==1)) echo "checked";?>>Практичне</label>
                        <label style="display: <?=$radio?>"><input type="radio" name="radio_3:<?= $grupa['id'] ?>" <?if(($edit)&&($dat["type_lesson"]==2)) echo "checked";?>>Лабораторне</label>
                    </td>
                <?php } $tmp_arr=array();?>
            </tr>
        </table>
            <script>
                paintActualCells();
                input_sel = document.getElementsByClassName("row:0")[1].getElementsByTagName("input")[0];
                $("input[type=text]").autocomplete({
                    serviceUrl: auditor, // Страница для обработки запросов автозаполнения
                    minChars: 1, // Минимальная длина запроса для срабатывания автозаполнения
                    delimiter: /(,|;)\s*/, // Разделитель для нескольких запросов, символ или регулярное выражение
                    maxHeight: 30, // Максимальная высота списка подсказок, в пикселях
                    width: 50, // Ширина списка
                    zIndex: 9999, // z-index списка
                    deferRequestBy: 0, // Задержка запроса (мсек), на случай, если мы не хотим слать миллион запросов, пока пользователь печатает. Я обычно ставлю 300.
                    onSelect: function (data, value) {
                    }, // Callback функция, срабатывающая на выбор одного из предложенных вариантов,
                    lookup: auditor // Список вариантов для локального автозаполнения
                });

                jQuery(document).ready(function ($) {
                    var $btns = $('#buttons_table'),
                        $pos = parseInt($btns.css("margin-left").replace("px", ""));

                    var $viewport = $(window);
                    $viewport.scroll(function () {
                        $btns.css({
                            marginLeft: $pos+$(this).scrollLeft()
                        });
                    });
                });
            </script>
        <?php } ?>
    </div>
</body>
</html>
