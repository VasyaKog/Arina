/**
 * Created by kostia on 21.06.15.
 */
function changeDate(elem){
    var arr_data  = elem.value.split('.');
    var data = new Date(arr_data[2],parseInt(arr_data[1])-1,arr_data[0]);
    var week = data.getDay();
    var select = document.getElementById('day');
    switch (week){
        case 1: select.selectedIndex = 0; break;
        case 2: select.selectedIndex = 1; break;
        case 3: select.selectedIndex = 2; break;
        case 4: select.selectedIndex = 3; break;
        case 5: select.selectedIndex = 4; break;
        default: select.selectedIndex = 0; break;
    }
}

function clickShape(){
    var data = document.getElementById('data').value.split('.');
    var day = document.getElementById('day').options[document.getElementById('day').selectedIndex].value;
    var type = document.getElementById('type').options[document.getElementById('type').selectedIndex].value;
    var sem, id_study_year='';

    if((data[1]>=9)&&(data[1]<=12)){
        sem='fill';
        years.forEach(function(dat){
            if(dat['begin']==data[2]) id_study_year=dat['id'];
        });
    }
    else{
        sem='spring';
        years.forEach(function(dat){
            if(dat['end']==data[2]) id_study_year=dat['id'];
        });
    }

    if (id_study_year!=''){
        $.ajax({
            url: 'actual_select_data.php',
            type: "post",
            data: {id: id_study_year, sem: sem, day: day, type: type, date: data[2]+'-'+data[1]+'-'+data[0]},
            success: function(result){
                if((result['count_timetable']>0)&&(result['count_actual_schedule']>0)) {
                    if (confirm("Фактичний розклад на вибрану дату був створений. Завантажити фактичний - 'OK', Завантажити статичний - 'Скасувати'"))
                        top.location.href='create_actual.php?year='+id_study_year+'&date='+data[0]+'.'+data[1]+'.'+data[2]+'&sem='+sem+'&day='+day+'&type='+type+'&edit='+1;
                    else
                        top.location.href='create_actual.php?year='+id_study_year+'&date='+data[0]+'.'+data[1]+'.'+data[2]+'&sem='+sem+'&day='+day+'&type='+type+'&edit='+0;
                }
                else if(result['count_timetable']>0) top.location.href='create_actual.php?year='+id_study_year+'&date='+data[0]+'.'+data[1]+'.'+data[2]+'&sem='+sem+'&day='+day+'&type='+type+'&edit='+0;
                else alert('Помилка: Статичний розклад не створено або немає данних');
            },
            dataType: "json"
        });
    }
    else alert('Сталась помилка: На вибрану дату немає даних...')
}

function clickSubject(elem) {
    var id_subject = elem.options[elem.selectedIndex].value;
    var tr;
    tr = document.getElementsByClassName("rozklad")[0].getElementsByClassName(elem.parentNode.parentNode.className);

    var name_teacher = '';
    var name_teacher2 = '';

    var teacher = tr[1].getElementsByClassName("teacher:" + group);
    var audience = tr[1].getElementsByClassName("audience:" + group);
    var teacher2 = tr[2].getElementsByClassName("teacher_pract:" + group);
    var audience2 = tr[2].getElementsByClassName("audience_pract:" + group);
    audience[0].value = '';
    audience2[0].value = '';
    paintCell(elem.parentNode, teacher[0], audience[0].parentNode, teacher2[0], audience2[0].parentNode, false);

    var dual = false;
    var first_count = 0;
    var ind = 0;


    predmets.forEach(function (dat, i) {
        if ((dat['group_id'] == group) && (dat['id'] == id_subject)) {
            name_teacher = dat['teacher'];
            if (dat['dual'] == 1) dual = true;
            first_count = dat['count_first'];
            ind = i;
        }
    });

    var potok = [];
    if (teacher[0].innerHTML != '') {
        var teachers = tr[1].getElementsByTagName("td");
        for (var i = 0; i < teachers.length; i = i + 2)
            if ((teacher[0].innerHTML == teachers[i].innerHTML) && (teachers[i].className.replace(/[^0-9]/g, '') != group)) potok.push(teachers[i].className.replace(/[^0-9]/g, ''));
    }

    if (potok.length > 0) potok.forEach(function (p) {
        tr[0].getElementsByClassName("id_group:" + p)[0].getElementsByClassName("subjects")[0].selectedIndex = 0;
        tr[1].getElementsByClassName("teacher:" + p)[0].innerHTML = '';
        tr[1].getElementsByClassName("audience:" + p)[0].readOnly = true;
        tr[1].getElementsByClassName("audience:" + p)[0].value = '';
        paintCell(tr[0].getElementsByClassName("id_group:" + p)[0].getElementsByClassName("subjects")[0].parentNode, tr[1].getElementsByClassName("teacher:" + p)[0], tr[1].getElementsByClassName("audience:" + p)[0].parentNode, tr[2].getElementsByClassName("teacher_pract:" + p)[0], tr[2].getElementsByClassName("audience_pract:" + p)[0].parentNode, false);
    });


    teacher[0].innerHTML = name_teacher;
    audience[0].readOnly = false;
    audience2[0].readOnly = true;
    teacher2[0].innerHTML = name_teacher2;
    if (elem.options[elem.selectedIndex].value == -1) audience[0].readOnly = true;

    if (name_teacher != '') {
        paintCell(elem.parentNode, teacher[0], audience[0].parentNode, teacher2[0], audience2[0].parentNode, true);
    }
    else paintCell(elem.parentNode, teacher[0], audience[0].parentNode, teacher2[0], audience2[0].parentNode, false);

    if (dual) addDualSubjects(id_subject, name_teacher, group, tr, elem);

}

function getSubjects(elem) {
    if ((elem.parentNode.id.replace(/[^0-9]/g, '') != group) || (elem.id.replace(/[^-0-9]/g, '') != para)) {
        group = elem.parentNode.className.replace(/[^0-9]/g, '');
        para = elem.id.replace(/[^-0-9]/g, '');
        row_id = elem.parentNode.parentNode.className.replace(/[^0-9]/g, '');
        var row;
        row = document.getElementsByClassName("rozklad")[0].getElementsByClassName(elem.parentNode.parentNode.className);
        var teacher = row[1].getElementsByTagName("td");
        var teacher2 = row[2].getElementsByTagName("td");
        if (elem.options.length == 1) {
            predmets.forEach(function (dat) {
                if ((dat['group_id'] == group) && (elem.options[elem.selectedIndex].value == -1)) {
                    var proverka = true;
                    for (var i = 0; i < teacher.length; i = i + 2) {
                        if (teacher[i].innerHTML == dat['teacher']) {
                            proverka = false;
                            break;
                        }

                        if (teacher2[i].innerHTML == dat['teacher']) {
                            proverka = false;
                            break;
                        }
                    }

                    for (i = 0; i <= 3; i++)
                        if (i != row_id) {
                            var sel;
                            sel = document.getElementsByClassName("rozklad")[0].getElementsByClassName("row:" + i)[0].getElementsByClassName("id_group:" + group)[0].getElementsByClassName("subjects")[0];
                            if (sel.options[sel.selectedIndex].value == dat['id']) {
                                proverka = false;
                                break;
                            }
                        }

                    if (proverka) addOption(elem, dat['subject_title'], dat['id']);
                }
            });
        }
        else {
            var double_teacher;
            double_teacher = document.getElementsByClassName("rozklad")[0].getElementsByClassName(elem.parentNode.parentNode.className)[2].getElementsByClassName('teacher_pract:' + group)[0];
            var dual = false;
            predmets.forEach(function (dat) {
                if ((dat['id'] == elem.options[elem.selectedIndex].value) && (dat['group_id'] == group) && (dat['dual']) == 1) dual = true;
            });

            if ((dual) && (double_teacher.innerHTML != '')) dual = false;

            if (dual) {
                dual = false;
                var arr_subj;
                arr_subj = document.getElementsByClassName("rozklad")[0].getElementsByClassName(elem.parentNode.parentNode.className)[0].getElementsByClassName("subjects");
                for (var i = 0; i < arr_subj.length; i++) {
                    if ((arr_subj[i].parentNode.className != "id_group:" + group) && (arr_subj[i].options[arr_subj[i].selectedIndex].value == elem.options[elem.selectedIndex].value)) {
                        dual = true;
                        break;
                    }
                }
            }

            var arr_teach;
            var arr_teach2;
            arr_teach = document.getElementsByClassName("rozklad")[0].getElementsByClassName(elem.parentNode.parentNode.className)[1].getElementsByTagName("td");
            arr_teach2 = document.getElementsByClassName("rozklad")[0].getElementsByClassName(elem.parentNode.parentNode.className)[2].getElementsByTagName("td");

            predmets.forEach(function (dat) {
                var add = true;
                if (dat['group_id'] == group) for (var g = 0; g < elem.options.length; g++) if (dat['id'] == elem.options[g].value) add = false;
                for (var i = 0; i < arr_teach.length; i = i + 2) {
                    if (arr_teach[i].className != "teacher:" + group) {
                        if ((dat['group_id'] == group) && (dat['teacher'] == arr_teach[i].innerHTML)) {
                            for (var j = 0; j < elem.options.length; j++) if ((elem.options[j].value == dat['id']) && (dual == false))
                                elem.options[j] = null;
                            add = false;
                        }
                    }

                    if (arr_teach2[i].className != "teacher_pract:" + group) {
                        if ((dat['group_id'] == group) && (dat['teacher'] == arr_teach2[i].innerHTML)) {
                            for (j = 0; j < elem.options.length; j++) if ((elem.options[j].value == dat['id']) && (dual == false))
                                elem.options[j] = null;
                            add = false;
                        }
                    }
                }

                for (i = 0; i <= 3; i++)
                    if (i != row_id) {
                        var sel;
                        sel = document.getElementsByClassName("rozklad")[0].getElementsByClassName("row:" + i)[0].getElementsByClassName("id_group:" + group)[0].getElementsByClassName("subjects")[0];
                        if (sel.options[sel.selectedIndex].value == dat['id']) {
                            add = false;
                            break;
                        }
                        for (j = 1; j < elem.options.length; j++) if (sel.options[sel.selectedIndex].value == elem.options[j].value) elem.options[j] = null;
                    }

                if ((dat['group_id'] == group) && (add)) addOption(elem, dat['subject_title'], dat['id']);
            });
        }
    }
}

function addOption(oListbox, text, value, isDefaultSelected, isSelected) {
    var oOption = document.createElement("option");
    oOption.appendChild(document.createTextNode(text));
    oOption.setAttribute("value", value);

    if (isDefaultSelected) oOption.defaultSelected = true;
    else if (isSelected) oOption.selected = true;

    oListbox.appendChild(oOption);
}


function addDualSubjects(id, teacher_name, group, tr, elem) {
    var index = 0;
    for (var i = 0; i < predmets.length; i++) if ((predmets[i]['id'] == id) && (predmets[i]['group_id'] == group)) {
        index = i;
        break;
    }
    if (predmets[index]['count_first'] > 0) {
        if (confirm("Якщо це лекційне занняття натисніть 'ОК', практичне - 'Скасувати'")) {
            var conf = true;
            for (i = 0; i < predmets.length; i++)  if ((predmets[i]['id'] == id) && (predmets[i]['group_id'] != group)) if (predmets[i]['count_first'] != predmets[index]['count_first']) {
                conf = false;
                break;
            }
            if ((confirm("Викладач має можливість проводити потокові лекції?")) && (conf)) {
                var groups_ids = [];
                predmets.forEach(function (dat) {
                    if (id == dat['id'] && (teacher_name == dat['teacher']) && (group != dat['group_id']))
                        groups_ids.push(dat['group_id']);
                });

                var arr_subject = [];
                var arr_teacher = [];
                var arr_audience = [];

                for (var i = 0; i < groups_ids.length; i++) {
                    arr_subject.push(tr[0].getElementsByClassName("id_group:" + groups_ids[i])[0].getElementsByClassName("subjects")[0]);
                    arr_teacher.push(tr[1].getElementsByClassName("teacher:" + groups_ids[i])[0]);
                    arr_audience.push(tr[1].getElementsByClassName("audience:" + groups_ids[i])[0]);
                }

                var perevirka = true;
                for (i = 0; i < arr_subject.length; i++)
                    if (arr_teacher[i].innerHTML != '') perevirka = false;

                for (i = 0; i < groups_ids.length; i++) {
                    for (var j = 0; j <= 3; j++) {
                        var sel;
                        sel = document.getElementsByClassName("rozklad")[0].getElementsByClassName("row:" + j)[0].getElementsByClassName("id_group:" + groups_ids[i])[0].getElementsByClassName("subjects")[0];
                        if (sel.options[sel.selectedIndex].value == elem.options[elem.selectedIndex].value) {
                            perevirka = false;
                            break;
                        }
                    }
                }

                if (perevirka) {
                    for (i = 0; i < arr_subject.length; i++) {
                        arr_subject[i].options.length = 0;
                        for (var j = 0; j < elem.options.length; j++) if (elem.selectedIndex == j) addOption(arr_subject[i], elem.options[j].text, elem.options[j].value, true);
                        else addOption(arr_subject[i], elem.options[j].text, elem.options[j].value);
                        arr_teacher[i].innerHTML = teacher_name;
                        arr_audience[i].readOnly = false;
                        var gr = arr_teacher[i].className.replace(/[^0-9]/g, '');
                        var ind;
                        for (var g = 0; g < predmets.length; g++) if ((predmets[g]['id'] == id) && (predmets[g]['group_id'] == gr)) {
                            ind = g;
                            break;
                        }
                        paintCell(arr_subject[i].parentNode, arr_teacher[i], arr_audience[i].parentNode, tr[2].getElementsByClassName("teacher_pract:" + gr)[0], tr[2].getElementsByClassName("audience_pract:" + gr)[0].parentNode, true);
                    }
                }
                else {
                    alert('Помилка! Потокову лекцію створити не можливо, у паралельних групах вже є пари');
                    tr[0].getElementsByClassName("id_group:" + group)[0].getElementsByClassName("subjects")[0].selectedIndex = 0;
                    tr[1].getElementsByClassName("teacher:" + group)[0].innerHTML = '';
                    tr[1].getElementsByClassName("audience:" + group)[0].readOnly = true;

                    paintCell(tr[0].getElementsByClassName("id_group:" + group)[0].getElementsByClassName("subjects")[0].parentNode, tr[1].getElementsByClassName("teacher:" + group)[0], tr[1].getElementsByClassName("audience:" + group)[0].parentNode, tr[2].getElementsByClassName("teacher_pract:" + group)[0], tr[2].getElementsByClassName("audience_pract:" + group)[0].parentNode, false);
                }
            }
        } else if (predmets[index]['count_dual'] > 0) {
            var teacher_name2 = '';
            predmets.forEach(function (dat) {
                if ((dat['id'] == id) && (dat['group_id'] == group) && (dat['teacher'] == teacher_name)) teacher_name2 = dat['teacher2'];
            });

            var teacher2_perevirka = true;
            for (i = 0; i < tr[1].getElementsByTagName("td").length; i = i + 2) {
                if (tr[1].getElementsByTagName("td")[i].innerHTML == teacher_name2) teacher2_perevirka = false;
                if (tr[2].getElementsByTagName("td")[i].innerHTML == teacher_name2) teacher2_perevirka = false;
            }
            if (teacher2_perevirka) {
                var teacher1 = tr[1].getElementsByClassName("teacher:" + group)[0];
                var audience1 = tr[1].getElementsByClassName("audience:" + group)[0];
                var teacher2 = tr[2].getElementsByClassName("teacher_pract:" + group)[0];
                var audience2 = tr[2].getElementsByClassName("audience_pract:" + group)[0];
                teacher1.innerHTML = teacher_name;
                teacher2.innerHTML = teacher_name2;
                audience1.readOnly = false;
                audience2.readOnly = false;
                paintCell(tr[0].getElementsByClassName("id_group:" + group)[0].getElementsByClassName("subjects")[0].parentNode, teacher1, audience1.parentNode, teacher2, audience2.parentNode, true);
            } else {
                alert('Помилка: Викладач ' + teacher_name2 + ' вже зайнятий');
                tr[0].getElementsByClassName("id_group:" + group)[0].getElementsByClassName("subjects")[0].selectedIndex = 0;
                tr[1].getElementsByClassName("teacher:" + group)[0].innerHTML = '';
                tr[1].getElementsByClassName("audience:" + group)[0].readOnly = true;

                paintCell(tr[0].getElementsByClassName("id_group:" + group)[0].getElementsByClassName("subjects")[0].parentNode, tr[1].getElementsByClassName("teacher:" + group)[0], tr[1].getElementsByClassName("audience:" + group)[0].parentNode, tr[2].getElementsByClassName("teacher_pract:" + group)[0], tr[2].getElementsByClassName("audience_pract:" + group)[0].parentNode, false);
            }
        } else {
            elem.selectedIndex = 0;
            tr[1].getElementsByClassName("teacher:" + group)[0].innerHTML = '';
            tr[1].getElementsByClassName("audience:" + group)[0].readOnly = true;
            tr[2].getElementsByClassName("teacher_pract:" + group)[0].innerHTML = '';
            tr[2].getElementsByClassName("audience_pract:" + group)[0].readOnly = true;
            paintCell(tr[0].getElementsByClassName("id_group:" + group)[0].getElementsByClassName("subjects")[0].parentNode, tr[1].getElementsByClassName("teacher:" + group)[0], tr[1].getElementsByClassName("audience:" + group)[0].parentNode, tr[2].getElementsByClassName("teacher_pract:" + group)[0], tr[2].getElementsByClassName("audience_pract:" + group)[0].parentNode, false);
        }
    } else if (predmets[index]['count_dual'] > 0) {
        var teacher_name2 = '';
        predmets.forEach(function (dat) {
            if ((dat['id'] == id) && (dat['group_id'] == group) && (dat['teacher'] == teacher_name)) teacher_name2 = dat['teacher2'];
        });

        var teacher2_perevirka = true;
        for (i = 0; i < tr[1].getElementsByTagName("td").length; i = i + 2) {
            if (tr[1].getElementsByTagName("td")[i].innerHTML == teacher_name2) teacher2_perevirka = false;
            if (tr[2].getElementsByTagName("td")[i].innerHTML == teacher_name2) teacher2_perevirka = false;
        }
        if (teacher2_perevirka) {
            var teacher1 = tr[1].getElementsByClassName("teacher:" + group)[0];
            var audience1 = tr[1].getElementsByClassName("audience:" + group)[0];
            var teacher2 = tr[2].getElementsByClassName("teacher_pract:" + group)[0];
            var audience2 = tr[2].getElementsByClassName("audience_pract:" + group)[0];
            teacher1.innerHTML = teacher_name;
            teacher2.innerHTML = teacher_name2;
            audience1.readOnly = false;
            audience2.readOnly = false;
            paintCell(tr[0].getElementsByClassName("id_group:" + group)[0].getElementsByClassName("subjects")[0].parentNode, teacher1, audience1.parentNode, teacher2, audience2.parentNode, true);
        } else {
            alert('Помилка: Викладач ' + teacher_name2 + ' вже зайнятий');
            tr[0].getElementsByClassName("id_group:" + group)[0].getElementsByClassName("subjects")[0].selectedIndex = 0;
            tr[1].getElementsByClassName("teacher:" + group)[0].innerHTML = '';
            tr[1].getElementsByClassName("audience:" + group)[0].readOnly = true;

            paintCell(tr[0].getElementsByClassName("id_group:" + group)[0].getElementsByClassName("subjects")[0].parentNode, tr[1].getElementsByClassName("teacher:" + group)[0], tr[1].getElementsByClassName("audience:" + group)[0].parentNode, tr[2].getElementsByClassName("teacher_pract:" + group)[0], tr[2].getElementsByClassName("audience_pract:" + group)[0].parentNode, false);
        }
    }
}

function hoverCourse(btn) {
    var table = document.getElementsByClassName("rozklad")[0];
    var course = btn.id.replace(/[^-0-9]/g, '');
    var group_id, arr_subjects, arr_teacher1, arr_teacher2, audience1, audience2, table_width;
    var count = 0;
    if (btn.style.backgroundColor == 'rgb(238, 87, 0)') {
        count = 0;
        for (var i = 0; i < table.getElementsByClassName('course:' + course).length; i++) {
            group_id = table.getElementsByClassName('course:' + course)[i].id.replace(/[^-0-9]/g, '');
            arr_subjects = table.getElementsByClassName('id_group:' + group_id);
            arr_teacher1 = table.getElementsByClassName('teacher:' + group_id);
            arr_teacher2 = table.getElementsByClassName('teacher_pract:' + group_id);
            audience1 = table.getElementsByClassName('audience:' + group_id);
            audience2 = table.getElementsByClassName('audience_pract:' + group_id);

            table.getElementsByClassName('course:' + course)[i].style.display = 'none';
            table.getElementsByClassName('course:' + course)[i].style.display = 'none';
            for (var j = 0; j < arr_subjects.length; j++) {
                arr_subjects[j].style.display = 'none';
                arr_teacher1[j].style.display = 'none';
                arr_teacher2[j].style.display = 'none';
                audience1[j].parentNode.style.display = 'none';
                audience2[j].parentNode.style.display = 'none';
                document.getElementsByName('radio_'+j+':'+group_id)[0].parentNode.parentNode.style.display = 'none';
            }
            count++;
        }

        table_width = table.style.width.replace(/[^-0-9]/g, '') - (count * 307);
        table.style.width = table_width + 'px';
        btn.style.background = "green";

        btn.innerHTML = 'Показати ' + course + ' курс';
    }
    else {
        count = 0;
        for (var i = 0; i < table.getElementsByClassName('course:' + course).length; i++) {
            group_id = table.getElementsByClassName('course:' + course)[i].id.replace(/[^-0-9]/g, '');
            arr_subjects = table.getElementsByClassName('id_group:' + group_id);
            arr_teacher1 = table.getElementsByClassName('teacher:' + group_id);
            arr_teacher2 = table.getElementsByClassName('teacher_pract:' + group_id);
            audience1 = table.getElementsByClassName('audience:' + group_id);
            audience2 = table.getElementsByClassName('audience_pract:' + group_id);

            table.getElementsByClassName('course:' + course)[i].style.display = '';
            table.getElementsByClassName('course:' + course)[i].style.display = '';
            for (var j = 0; j < arr_subjects.length; j++) {
                arr_subjects[j].style.display = '';
                arr_teacher1[j].style.display = '';
                arr_teacher2[j].style.display = '';
                audience1[j].parentNode.style.display = '';
                audience2[j].parentNode.style.display = '';
                document.getElementsByName('radio_'+j+':'+group_id)[0].parentNode.parentNode.style.display = '';
            }
            count++;
        }

        table_width = parseInt(table.style.width.replace(/[^-0-9]/g, '')) + (count * 307);
        table.style.width = table_width + 'px';
        btn.style.backgroundColor = "rgb(238, 87, 0)";
        btn.innerHTML = 'Сховати ' + course + ' курс';
    }
}

function paintCell(sel, teach, audience, teach2, audience2, success) {
    if (success) {
        sel.style.backgroundColor = "#51a351";
        teach.style.backgroundColor = "#51a351";
        audience.style.backgroundColor = "#51a351";
        audience.childNodes[0].style.backgroundColor = "#E76060";
        teach2.style.backgroundColor = "#51a351";
        audience2.style.backgroundColor = "#51a351";
        var row_id = sel.parentNode.className.replace(/[^0-9]/g, '');
        var group_id = sel.className.replace(/[^0-9]/g, '');
        var radio = document.getElementsByName("radio_"+row_id+":"+group_id);
        radio[0].parentNode.parentNode.style.backgroundColor = "#51a351";
        for(var i=0; i<3; i++) { radio[i].parentNode.style.display = ""; radio[i].checked=false; }
        if (audience2.childNodes[0].readOnly == false) audience2.childNodes[0].style.backgroundColor = "#E76060";
    }
    else {
        sel.style.backgroundColor = "";
        teach.style.backgroundColor = "#F9F9F9";
        audience.style.backgroundColor = "#F9F9F9";
        audience.childNodes[0].style.backgroundColor = "#C3C7C2";
        teach2.style.backgroundColor = "#F9F9F9";
        audience2.style.backgroundColor = "#F9F9F9";
        audience2.childNodes[0].style.backgroundColor = "#C3C7C2";
        var row_id = sel.parentNode.className.replace(/[^0-9]/g, '');
        var group_id = sel.className.replace(/[^0-9]/g, '');
        var radio = document.getElementsByName("radio_"+row_id+":"+group_id);
        radio[0].parentNode.parentNode.style.backgroundColor = "#F9F9F9";
        for(var i=0; i<3; i++) radio[i].parentNode.style.display = 'none';
    }
}

function changeAudience() {
    if (!((prev_input_sel === input_sel) && (input_value === input_sel.value))) {
        var tr;
        var gr = input_sel.className.replace(/[^0-9]/g, '');
        tr = document.getElementsByClassName("rozklad")[0].getElementsByClassName(input_sel.parentNode.parentNode.className);

        var teacher = tr[1].getElementsByClassName("teacher:" + gr)[0];
        var audience = tr[1].getElementsByClassName("audience:" + gr)[0];
        var teacher2 = tr[2].getElementsByClassName("teacher_pract:" + gr)[0];
        var audience2 = tr[2].getElementsByClassName("audience_pract:" + gr)[0];
        var select = tr[0].getElementsByClassName("id_group:" + gr)[0].getElementsByClassName("subjects")[0];

        var arr_select = tr[0].getElementsByTagName("select");
        var arr_teacher = [];
        var arr_teacher2 = [];
        var arr_audience = tr[1].getElementsByTagName("input");
        var arr_audience2 = tr[2].getElementsByTagName("input");

        for (var i = 0; i < tr[1].getElementsByTagName("td").length; i = i + 2) {
            arr_teacher.push(tr[1].getElementsByTagName("td")[i]);
            arr_teacher2.push(tr[2].getElementsByTagName("td")[i]);
        }

        for (i = 0; i < arr_select.length; i++)
            if (arr_select[i].parentNode.className != select.parentNode.className)
                if ((arr_select[i].options[arr_select[i].selectedIndex].value == select.options[select.selectedIndex].value) && (arr_teacher[i].innerHTML == teacher.innerHTML) && (arr_teacher2[i].innerHTML == teacher2.innerHTML)) {
                    arr_audience[i].value = audience.value;
                    arr_audience2[i].value = audience2.value;
                }

        peintInputs(tr);

        prev_input_sel = input_sel;
        input_value = input_sel.value;
    }
}

function peintInputs(rows) {
    var arr_audience1 = [];
    var arr_audience2 = [];
    var arr_normal = [];
    var arr_sub = [];
    var pochozi = [];
    for (var i = 0; i < rows[1].getElementsByTagName("input").length; i++) {
        if (rows[1].getElementsByTagName("input")[i].readOnly == false) {
            arr_audience1.push(rows[1].getElementsByTagName("input")[i]);
            arr_sub.push(rows[0].getElementsByTagName("select")[i]);
        }
        if (rows[2].getElementsByTagName("input")[i].readOnly == false) arr_audience2.push(rows[2].getElementsByTagName("input")[i]);
    }

    for (i = 0; i < arr_audience1.length; i++) {
        if (arr_audience1[i].value == '') pochozi.push(arr_audience1[i]);
        for (var j = 0; j < arr_audience1.length; j++)
            if ((arr_audience1[i] != arr_audience1[j]) && ((arr_audience1[i].value == arr_audience1[j].value)) && (arr_sub[i].options[arr_sub[i].selectedIndex].value != arr_sub[j].options[arr_sub[j].selectedIndex].value)) {
                pochozi.push(arr_audience1[i]);
                pochozi.push(arr_audience1[j]);
            }
            else {
                arr_normal.push(arr_audience1[i]);
                arr_normal.push(arr_audience1[j]);
            }

        for (j = 0; j < arr_audience2.length; j++) {
            if (arr_audience1[i].value == arr_audience2[j].value) {
                pochozi.push(arr_audience1[i]);
                pochozi.push(arr_audience2[j]);
            }
            else {
                arr_normal.push(arr_audience1[i]);
                arr_normal.push(arr_audience2[j]);
            }
        }
    }

    for (i = 0; i < arr_audience2.length; i++) {
        for (var j = 0; j < arr_audience1.length; j++)
            if (arr_audience2[i].value == arr_audience1[j].value) {
                pochozi.push(arr_audience2[i]);
                pochozi.push(arr_audience1[j]);
            }
            else {
                arr_normal.push(arr_audience2[i]);
                arr_normal.push(arr_audience1[j]);
            }

        for (j = 0; j < arr_audience2.length; j++) {
            if ((arr_audience2[i] != arr_audience2[j]) && (arr_audience2[i].value == arr_audience2[j].value)) {
                pochozi.push(arr_audience2[i]);
                pochozi.push(arr_audience2[j]);
            }
            else {
                arr_normal.push(arr_audience2[i]);
                arr_normal.push(arr_audience2[j]);
            }
        }
    }

    for (i = 0; i < arr_audience1.length; i++) if (arr_audience1[i].value == '') pochozi.push(arr_audience1[i]);

    var arr_res = [];
    for (i = 1; i < arr_normal.length; i++) {
        for (j = i - 1; j >= 0; j--) {
            if (arr_normal[j] == arr_normal[i]) {
                var is_unique = true; // флаг уникальности элемента
                for (var k = 0; k < arr_res.length; k++) {
                    if (arr_res[k] == arr_normal[i]) {
                        is_unique = false;
                        break;
                    }
                }
                if (is_unique) {
                    arr_res.push(arr_normal[i]);
                }
                break;
            }
        }
    }
    arr_normal = arr_res;

    arr_res = [];
    for (i = 1; i < pochozi.length; i++) {
        for (j = i - 1; j >= 0; j--) {
            if (pochozi[j] == pochozi[i]) {
                is_unique = true; // флаг уникальности элемента
                for (k = 0; k < arr_res.length; k++) {
                    if (arr_res[k] == pochozi[i]) {
                        is_unique = false;
                        break;
                    }
                }
                if (is_unique) {
                    arr_res.push(pochozi[i]);
                }
                break;
            }
        }
    }

    pochozi = arr_res;

    for (i = 0; i < arr_normal.length; i++) {
        arr_normal[i].style.backgroundColor = "#51a351";
    }

    for (i = 0; i < pochozi.length; i++) {
        if (pochozi[i].value != "с.зал") pochozi[i].style.backgroundColor = "#E76060";
    }

}

function paintActualCells(){
    var tr = document.getElementsByClassName("rozklad")[0].getElementsByClassName("row:0")[0].getElementsByTagName("td");
    var groups = [];
    for (var i = 0; i < tr.length; i++) {
        if (tr[i].className.replace(/[^a-z_:]/g, '') == "id_group:") groups.push(tr[i].className.replace(/[^0-9]/g, ''));
    }
    for (i = 0; i < 4; i++) {
        tr = document.getElementsByClassName("rozklad")[0].getElementsByClassName("row:" + i);
        groups.forEach(function (d) {
            if (tr[0].getElementsByClassName("id_group:" + d)[0].getElementsByTagName("select")[0].options[tr[0].getElementsByClassName("id_group:" + d)[0].getElementsByTagName("select")[0].selectedIndex].value != -1) {
                tr[1].getElementsByClassName("teacher:" + d)[0].style.backgroundColor = "#51a351";
                tr[1].getElementsByClassName("audience:" + d)[0].parentNode.style.backgroundColor = "#51a351";
                tr[2].getElementsByClassName("teacher_pract:" + d)[0].style.backgroundColor = "#51a351";
                tr[2].getElementsByClassName("audience_pract:" + d)[0].parentNode.style.backgroundColor = "#51a351";
                tr[0].getElementsByClassName("id_group:" + d)[0].style.backgroundColor = "#51a351";
                document.getElementsByName("radio_" + i + ":" + d)[0].parentNode.parentNode.style.backgroundColor = "#51a351";
            }
        });
        peintInputs(tr);
    }

}
