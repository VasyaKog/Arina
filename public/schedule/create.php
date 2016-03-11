<?php
@session_start();
if (count($_SESSION)<=1) header("Location:../index.php");
if ((!isset($_GET['id']))||(!isset($_GET['sem']))) header("Location:../index.php");
require_once "classes/func.php";
$func = new GlobalFunction($_GET['id']);
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <!--<link rel="stylesheet" type="text/css" href="/assets/css/bootstrap.css">-->
    <link rel="stylesheet" type="text/css" href="css/style.css">
    <script type="text/javascript" src="js/jquery.js"></script>
    <script type="text/javascript" src="js/jquery.autocomplete.js"></script>
    <script type="text/javascript" src="js/function.js"></script>
    <script type="text/javascript" src="js/save_data.js"></script>
    <script type="text/javascript" src="js/helps.js"></script>
    <title>Складання розкладу</title>
</head>
<body>
<div class="wrapper">
<?php if ($_GET['sem'] == 'fill') { ?> <h1>Осінній семестр</h1>
    <?php $gr = $func->createGroups(0);
    $subj = $func->createSubject(0);
} elseif ($_GET['sem'] == 'spring') {
    ?> <h1>Весняний семестр</h1>
    <?php  $gr = $func->createGroups(1);
    $subj = $func->createSubject(1);
}
$table_width = 0;
foreach ($gr as $kurs)
    foreach ($kurs as $grupa) $table_width++;
$table_width = $table_width * 307;
$teachers = $func->getTeachers($subj);
$audience = $func->getAudience();
$arr_audience = $func->getFullAudiences();
$arr_groups=array();

$arr_data_timetable = $func->getTimetable($_GET["id"], $_GET["sem"]);
?>
<script>var year =<?=json_encode($_GET['id'])?>;
    var sem =<?=json_encode($_GET['sem'])?>;</script>
<div id="button_save" onclick="save_timetable()">Зберегти</div>
<div id="buttons_table">
    <div id="button_numerator" onclick="hoverNumerator(this)" style="background-color: #CB5151"
         onmouseover="if(this.style.backgroundColor=='rgb(203, 81, 81)') this.style.backgroundColor='#ee5700'; else this.style.backgroundColor='#229435' "
         onmouseout="if(this.style.backgroundColor=='rgb(238, 87, 0)') this.style.backgroundColor='#CB5151'; else this.style.backgroundColor='green'">
        Сховати чисельник
    </div>
    <div id="button_denumerator" onclick="hoverDenumerator(this)" style="background-color: #CB5151"
         onmouseover="if(this.style.backgroundColor=='rgb(203, 81, 81)') this.style.backgroundColor='#ee5700'; else this.style.backgroundColor='#229435' "
         onmouseout="if(this.style.backgroundColor=='rgb(238, 87, 0)') this.style.backgroundColor='#CB5151'; else this.style.backgroundColor='green'">
        Сховати знаменник
    </div>
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
    <button class="button_help"  onclick="show_help_teacher('block')">Інформація про викладачів</button>
    <button class="button_help" onclick="show_help_audience('block')">Інформація про аудиторії</button>

    <button id="button_help_hours" onclick="show_help_subjects('block')">Інформація про не використані години</button>
    <button id="clear" onclick="clear_schedule()">Очистити</button>
</div>

<h2 class="type_table">Чисельник</h2>

<div id="header" style="width: <?= $table_width + 65; ?>px;"></div>
<table class="rozklad" id="numerator" style="width: <?= $table_width + 65; ?>px;">
<thead>
<tr>
    <th></th>
    <th class="number"></th>
    <?php
    foreach ($gr as $kurs)
        foreach ($kurs as $grupa) { $arr_groups[] = $grupa;
            ?>
            <th id="id_group:<?= $grupa['id'] ?>" class="course:<?= $grupa['course'] ?>"
                colspan="2"><?= $grupa['title'] ?></th>
        <?php } ?></tr>
</thead>
<script>
    var data = <?=json_encode($gr)?>;
    var predmets = <?=json_encode($subj)?>;
    var auditor =
    <?=json_encode($audience)?>.split(' ');
    var arr_auditor = <?=json_encode($arr_audience)?>;
    var group;
    var para;
    var prev_subject_id = -1;
    var prev_arr_index = '';
    var prev_pract = false;
    var input_sel;
    var prev_input_sel;
    var input_value;
    var arr_groups = <?=json_encode($arr_groups)?>;
</script>
<?php $row = 0;
$tmp_arr = array();
$count_reset = false;
for ($i = 0; $i < 5; $i++): ?>
    <tr class="row:<?= $row ?>" id="tr_begin">
        <?php if ($i == 0) { ?>
            <td rowspan="12">Пн.</td> <?php } ?>
        <?php if ($i == 1) { ?>
            <td rowspan="12">Вт.</td> <?php } ?>
        <?php if ($i == 2) { ?>
            <td rowspan="12">Ср.</td> <?php } ?>
        <?php if ($i == 3) { ?>
            <td rowspan="12">Чт.</td> <?php } ?>
        <?php if ($i == 4) { ?>
            <td rowspan="12">Пт.</td> <?php } ?>

        <td rowspan="3" class="number">1-2</td>
        <?php foreach ($gr as $kurs) foreach ($kurs as $grupa) { ?>
            <td class="id_group:<?= $grupa['id'] ?>" id="border_lft_rht" colspan="2">
                <select class="subjects" id="para:1-2" onmouseover="getSubjects(this)" onclick="clickSubject(this)">
                    <option value="-1">Виберіть предмет</option>
                    <?php
                    foreach ($arr_data_timetable as $dat)
                        if (($dat["group_id"] == $grupa['id']) && ($dat["para"] == "1-2") && ($dat["day"] == ($i + 1)) && ($dat["type"] == 0)) {
                            for ($z = 0; $z < count($subj); $z++) {
                                if (($dat["group_id"] == $subj[$z]["group_id"]) && ($dat["subject_id"] == $subj[$z]["id"]) && ($dat["teacher1_id"] == $subj[$z]["teacher_id"])) {
                                    if ($dat["teacher2_name"] != '') $subj[$z]["count_dual"] = $subj[$z]["count_dual"] - 0.5; else $subj[$z]["count_first"] = $subj[$z]["count_first"] - 0.5;
                                    $count_reset = true;
                                    break;
                                }
                            }
                            $tmp_arr[] = $dat; ?>
                            <option value="<?= $dat['subject_id'] ?>" selected><?= $dat['subject'] ?></option>
                        <?php } ?>
                </select>
            </td>
        <?php } ?>
    </tr>
    <tr class="row:<?= $row ?>">
        <?php foreach ($gr as $kurs) foreach ($kurs as $grupa) { ?>
            <td id="bd_left"
                class="teacher:<?= $grupa['id'] ?>"><?php foreach ($tmp_arr as $dat) if (($dat["group_id"] == $grupa['id']) && ($dat["para"] == "1-2") && ($dat["day"] == ($i + 1)) && ($dat["type"] == 0) && ($dat["teacher1_name"] != '')) {
                    echo $dat['teacher1_name'];
                    break;
                } ?></td>
            <?php $audit = '';
            foreach ($tmp_arr as $dat) if (($dat["group_id"] == $grupa['id']) && ($dat["para"] == "1-2") && ($dat["day"] == ($i + 1)) && ($dat["type"] == 0) && ($dat["audience1_number"] != '')) {
                $audit = $dat['audience1_number'];
                break;
            } ?>
            <td id="bd_right"><input class="audience:<?= $grupa['id'] ?>" id="audiens" type="text"
                                     onkeyup="input_sel=this"
                                     value="<?= $audit ?>" <?php if ($audit == '') echo 'readonly'; ?>/></td>
        <?php } ?>
    </tr>
    <tr class="row:<?= $row ?>">
        <?php foreach ($gr as $kurs) foreach ($kurs as $grupa) { ?>
            <td id="bd_left"
                class="teacher_pract:<?= $grupa['id'] ?>"><?php foreach ($tmp_arr as $dat) if (($dat["group_id"] == $grupa['id']) && ($dat["para"] == "1-2") && ($dat["day"] == ($i + 1)) && ($dat["type"] == 0) && ($dat["teacher2_name"] != '')) {
                    echo $dat['teacher2_name'];
                    break;
                } ?></td>
            <?php $audit = '';
            foreach ($tmp_arr as $dat) if (($dat["group_id"] == $grupa['id']) && ($dat["para"] == "1-2") && ($dat["day"] == ($i + 1)) && ($dat["type"] == 0) && ($dat["audience2_number"] != '')) {
                $audit = $dat['audience2_number'];
                break;
            } ?>
            <td id="bd_right"><input class="audience_pract:<?= $grupa['id'] ?>" id="audiens" type="text"
                                     onkeyup="input_sel=this"
                                     value="<?= $audit ?>" <?php if ($audit == '') echo 'readonly'; ?>/></td>
        <?php } ?>
    </tr>
    <?php $row++;
    $tmp_arr = array(); ?>

    <tr class="row:<?= $row ?>">
        <td rowspan="3" class="number">3-4</td>
        <?php foreach ($gr as $kurs) foreach ($kurs as $grupa) { ?>
            <td class="id_group:<?= $grupa['id'] ?>" id="border_lft_rht" colspan="2">
                <select class="subjects" id="para:3-4" onmouseover="getSubjects(this)" onclick="clickSubject(this)">
                    <option value="-1">Виберіть предмет</option>
                    <?php
                    foreach ($arr_data_timetable as $dat)
                        if (($dat["group_id"] == $grupa['id']) && ($dat["para"] == "3-4") && ($dat["day"] == ($i + 1)) && ($dat["type"] == 0)) {
                            for ($z = 0; $z < count($subj); $z++) {
                                if (($dat["group_id"] == $subj[$z]["group_id"]) && ($dat["subject_id"] == $subj[$z]["id"]) && ($dat["teacher1_id"] == $subj[$z]["teacher_id"])) {
                                    if ($dat["teacher2_name"] != '') $subj[$z]["count_dual"] = $subj[$z]["count_dual"] - 0.5; else $subj[$z]["count_first"] = $subj[$z]["count_first"] - 0.5;
                                    $count_reset = true;
                                    break;
                                }
                            }
                            $tmp_arr[] = $dat; ?>
                            <option value="<?= $dat['subject_id'] ?>" selected><?= $dat['subject'] ?></option>
                        <?php } ?>
                </select>
            </td>
        <?php } ?>
    </tr>
    <tr class="row:<?= $row ?>">
        <?php foreach ($gr as $kurs) foreach ($kurs as $grupa) { ?>
            <td id="bd_left"
                class="teacher:<?= $grupa['id'] ?>"><?php foreach ($tmp_arr as $dat) if (($dat["group_id"] == $grupa['id']) && ($dat["para"] == "3-4") && ($dat["day"] == ($i + 1)) && ($dat["type"] == 0) && ($dat["teacher1_name"] != '')) {
                    echo $dat['teacher1_name'];
                    break;
                } ?></td>
            <?php $audit = '';
            foreach ($tmp_arr as $dat) if (($dat["group_id"] == $grupa['id']) && ($dat["para"] == "3-4") && ($dat["day"] == ($i + 1)) && ($dat["type"] == 0) && ($dat["audience1_number"] != '')) {
                $audit = $dat['audience1_number'];
                break;
            } ?>
            <td id="bd_right"><input class="audience:<?= $grupa['id'] ?>" id="audiens" type="text"
                                     onkeyup="input_sel=this"
                                     value="<?= $audit ?>" <?php if ($audit == '') echo 'readonly'; ?>/></td>
        <?php } ?>
    </tr>
    <tr class="row:<?= $row ?>">
        <?php foreach ($gr as $kurs) foreach ($kurs as $grupa) { ?>
            <td id="bd_left"
                class="teacher_pract:<?= $grupa['id'] ?>"><?php foreach ($tmp_arr as $dat) if (($dat["group_id"] == $grupa['id']) && ($dat["para"] == "3-4") && ($dat["day"] == ($i + 1)) && ($dat["type"] == 0) && ($dat["teacher2_name"] != '')) {
                    echo $dat['teacher2_name'];
                    break;
                } ?></td>
            <?php $audit = '';
            foreach ($tmp_arr as $dat) if (($dat["group_id"] == $grupa['id']) && ($dat["para"] == "3-4") && ($dat["day"] == ($i + 1)) && ($dat["type"] == 0) && ($dat["audience2_number"] != '')) {
                $audit = $dat['audience2_number'];
                break;
            } ?>
            <td id="bd_right"><input class="audience_pract:<?= $grupa['id'] ?>" id="audiens" type="text"
                                     onkeyup="input_sel=this"
                                     value="<?= $audit ?>" <?php if ($audit == '') echo 'readonly'; ?>/></td>
        <?php } ?>
    </tr>
    <?php $row++; ?>


    <tr class="row:<?= $row ?>">
        <td rowspan="3" class="number">5-6</td>
        <?php foreach ($gr as $kurs) foreach ($kurs as $grupa) { ?>
            <td class="id_group:<?= $grupa['id'] ?>" id="border_lft_rht" colspan="2">
                <select class="subjects" id="para:5-6" onmouseover="getSubjects(this)" onclick="clickSubject(this)">
                    <option value="-1">Виберіть предмет</option>
                    <?php
                    foreach ($arr_data_timetable as $dat)
                        if (($dat["group_id"] == $grupa['id']) && ($dat["para"] == "5-6") && ($dat["day"] == ($i + 1)) && ($dat["type"] == 0)) {
                            for ($z = 0; $z < count($subj); $z++) {
                                if (($dat["group_id"] == $subj[$z]["group_id"]) && ($dat["subject_id"] == $subj[$z]["id"]) && ($dat["teacher1_id"] == $subj[$z]["teacher_id"])) {
                                    if ($dat["teacher2_name"] != '') $subj[$z]["count_dual"] = $subj[$z]["count_dual"] - 0.5; else $subj[$z]["count_first"] = $subj[$z]["count_first"] - 0.5;
                                    $count_reset = true;
                                    break;
                                }
                            }
                            $tmp_arr[] = $dat; ?>
                            <option value="<?= $dat['subject_id'] ?>" selected><?= $dat['subject'] ?></option>
                        <?php } ?>
                </select>
            </td>
        <?php } ?>
    </tr>
    <tr class="row:<?= $row ?>">
        <?php foreach ($gr as $kurs) foreach ($kurs as $grupa) { ?>
            <td id="bd_left"
                class="teacher:<?= $grupa['id'] ?>"><?php foreach ($tmp_arr as $dat) if (($dat["group_id"] == $grupa['id']) && ($dat["para"] == "5-6") && ($dat["day"] == ($i + 1)) && ($dat["type"] == 0) && ($dat["teacher1_name"] != '')) {
                    echo $dat['teacher1_name'];
                    break;
                } ?></td>
            <?php $audit = '';
            foreach ($tmp_arr as $dat) if (($dat["group_id"] == $grupa['id']) && ($dat["para"] == "5-6") && ($dat["day"] == ($i + 1)) && ($dat["type"] == 0) && ($dat["audience1_number"] != '')) {
                $audit = $dat['audience1_number'];
                break;
            } ?>
            <td id="bd_right"><input class="audience:<?= $grupa['id'] ?>" id="audiens" type="text"
                                     onkeyup="input_sel=this"
                                     value="<?= $audit ?>" <?php if ($audit == '') echo 'readonly'; ?>/></td>
        <?php } ?>
    </tr>
    <tr class="row:<?= $row ?>">
        <?php foreach ($gr as $kurs) foreach ($kurs as $grupa) { ?>
            <td id="bd_left"
                class="teacher_pract:<?= $grupa['id'] ?>"><?php foreach ($tmp_arr as $dat) if (($dat["group_id"] == $grupa['id']) && ($dat["para"] == "5-6") && ($dat["day"] == ($i + 1)) && ($dat["type"] == 0) && ($dat["teacher2_name"] != '')) {
                    echo $dat['teacher2_name'];
                    break;
                } ?></td>
            <?php $audit = '';
            foreach ($tmp_arr as $dat) if (($dat["group_id"] == $grupa['id']) && ($dat["para"] == "5-6") && ($dat["day"] == ($i + 1)) && ($dat["type"] == 0) && ($dat["audience2_number"] != '')) {
                $audit = $dat['audience2_number'];
                break;
            } ?>
            <td id="bd_right"><input class="audience_pract:<?= $grupa['id'] ?>" id="audiens" type="text"
                                     onkeyup="input_sel=this"
                                     value="<?= $audit ?>" <?php if ($audit == '') echo 'readonly'; ?>/></td>
        <?php } ?>
    </tr>
    <?php $row++; ?>

    <tr class="row:<?= $row ?>">
        <td rowspan="3" class="number">7-8</td>
        <?php foreach ($gr as $kurs) foreach ($kurs as $grupa) { ?>
            <td class="id_group:<?= $grupa['id'] ?>" id="border_lft_rht" colspan="2">
                <select class="subjects" id="para:7-8" onmouseover="getSubjects(this)" onclick="clickSubject(this)">
                    <option value="-1">Виберіть предмет</option>
                    <?php foreach ($arr_data_timetable as $dat)
                        if (($dat["group_id"] == $grupa['id']) && ($dat["para"] == "7-8") && ($dat["day"] == ($i + 1)) && ($dat["type"] == 0)) {
                            for ($z = 0; $z < count($subj); $z++) {
                                if (($dat["group_id"] == $subj[$z]["group_id"]) && ($dat["subject_id"] == $subj[$z]["id"]) && ($dat["teacher1_id"] == $subj[$z]["teacher_id"])) {
                                    if ($dat["teacher2_name"] != '') $subj[$z]["count_dual"] = $subj[$z]["count_dual"] - 0.5; else $subj[$z]["count_first"] = $subj[$z]["count_first"] - 0.5;
                                    $count_reset = true;
                                    break;
                                }
                            }
                            $tmp_arr[] = $dat; ?>
                            <option value="<?= $dat['subject_id'] ?>" selected><?= $dat['subject'] ?></option>
                        <?php } ?>
                </select>
            </td>
        <?php } ?>
    </tr>
    <tr class="row:<?= $row ?>">
        <?php foreach ($gr as $kurs) foreach ($kurs as $grupa) { ?>
            <td id="bd_left"
                class="teacher:<?= $grupa['id'] ?>"><?php foreach ($tmp_arr as $dat) if (($dat["group_id"] == $grupa['id']) && ($dat["para"] == "7-8") && ($dat["day"] == ($i + 1)) && ($dat["type"] == 0) && ($dat["teacher1_name"] != '')) {
                    echo $dat['teacher1_name'];
                    break;
                } ?></td>
            <?php $audit = '';
            foreach ($tmp_arr as $dat) if (($dat["group_id"] == $grupa['id']) && ($dat["para"] == "7-8") && ($dat["day"] == ($i + 1)) && ($dat["type"] == 0) && ($dat["audience1_number"] != '')) {
                $audit = $dat['audience1_number'];
                break;
            } ?>
            <td id="bd_right"><input class="audience:<?= $grupa['id'] ?>" id="audiens" type="text"
                                     onkeyup="input_sel=this"
                                     value="<?= $audit ?>" <?php if ($audit == '') echo 'readonly'; ?>/></td>
        <?php } ?>
    </tr>
    <tr class="row:<?= $row ?>">
        <?php foreach ($gr as $kurs) foreach ($kurs as $grupa) { ?>
            <td id="bd_left"
                class="teacher_pract:<?= $grupa['id'] ?>"><?php foreach ($tmp_arr as $dat) if (($dat["group_id"] == $grupa['id']) && ($dat["para"] == "7-8") && ($dat["day"] == ($i + 1)) && ($dat["type"] == 0) && ($dat["teacher2_name"] != '')) {
                    echo $dat['teacher2_name'];
                    break;
                } ?></td>
            <?php $audit = '';
            foreach ($tmp_arr as $dat) if (($dat["group_id"] == $grupa['id']) && ($dat["para"] == "7-8") && ($dat["day"] == ($i + 1)) && ($dat["type"] == 0) && ($dat["audience2_number"] != '')) {
                $audit = $dat['audience2_number'];
                break;
            } ?>
            <td id="bd_right"><input class="audience_pract:<?= $grupa['id'] ?>" id="audiens" type="text"
                                     onkeyup="input_sel=this"
                                     value="<?= $audit ?>" <?php if ($audit == '') echo 'readonly'; ?>/></td>
        <?php } ?>
    </tr>
    <?php $row++; ?>
<?php endfor ?>
</table>


<h2 class="type_table">Знаменник</h2>
<table class="rozklad" id="denumerator" style="width: <?= $table_width + 65; ?>px;">
<tr>
    <th></th>
    <th class="number"></th>
    <?php
    foreach ($gr as $kurs)
        foreach ($kurs as $grupa) {
            ?>

            <th id="id_group:<?= $grupa['id'] ?>" class="course:<?= $grupa['course'] ?>"
                colspan="2"><?= $grupa['title'] ?></th>
        <?php } ?></tr>
<?php $row = 0;
for ($i = 0; $i < 5; $i++):
    ?>
    <tr class="row:<?= $row ?>" id="tr_begin">
        <?php if ($i == 0) { ?>
            <td rowspan="12">Пн.</td> <?php } ?>
        <?php if ($i == 1) { ?>
            <td rowspan="12">Вт.</td> <?php } ?>
        <?php if ($i == 2) { ?>
            <td rowspan="12">Ср.</td> <?php } ?>
        <?php if ($i == 3) { ?>
            <td rowspan="12">Чт.</td> <?php } ?>
        <?php if ($i == 4) { ?>
            <td rowspan="12">Пт.</td> <?php } ?>

        <td rowspan="3" class="number">1-2</td>
        <?php foreach ($gr as $kurs) foreach ($kurs as $grupa) { ?>
            <td class="id_group:<?= $grupa['id'] ?>" id="border_lft_rht" colspan="2">
                <select class="subjects" id="para:1-2" onmouseover="getSubjects(this)" onclick="clickSubject(this)">
                    <option value="-1">Виберіть предмет</option>
                    <?php
                    foreach ($arr_data_timetable as $dat)
                        if (($dat["group_id"] == $grupa['id']) && ($dat["para"] == "1-2") && ($dat["day"] == ($i + 1)) && ($dat["type"] == 1)) {
                            for ($z = 0; $z < count($subj); $z++) {
                                if (($dat["group_id"] == $subj[$z]["group_id"]) && ($dat["subject_id"] == $subj[$z]["id"]) && ($dat["teacher1_id"] == $subj[$z]["teacher_id"])) {
                                    if ($dat["teacher2_name"] != '') $subj[$z]["count_dual"] = $subj[$z]["count_dual"] - 0.5; else $subj[$z]["count_first"] = $subj[$z]["count_first"] - 0.5;
                                    $count_reset = true;
                                    break;
                                }
                            }
                            $tmp_arr[] = $dat; ?>
                            <option value="<?= $dat['subject_id'] ?>" selected><?= $dat['subject'] ?></option>
                        <?php } ?>
                </select>
            </td>
        <?php } ?>
    </tr>
    <tr class="row:<?= $row ?>">
        <?php foreach ($gr as $kurs) foreach ($kurs as $grupa) { ?>
            <td id="bd_left"
                class="teacher:<?= $grupa['id'] ?>"><?php foreach ($tmp_arr as $dat) if (($dat["group_id"] == $grupa['id']) && ($dat["para"] == "1-2") && ($dat["day"] == ($i + 1)) && ($dat["type"] == 1) && ($dat["teacher1_name"] != '')) {
                    echo $dat['teacher1_name'];
                    break;
                } ?></td>
            <?php $audit = '';
            foreach ($tmp_arr as $dat) if (($dat["group_id"] == $grupa['id']) && ($dat["para"] == "1-2") && ($dat["day"] == ($i + 1)) && ($dat["type"] == 1) && ($dat["audience1_number"] != '')) {
                $audit = $dat['audience1_number'];
                break;
            } ?>
            <td id="bd_right"><input class="audience:<?= $grupa['id'] ?>" id="audiens" type="text"
                                     onkeyup="input_sel=this"
                                     value="<?= $audit ?>" <?php if ($audit == '') echo 'readonly'; ?>/></td>
        <?php } ?>
    </tr>
    <tr class="row:<?= $row ?>">
        <?php foreach ($gr as $kurs) foreach ($kurs as $grupa) { ?>
            <td id="bd_left"
                class="teacher_pract:<?= $grupa['id'] ?>"><?php foreach ($tmp_arr as $dat) if (($dat["group_id"] == $grupa['id']) && ($dat["para"] == "1-2") && ($dat["day"] == ($i + 1)) && ($dat["type"] == 1) && ($dat["teacher2_name"] != '')) {
                    echo $dat['teacher2_name'];
                    break;
                } ?></td>
            <?php $audit = '';
            foreach ($tmp_arr as $dat) if (($dat["group_id"] == $grupa['id']) && ($dat["para"] == "1-2") && ($dat["day"] == ($i + 1)) && ($dat["type"] == 1) && ($dat["audience2_number"] != '')) {
                $audit = $dat['audience2_number'];
                break;
            } ?>
            <td id="bd_right"><input class="audience_pract:<?= $grupa['id'] ?>" id="audiens" type="text"
                                     onkeyup="input_sel=this"
                                     value="<?= $audit ?>" <?php if ($audit == '') echo 'readonly'; ?>/></td>
        <?php } ?>
    </tr>
    <?php $row++;
    $tmp_arr = array(); ?>

    <tr class="row:<?= $row ?>">
        <td rowspan="3" class="number">3-4</td>
        <?php foreach ($gr as $kurs) foreach ($kurs as $grupa) { ?>
            <td class="id_group:<?= $grupa['id'] ?>" id="border_lft_rht" colspan="2">
                <select class="subjects" id="para:3-4" onmouseover="getSubjects(this)" onclick="clickSubject(this)">
                    <option value="-1">Виберіть предмет</option>
                    <?php
                    foreach ($arr_data_timetable as $dat)
                        if (($dat["group_id"] == $grupa['id']) && ($dat["para"] == "3-4") && ($dat["day"] == ($i + 1)) && ($dat["type"] == 1)) {
                            for ($z = 0; $z < count($subj); $z++) {
                                if (($dat["group_id"] == $subj[$z]["group_id"]) && ($dat["subject_id"] == $subj[$z]["id"]) && ($dat["teacher1_id"] == $subj[$z]["teacher_id"])) {
                                    if ($dat["teacher2_name"] != '') $subj[$z]["count_dual"] = $subj[$z]["count_dual"] - 0.5; else $subj[$z]["count_first"] = $subj[$z]["count_first"] - 0.5;
                                    $count_reset = true;
                                    break;
                                }
                            }
                            $tmp_arr[] = $dat; ?>
                            <option value="<?= $dat['subject_id'] ?>" selected><?= $dat['subject'] ?></option>
                        <?php } ?>
                </select>
            </td>
        <?php } ?>
    </tr>
    <tr class="row:<?= $row ?>">
        <?php foreach ($gr as $kurs) foreach ($kurs as $grupa) { ?>
            <td id="bd_left"
                class="teacher:<?= $grupa['id'] ?>"><?php foreach ($tmp_arr as $dat) if (($dat["group_id"] == $grupa['id']) && ($dat["para"] == "3-4") && ($dat["day"] == ($i + 1)) && ($dat["type"] == 1) && ($dat["teacher1_name"] != '')) {
                    echo $dat['teacher1_name'];
                    break;
                } ?></td>
            <?php $audit = '';
            foreach ($tmp_arr as $dat) if (($dat["group_id"] == $grupa['id']) && ($dat["para"] == "3-4") && ($dat["day"] == ($i + 1)) && ($dat["type"] == 1) && ($dat["audience1_number"] != '')) {
                $audit = $dat['audience1_number'];
                break;
            } ?>
            <td id="bd_right"><input class="audience:<?= $grupa['id'] ?>" id="audiens" type="text"
                                     onkeyup="input_sel=this"
                                     value="<?= $audit ?>" <?php if ($audit == '') echo 'readonly'; ?>/></td>
        <?php } ?>
    </tr>
    <tr class="row:<?= $row ?>">
        <?php foreach ($gr as $kurs) foreach ($kurs as $grupa) { ?>
            <td id="bd_left"
                class="teacher_pract:<?= $grupa['id'] ?>"><?php foreach ($tmp_arr as $dat) if (($dat["group_id"] == $grupa['id']) && ($dat["para"] == "3-4") && ($dat["day"] == ($i + 1)) && ($dat["type"] == 1) && ($dat["teacher2_name"] != '')) {
                    echo $dat['teacher2_name'];
                    break;
                } ?></td>
            <?php $audit = '';
            foreach ($tmp_arr as $dat) if (($dat["group_id"] == $grupa['id']) && ($dat["para"] == "3-4") && ($dat["day"] == ($i + 1)) && ($dat["type"] == 1) && ($dat["audience2_number"] != '')) {
                $audit = $dat['audience2_number'];
                break;
            } ?>
            <td id="bd_right"><input class="audience_pract:<?= $grupa['id'] ?>" id="audiens" type="text"
                                     onkeyup="input_sel=this"
                                     value="<?= $audit ?>" <?php if ($audit == '') echo 'readonly'; ?>/></td>
        <?php } ?>
    </tr>
    <?php $row++; ?>


    <tr class="row:<?= $row ?>">
        <td rowspan="3" class="number">5-6</td>
        <?php foreach ($gr as $kurs) foreach ($kurs as $grupa): ?>
            <td class="id_group:<?= $grupa['id'] ?>" id="border_lft_rht" colspan="2">
                <select class="subjects" id="para:5-6" onmouseover="getSubjects(this)" onclick="clickSubject(this)">
                    <option value="-1">Виберіть предмет</option>
                    <?php
                    foreach ($arr_data_timetable as $dat)
                        if (($dat["group_id"] == $grupa['id']) && ($dat["para"] == "5-6") && ($dat["day"] == ($i + 1)) && ($dat["type"] == 1)) {
                            for ($z = 0; $z < count($subj); $z++) {
                                if (($dat["group_id"] == $subj[$z]["group_id"]) && ($dat["subject_id"] == $subj[$z]["id"]) && ($dat["teacher1_id"] == $subj[$z]["teacher_id"])) {
                                    if ($dat["teacher2_name"] != '') $subj[$z]["count_dual"] = $subj[$z]["count_dual"] - 0.5; else $subj[$z]["count_first"] = $subj[$z]["count_first"] - 0.5;
                                    $count_reset = true;
                                    break;
                                }
                            }
                            $tmp_arr[] = $dat; ?>
                            <option value="<?= $dat['subject_id'] ?>" selected><?= $dat['subject'] ?></option>
                        <?php } ?>
                </select>
            </td>
        <?php endforeach ?>
    </tr>
    <tr class="row:<?= $row ?>">
        <?php foreach ($gr as $kurs) foreach ($kurs as $grupa) { ?>
            <td id="bd_left"
                class="teacher:<?= $grupa['id'] ?>"><?php foreach ($tmp_arr as $dat) if (($dat["group_id"] == $grupa['id']) && ($dat["para"] == "5-6") && ($dat["day"] == ($i + 1)) && ($dat["type"] == 1) && ($dat["teacher1_name"] != '')) {
                    echo $dat['teacher1_name'];
                    break;
                } ?></td>
            <?php $audit = '';
            foreach ($tmp_arr as $dat) if (($dat["group_id"] == $grupa['id']) && ($dat["para"] == "5-6") && ($dat["day"] == ($i + 1)) && ($dat["type"] == 1) && ($dat["audience1_number"] != '')) {
                $audit = $dat['audience1_number'];
                break;
            } ?>
            <td id="bd_right"><input class="audience:<?= $grupa['id'] ?>" id="audiens" type="text"
                                     onkeyup="input_sel=this"
                                     value="<?= $audit ?>" <?php if ($audit == '') echo 'readonly'; ?>/></td>
        <?php } ?>
    </tr>
    <tr class="row:<?= $row ?>">
        <?php foreach ($gr as $kurs) foreach ($kurs as $grupa) { ?>
            <td id="bd_left"
                class="teacher_pract:<?= $grupa['id'] ?>"><?php foreach ($tmp_arr as $dat) if (($dat["group_id"] == $grupa['id']) && ($dat["para"] == "5-6") && ($dat["day"] == ($i + 1)) && ($dat["type"] == 1) && ($dat["teacher2_name"] != '')) {
                    echo $dat['teacher2_name'];
                    break;
                } ?></td>
            <?php $audit = '';
            foreach ($tmp_arr as $dat) if (($dat["group_id"] == $grupa['id']) && ($dat["para"] == "5-6") && ($dat["day"] == ($i + 1)) && ($dat["type"] == 1) && ($dat["audience2_number"] != '')) {
                $audit = $dat['audience2_number'];
                break;
            } ?>
            <td id="bd_right"><input class="audience_pract:<?= $grupa['id'] ?>" id="audiens" type="text"
                                     onkeyup="input_sel=this"
                                     value="<?= $audit ?>" <?php if ($audit == '') echo 'readonly'; ?>/></td>
        <?php } ?>
    </tr>
    <?php $row++; ?>

    <tr class="row:<?= $row ?>">
        <td rowspan="3" class="number">7-8</td>
        <?php foreach ($gr as $kurs) foreach ($kurs as $grupa) { ?>
            <td class="id_group:<?= $grupa['id'] ?>" id="border_lft_rht" colspan="2">
                <select class="subjects" id="para:7-8" onmouseover="getSubjects(this)" onclick="clickSubject(this)">
                    <option value="-1">Виберіть предмет</option>
                    <?php
                    foreach ($arr_data_timetable as $dat)
                        if (($dat["group_id"] == $grupa['id']) && ($dat["para"] == "7-8") && ($dat["day"] == ($i + 1)) && ($dat["type"] == 1)) {
                            for ($z = 0; $z < count($subj); $z++) {
                                if (($dat["group_id"] == $subj[$z]["group_id"]) && ($dat["subject_id"] == $subj[$z]["id"]) && ($dat["teacher1_id"] == $subj[$z]["teacher_id"])) {
                                    if ($dat["teacher2_name"] != '') $subj[$z]["count_dual"] = $subj[$z]["count_dual"] - 0.5; else $subj[$z]["count_first"] = $subj[$z]["count_first"] - 0.5;
                                    $count_reset = true;
                                    break;
                                }
                            }
                            $tmp_arr[] = $dat; ?>
                            <option value="<?= $dat['subject_id'] ?>" selected><?= $dat['subject'] ?></option>
                        <?php } ?>
                </select>
            </td>
        <?php } ?>
    </tr>
    <tr class="row:<?= $row ?>">
        <?php foreach ($gr as $kurs) foreach ($kurs as $grupa) { ?>
            <td id="bd_left"
                class="teacher:<?= $grupa['id'] ?>"><?php foreach ($tmp_arr as $dat) if (($dat["group_id"] == $grupa['id']) && ($dat["para"] == "7-8") && ($dat["day"] == ($i + 1)) && ($dat["type"] == 1) && ($dat["teacher1_name"] != '')) {
                    echo $dat['teacher1_name'];
                    break;
                } ?></td>
            <?php $audit = '';
            foreach ($tmp_arr as $dat) if (($dat["group_id"] == $grupa['id']) && ($dat["para"] == "7-8") && ($dat["day"] == ($i + 1)) && ($dat["type"] == 1) && ($dat["audience1_number"] != '')) {
                $audit = $dat['audience1_number'];
                break;
            } ?>
            <td id="bd_right"><input class="audience:<?= $grupa['id'] ?>" id="audiens" type="text"
                                     onkeyup="input_sel=this"
                                     value="<?= $audit ?>" <?php if ($audit == '') echo 'readonly'; ?>/></td>
        <?php } ?>
    </tr>
    <tr class="row:<?= $row ?>">
        <?php foreach ($gr as $kurs) foreach ($kurs as $grupa) { ?>
            <td id="bd_left"
                class="teacher_pract:<?= $grupa['id'] ?>"><?php foreach ($tmp_arr as $dat) if (($dat["group_id"] == $grupa['id']) && ($dat["para"] == "7-8") && ($dat["day"] == ($i + 1)) && ($dat["type"] == 1) && ($dat["teacher2_name"] != '')) {
                    echo $dat['teacher2_name'];
                    break;
                } ?></td>
            <?php $audit = '';
            foreach ($tmp_arr as $dat) if (($dat["group_id"] == $grupa['id']) && ($dat["para"] == "7-8") && ($dat["day"] == ($i + 1)) && ($dat["type"] == 1) && ($dat["audience2_number"] != '')) {
                $audit = $dat['audience2_number'];
                break;
            } ?>
            <td id="bd_right"><input class="audience_pract:<?= $grupa['id'] ?>" id="audiens" type="text"
                                     onkeyup="input_sel=this"
                                     value="<?= $audit ?>" <?php if ($audit == '') echo 'readonly'; ?>/></td>
        <?php } ?>
    </tr>
    <?php $row++; ?>
<?php endfor ?>
</table>
</div>

<div onclick="hideHelp('none')" id="wrap"></div>

<div id="help_block">

    <a class="close_button" onclick="hideHelp('none')"></a>

    <div id="teacher_block">
    <center>
    <h3 class="table_type">Чисельник</h3>
    <table class="table_teacher" id="teacher_numerator" style="width: 1275px;">
        <tr>
            <th rowspan="2">Викладач</th>
            <th colspan="4">Понеділок</th>
            <th colspan="4">Вівторок</th>
            <th colspan="4">Середа</th>
            <th colspan="4">Четвер</th>
            <th colspan="4">Пятниця</th>
        </tr>
        <tr>
            <?php for($i=0; $i<5; $i++) { ?>
                <th id="begin_para">1-2</th>
                <th id="para">3-4</th>
                <th id="para">5-6</th>
                <th id="para">7-8</th>
            <?php } ?>
        </tr>

        <?php foreach($teachers as $key => $tmp):?>
        <tr id="row:<?=$key?>">
            <td class="teacherName"><?=$tmp?></td>
            <?php $c=0; for($i=0; $i<5; $i++):?>
                <td class="td:<?=$c?>" id="begin_day" style="width: 47px;"></td> <?php $c++?>
                <td class="td:<?=$c?>" style="width: 47px;"></td> <?php $c++?>
                <td class="td:<?=$c?>" style="width: 47px;"></td> <?php $c++?>
                <td class="td:<?=$c?>" style="width: 47px;"></td> <?php $c++?>
            <?endfor;?>
        </tr>

        <?php endforeach; ?>
    </table>
        <h3 class="table_type">Знаменник</h3>
        <table class="table_teacher" id="teacher_denumerator" style="width: 1275px;">
            <tr>
                <th rowspan="2">Викладач</th>
                <th colspan="4">Понеділок</th>
                <th colspan="4">Вівторок</th>
                <th colspan="4">Середа</th>
                <th colspan="4">Четвер</th>
                <th colspan="4">Пятниця</th>
            </tr>
            <tr>
                <?php for($i=0; $i<5; $i++) { ?>
                    <th id="begin_para">1-2</th>
                    <th id="para">3-4</th>
                    <th id="para">5-6</th>
                    <th id="para">7-8</th>
                <?php } ?>
            </tr>

            <?php foreach($teachers as $key => $tmp):?>
                <tr id="row:<?=$key?>">
                    <td class="teacherName"><?=$tmp?></td>
                    <?php $c=0; for($i=0; $i<5; $i++):?>
                        <td class="td:<?=$c?>" id="begin_day" style="width: 47px;"></td> <?php $c++?>
                        <td class="td:<?=$c?>" style="width: 47px;"></td> <?php $c++?>
                        <td class="td:<?=$c?>" style="width: 47px;"></td> <?php $c++?>
                        <td class="td:<?=$c?>" style="width: 47px;"></td> <?php $c++?>
                    <?endfor;?>
                </tr>

            <?php endforeach; ?>
        </table>
    </center>
    </div>

    <div id="audience_block">
        <center>
            <h3 class="table_type">Чисельник</h3>
            <table class="table_audience" id="audience_numerator">
                <tr>
                    <th rowspan="2">Аудиторія</th>
                    <th colspan="4">Понеділок</th>
                    <th colspan="4">Вівторок</th>
                    <th colspan="4">Середа</th>
                    <th colspan="4">Четвер</th>
                    <th colspan="4">Пятниця</th>
                </tr>
                <tr>
                    <?php for($i=0; $i<5; $i++) { ?>
                        <th id="begin_para">1-2</th>
                        <th id="para">3-4</th>
                        <th id="para">5-6</th>
                        <th id="para">7-8</th>
                    <?php } ?>
                </tr>

                <?php foreach($arr_audience as $tmp):?>
                    <tr id="row:<?=$tmp['id']?>">
                        <td class="audienceNumber"><?=$tmp['number']?></td>
                        <?php $c=0; for($i=0; $i<5; $i++):?>
                            <td class="td:<?=$c?>" id="begin_day"></td> <?php $c++?>
                            <td class="td:<?=$c?>"></td> <?php $c++?>
                            <td class="td:<?=$c?>"></td> <?php $c++?>
                            <td class="td:<?=$c?>"></td> <?php $c++?>
                        <?endfor;?>
                    </tr>

                <?php endforeach; ?>
            </table>
            <h3 class="table_type">Знаменник</h3>
            <table class="table_audience" id="audience_denumerator">
                <tr>
                    <th rowspan="2">Аудиторія</th>
                    <th colspan="4">Понеділок</th>
                    <th colspan="4">Вівторок</th>
                    <th colspan="4">Середа</th>
                    <th colspan="4">Четвер</th>
                    <th colspan="4">Пятниця</th>
                </tr>
                <tr>
                    <?php for($i=0; $i<5; $i++) { ?>
                        <th id="begin_para">1-2</th>
                        <th id="para">3-4</th>
                        <th id="para">5-6</th>
                        <th id="para">7-8</th>
                    <?php } ?>
                </tr>

                <?php foreach($arr_audience as $tmp):?>
                    <tr id="row:<?=$tmp['id']?>">
                        <td class="audienceNumber"><?=$tmp['number']?></td>
                        <?php $c=0; for($i=0; $i<5; $i++):?>
                            <td class="td:<?=$c?>" id="begin_day"></td> <?php $c++?>
                            <td class="td:<?=$c?>"></td> <?php $c++?>
                            <td class="td:<?=$c?>"></td> <?php $c++?>
                            <td class="td:<?=$c?>"></td> <?php $c++?>
                        <?endfor;?>
                    </tr>

                <?php endforeach; ?>
            </table>
        </center>
    </div>

    <div id="subject_block">
        <center><table></table></center>
    </div>

</div>



<?php if ($count_reset) { ?>
    <script>predmets = <?=json_encode($subj)?>;</script><?php } ?>
<script>
    paintCells();
    input_sel = document.getElementsByClassName("row:0")[1].getElementsByTagName("input")[0];
    $("input").autocomplete({
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
        var $table = $('#numerator'),
            $header = $('#header'),
            $thead = $('thead'),
            $btns = $('#buttons_table'),
            $pos = parseInt($btns.css("margin-left").replace("px", ""));
        $header.hide();
        $thead.find('th').each(function () {
            var $newdiv = $('<div />', {
                style: 'width:' + $(this).width() + 'px'

            });
            $newdiv.text($(this).text());
            $header.append($newdiv);
        });

        if ($(this).scrollTop() > 183) $header.show();

        var $viewport = $(window);
        $viewport.scroll(function () {
            if ($(this).scrollTop() > 183) $header.show();
            else $header.hide();
            $header.css({
                left: -$(this).scrollLeft()
            });

            $btns.css({
                marginLeft: $pos+$(this).scrollLeft()
            });
        });
    });

</script>
</body>
</html>