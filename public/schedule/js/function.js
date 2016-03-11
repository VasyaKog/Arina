function clickSubject(elem) {
    var id_subject = elem.options[elem.selectedIndex].value;
    var tr;
    var tr2;
    if (elem.parentNode.parentNode.parentNode.parentNode.id == "numerator") {
        tr = document.getElementsByClassName("rozklad")[0].getElementsByClassName(elem.parentNode.parentNode.className);
        tr2 = document.getElementsByClassName("rozklad")[1].getElementsByClassName(elem.parentNode.parentNode.className);
    }
    else {
        tr = document.getElementsByClassName("rozklad")[1].getElementsByClassName(elem.parentNode.parentNode.className);
        tr2 = document.getElementsByClassName("rozklad")[0].getElementsByClassName(elem.parentNode.parentNode.className);
    }

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
        predmets.forEach(function (d) {
            if ((d['id'] == prev_subject_id) && (d['group_id'] == p)) {
                d['count_first'] = d['count_first'] + 0.5;
            }
        });
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
    addCountSubjectWeek();
    if (dual) addDualSubjects(id_subject, name_teacher, group, tr, tr2, elem);
    else if ((first_count >= 1) && (tr2[1].getElementsByClassName("teacher:" + group)[0].innerHTML == '')) {
        var povtor_teacher = false;
        for (i = 0; i < tr2[1].getElementsByTagName("td").length; i = i + 2) if ((tr2[1].getElementsByTagName("td")[i].innerHTML == name_teacher) || (tr2[2].getElementsByTagName("td")[i].innerHTML == name_teacher)) povtor_teacher = true;
        var day = rowDay(elem.parentNode.parentNode.className.replace(/[^0-9]/g, ''));
        for (i = day.start; i <= day.end; i++) {
            var sel;
            if (elem.parentNode.parentNode.parentNode.parentNode.id == "numerator")
                sel = document.getElementsByClassName("rozklad")[1].getElementsByClassName("row:" + i)[0].getElementsByClassName("id_group:" + group)[0].getElementsByClassName("subjects")[0];
            else
                sel = document.getElementsByClassName("rozklad")[0].getElementsByClassName("row:" + i)[0].getElementsByClassName("id_group:" + group)[0].getElementsByClassName("subjects")[0];
            if (sel.options[sel.selectedIndex].value == elem.options[elem.selectedIndex].value) {
                povtor_teacher = true;
                break;
            }
        }


        if (povtor_teacher == false) {
            var sel_original = tr[0].getElementsByClassName("id_group:" + group)[0].getElementsByClassName("subjects")[0];
            var sel = tr2[0].getElementsByClassName("id_group:" + group)[0].getElementsByClassName("subjects")[0];
            if (sel.options.length == 1) addOption(sel, sel_original.options[sel_original.selectedIndex].text, sel_original.options[sel_original.selectedIndex].value, true);
            else
                for (i = 0; i < sel.options.length; i++) if (sel.options[i].value == sel_original.options[sel_original.selectedIndex].value) {
                    sel.selectedIndex = i;
                    break;
                }

            tr2[1].getElementsByClassName("teacher:" + group)[0].innerHTML = name_teacher;
            tr2[1].getElementsByClassName("audience:" + group)[0].readOnly = false;
            paintCell(sel.parentNode, tr2[1].getElementsByClassName("teacher:" + group)[0], tr2[1].getElementsByClassName("audience:" + group)[0].parentNode, tr2[2].getElementsByClassName("teacher_pract:" + group)[0], tr2[2].getElementsByClassName("audience_pract:" + group)[0].parentNode, true);
            subCountSubjectFullWeek(ind, id_subject, false);
        }
        else subCountSubjectWeek(ind, id_subject, false);
    }
    else subCountSubjectWeek(ind, id_subject, false);

}

function getSubjects(elem) {
    if ((elem.parentNode.id.replace(/[^0-9]/g, '') != group) || (elem.id.replace(/[^-0-9]/g, '') != para)) {
        group = elem.parentNode.className.replace(/[^0-9]/g, '');
        para = elem.id.replace(/[^-0-9]/g, '');
        row_id = elem.parentNode.parentNode.className.replace(/[^0-9]/g, '');
        var day = rowDay(row_id);
        var row;
        if (elem.parentNode.parentNode.parentNode.parentNode.id == "numerator")
            row = document.getElementsByClassName("rozklad")[0].getElementsByClassName(elem.parentNode.parentNode.className);
        else
            row = document.getElementsByClassName("rozklad")[1].getElementsByClassName(elem.parentNode.parentNode.className);
        var teacher = row[1].getElementsByTagName("td");
        var teacher2 = row[2].getElementsByTagName("td");

        if (elem.options[elem.selectedIndex].value == -1) {
            prev_subject_id = -1;
            prev_arr_index = '';
            prev_pract = false;
        }
        else {
            prev_subject_id = elem.options[elem.selectedIndex].value;
            if (row[2].getElementsByClassName("teacher_pract:" + group)[0].innerHTML != '') prev_pract = true;
            predmets.forEach(function (dat, i) {
                if ((dat['id'] == prev_subject_id) && (dat['group_id'] == group))
                    prev_arr_index = i;
            });
            //alert(prev_arr_index+' - '+group+' - '+prev_subject_id+' - '+prev_pract);
        }

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

                    for (i = day.start; i <= day.end; i++)
                        if (i != row_id) {
                            var sel;
                            if (elem.parentNode.parentNode.parentNode.parentNode.id == "numerator")
                                sel = document.getElementsByClassName("rozklad")[0].getElementsByClassName("row:" + i)[0].getElementsByClassName("id_group:" + group)[0].getElementsByClassName("subjects")[0];
                            else
                                sel = document.getElementsByClassName("rozklad")[1].getElementsByClassName("row:" + i)[0].getElementsByClassName("id_group:" + group)[0].getElementsByClassName("subjects")[0];
                            if (sel.options[sel.selectedIndex].value == dat['id']) {
                                proverka = false;
                                break;
                            }
                        }

                    if ((dat['count_first'] <= 0) && (dat['count_dual'] <= 0)) proverka = false;
                    if (proverka) addOption(elem, dat['subject_title'], dat['id']);
                }
            });
        }
        else {
            var double_teacher;
            if (elem.parentNode.parentNode.parentNode.parentNode.id == "numerator")
                double_teacher = document.getElementsByClassName("rozklad")[0].getElementsByClassName(elem.parentNode.parentNode.className)[2].getElementsByClassName('teacher_pract:' + group)[0];
            else
                double_teacher = document.getElementsByClassName("rozklad")[1].getElementsByClassName(elem.parentNode.parentNode.className)[2].getElementsByClassName('teacher_pract:' + group)[0];
            var dual = false;
            predmets.forEach(function (dat) {
                if ((dat['id'] == elem.options[elem.selectedIndex].value) && (dat['group_id'] == group) && (dat['dual']) == 1) dual = true;
            });

            if ((dual) && (double_teacher.innerHTML != '')) dual = false;

            if (dual) {
                dual = false;
                var arr_subj;
                if (elem.parentNode.parentNode.parentNode.parentNode.id == "numerator")
                    arr_subj = document.getElementsByClassName("rozklad")[0].getElementsByClassName(elem.parentNode.parentNode.className)[0].getElementsByClassName("subjects");
                else
                    arr_subj = document.getElementsByClassName("rozklad")[1].getElementsByClassName(elem.parentNode.parentNode.className)[0].getElementsByClassName("subjects");
                for (var i = 0; i < arr_subj.length; i++) {
                    if ((arr_subj[i].parentNode.className != "id_group:" + group) && (arr_subj[i].options[arr_subj[i].selectedIndex].value == elem.options[elem.selectedIndex].value)) {
                        dual = true;
                        break;
                    }
                }
            }

            var arr_teach;
            var arr_teach2;
            if (elem.parentNode.parentNode.parentNode.parentNode.id == "numerator") {
                arr_teach = document.getElementsByClassName("rozklad")[0].getElementsByClassName(elem.parentNode.parentNode.className)[1].getElementsByTagName("td");
                arr_teach2 = document.getElementsByClassName("rozklad")[0].getElementsByClassName(elem.parentNode.parentNode.className)[2].getElementsByTagName("td");
            }
            else {
                arr_teach = document.getElementsByClassName("rozklad")[1].getElementsByClassName(elem.parentNode.parentNode.className)[1].getElementsByTagName("td");
                arr_teach2 = document.getElementsByClassName("rozklad")[1].getElementsByClassName(elem.parentNode.parentNode.className)[2].getElementsByTagName("td");
            }
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

                for (i = day.start; i <= day.end; i++)
                    if (i != row_id) {
                        var sel;
                        if (elem.parentNode.parentNode.parentNode.parentNode.id == "numerator")
                            sel = document.getElementsByClassName("rozklad")[0].getElementsByClassName("row:" + i)[0].getElementsByClassName("id_group:" + group)[0].getElementsByClassName("subjects")[0];
                        else
                            sel = document.getElementsByClassName("rozklad")[1].getElementsByClassName("row:" + i)[0].getElementsByClassName("id_group:" + group)[0].getElementsByClassName("subjects")[0];
                        if (sel.options[sel.selectedIndex].value == dat['id']) {
                            add = false;
                            break;
                        }
                        for (j = 1; j < elem.options.length; j++) if (sel.options[sel.selectedIndex].value == elem.options[j].value) elem.options[j] = null;
                    }

                if (dat['group_id'] == group)
                    for (i = 0; i < elem.options.length; i++)
                        if ((elem.options[i].value == dat['id']) && (dat['count_first'] <= 0) && (dat['count_dual'] <= 0) && (elem.selectedIndex != i)) {
                            elem.options[i] = null;
                            break;
                        }
                if ((dat['group_id'] == group) && (dat['count_first'] <= 0) && (dat['count_dual'] <= 0)) add = false;

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

function rowDay(row_id) {
    day_row_start = 0;
    day_row_end = 0;
    for (var i = 0; i < 19; i = i + 4) {
        if ((row_id >= i) && (row_id < i + 4)) {
            day_row_start = i;
            day_row_end = i + 4 - 1;
        }
    }
    var day_row = {};
    day_row.start = day_row_start;
    day_row.end = day_row_end;
    return day_row;
}

function addDualSubjects(id, teacher_name, group, tr, tr2, elem) {
    var index = 0;
    for (var i = 0; i < predmets.length; i++) if ((predmets[i]['id'] == id) && (predmets[i]['group_id'] == group)) {
        index = i;
        break;
    }
    if (predmets[index]['count_first'] > 0) {
       // alert(predmets[index]['count_first'] + ' - ' + predmets[index]['subject_title']);
        //console.log(predmets[index]['count_first'] + ' - ' + predmets[index]['subject_title']);
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

                var arr_subject2 = [tr2[0].getElementsByClassName("id_group:" + group)[0].getElementsByClassName("subjects")[0]];
                var arr_teacher2 = [tr2[1].getElementsByClassName("teacher:" + group)[0]];
                var arr_audience2 = [tr2[1].getElementsByClassName("audience:" + group)[0]];

                for (var i = 0; i < groups_ids.length; i++) {
                    arr_subject.push(tr[0].getElementsByClassName("id_group:" + groups_ids[i])[0].getElementsByClassName("subjects")[0]);
                    arr_teacher.push(tr[1].getElementsByClassName("teacher:" + groups_ids[i])[0]);
                    arr_audience.push(tr[1].getElementsByClassName("audience:" + groups_ids[i])[0]);
                    if (predmets[index]['count_first'] >= 1) {
                        arr_subject2.push(tr2[0].getElementsByClassName("id_group:" + groups_ids[i])[0].getElementsByClassName("subjects")[0]);
                        arr_teacher2.push(tr2[1].getElementsByClassName("teacher:" + groups_ids[i])[0]);
                        arr_audience2.push(tr2[1].getElementsByClassName("audience:" + groups_ids[i])[0]);
                    }
                }

                var perevirka = true;
                for (i = 0; i < arr_subject.length; i++)
                    if (arr_teacher[i].innerHTML != '') perevirka = false;

                var day = rowDay(elem.parentNode.parentNode.className.replace(/[^0-9]/g, ''));
                for (i = 0; i < groups_ids.length; i++) {
                    for (var j = day.start; j <= day.end; j++) {
                        var sel;
                        if (elem.parentNode.parentNode.parentNode.parentNode.id == "numerator")
                            sel = document.getElementsByClassName("rozklad")[0].getElementsByClassName("row:" + j)[0].getElementsByClassName("id_group:" + groups_ids[i])[0].getElementsByClassName("subjects")[0];
                        else
                            sel = document.getElementsByClassName("rozklad")[1].getElementsByClassName("row:" + j)[0].getElementsByClassName("id_group:" + groups_ids[i])[0].getElementsByClassName("subjects")[0];
                        if (sel.options[sel.selectedIndex].value == elem.options[elem.selectedIndex].value) {
                            perevirka = false;
                            break;
                        }
                    }
                }

                if (predmets[index]['count_first'] >= 1) {
                    for (i = 0; i < arr_subject2.length; i++)
                        if (arr_teacher2[i].innerHTML != '') perevirka = false;

                    for (i = 0; i < groups_ids.length + 1; i++) {
                        for (j = day.start; j <= day.end; j++) {
                            var sel;
                            if (i < groups_ids.length) {
                                if (elem.parentNode.parentNode.parentNode.parentNode.id == "numerator")
                                    sel = document.getElementsByClassName("rozklad")[1].getElementsByClassName("row:" + j)[0].getElementsByClassName("id_group:" + groups_ids[i])[0].getElementsByClassName("subjects")[0];
                                else
                                    sel = document.getElementsByClassName("rozklad")[0].getElementsByClassName("row:" + j)[0].getElementsByClassName("id_group:" + groups_ids[i])[0].getElementsByClassName("subjects")[0];
                            }
                            else {
                                if (elem.parentNode.parentNode.parentNode.parentNode.id == "numerator")
                                    sel = document.getElementsByClassName("rozklad")[1].getElementsByClassName("row:" + j)[0].getElementsByClassName("id_group:" + group)[0].getElementsByClassName("subjects")[0];
                                else
                                    sel = document.getElementsByClassName("rozklad")[0].getElementsByClassName("row:" + j)[0].getElementsByClassName("id_group:" + group)[0].getElementsByClassName("subjects")[0];
                            }
                            if (sel.options[sel.selectedIndex].value == elem.options[elem.selectedIndex].value) {
                                perevirka = false;
                                break;
                            }
                        }
                    }
                }

                if (perevirka) {
                    var perevirka_teacher = true;
                    for (i = 0; i < tr2[1].getElementsByTagName("td").length; i = i + 2) if (tr2[1].getElementsByTagName("td")[i].innerHTML == teacher_name) perevirka_teacher = false;

                    if ((predmets[index]['count_first'] < 1) || (perevirka_teacher == false)) {
                        subCountSubjectWeek(index, id, false);
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
                            subCountSubjectWeek(ind, id, false);

                            paintCell(arr_subject[i].parentNode, arr_teacher[i], arr_audience[i].parentNode, tr[2].getElementsByClassName("teacher_pract:" + gr)[0], tr[2].getElementsByClassName("audience_pract:" + gr)[0].parentNode, true);
                        }
                    }
                    else {
                        subCountSubjectFullWeek(index, id, false);
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
                            subCountSubjectFullWeek(ind, id, false);
                            paintCell(arr_subject[i].parentNode, arr_teacher[i], arr_audience[i].parentNode, tr[2].getElementsByClassName("teacher_pract:" + gr)[0], tr[2].getElementsByClassName("audience_pract:" + gr)[0].parentNode, true);
                        }
                        for (i = 0; i < arr_subject2.length; i++) {
                            arr_subject2[i].options.length = 0;
                            for (j = 0; j < elem.options.length; j++) if (elem.selectedIndex == j) addOption(arr_subject2[i], elem.options[j].text, elem.options[j].value, true);
                            else addOption(arr_subject2[i], elem.options[j].text, elem.options[j].value);
                            arr_teacher2[i].innerHTML = teacher_name;
                            arr_audience2[i].readOnly = false;
                            paintCell(arr_subject2[i].parentNode, arr_teacher2[i], arr_audience2[i].parentNode, tr2[2].getElementsByClassName("teacher_pract:" + gr)[0], tr2[2].getElementsByClassName("audience_pract:" + gr)[0].parentNode, true);
                        }

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
            else {
                if ((predmets[index]['count_first'] >= 1) && (tr2[1].getElementsByClassName("teacher:" + group)[0].innerHTML == '')) {

                    var perevirka_teacher = true;
                    for (i = 0; i < tr2[1].getElementsByTagName("td").length; i = i + 2) if (tr2[1].getElementsByTagName("td")[i].innerHTML == teacher_name) perevirka_teacher = false;
                    var day = rowDay(elem.parentNode.parentNode.className.replace(/[^0-9]/g, ''));
                    for (i = day.start; i <= day.end; i++) {
                        var sel;
                        if (elem.parentNode.parentNode.parentNode.parentNode.id == "numerator")
                            sel = document.getElementsByClassName("rozklad")[1].getElementsByClassName("row:" + i)[0].getElementsByClassName("id_group:" + group)[0].getElementsByClassName("subjects")[0];
                        else
                            sel = document.getElementsByClassName("rozklad")[0].getElementsByClassName("row:" + i)[0].getElementsByClassName("id_group:" + group)[0].getElementsByClassName("subjects")[0];
                        if (sel.options[sel.selectedIndex].value == elem.options[elem.selectedIndex].value) {
                            perevirka_teacher = false;
                            break;
                        }
                    }

                    if (perevirka_teacher) {
                        var sel_original = tr[0].getElementsByClassName("id_group:" + group)[0].getElementsByClassName("subjects")[0];
                        var sel = tr2[0].getElementsByClassName("id_group:" + group)[0].getElementsByClassName("subjects")[0];
                        if (sel.options.length == 1) addOption(sel, sel_original.options[sel_original.selectedIndex].text, sel_original.options[sel_original.selectedIndex].value, true);
                        else
                            for (i = 0; i < sel.options.length; i++) if (sel.options[i].value == sel_original.options[sel_original.selectedIndex].value) {
                                sel.selectedIndex = i;
                                break;
                            }

                        tr2[1].getElementsByClassName("teacher:" + group)[0].innerHTML = teacher_name;
                        tr2[1].getElementsByClassName("audience:" + group)[0].readOnly = false;
                        subCountSubjectFullWeek(index, id, false);
                    }
                    else subCountSubjectWeek(index, id, false);
                }
                else subCountSubjectWeek(index, id, false);
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

                var teachers_perevirka = true;
                for (i = 0; i < tr2[1].getElementsByTagName("td").length; i = i + 2) {
                    if (tr2[1].getElementsByTagName("td")[i].innerHTML == teacher_name) teachers_perevirka = false;
                    if (tr2[2].getElementsByTagName("td")[i].innerHTML == teacher_name) teachers_perevirka = false;
                    if (tr2[1].getElementsByTagName("td")[i].innerHTML == teacher_name2) teachers_perevirka = false;
                    if (tr2[2].getElementsByTagName("td")[i].innerHTML == teacher_name2) teachers_perevirka = false;
                }

                var day = rowDay(elem.parentNode.parentNode.className.replace(/[^0-9]/g, ''));
                for (i = day.start; i <= day.end; i++) {
                    var sel;
                    if (elem.parentNode.parentNode.parentNode.parentNode.id == "numerator")
                        sel = document.getElementsByClassName("rozklad")[1].getElementsByClassName("row:" + i)[0].getElementsByClassName("id_group:" + group)[0].getElementsByClassName("subjects")[0];
                    else
                        sel = document.getElementsByClassName("rozklad")[0].getElementsByClassName("row:" + i)[0].getElementsByClassName("id_group:" + group)[0].getElementsByClassName("subjects")[0];
                    if (sel.options[sel.selectedIndex].value == elem.options[elem.selectedIndex].value) {
                        teachers_perevirka = false;
                        break;
                    }
                }

                if ((predmets[index]['count_dual'] >= 1) && (tr2[1].getElementsByClassName("teacher:" + group)[0].innerHTML == '') && (teachers_perevirka == true)) {
                    var sel_original = tr[0].getElementsByClassName("id_group:" + group)[0].getElementsByClassName("subjects")[0];
                    var sel = tr2[0].getElementsByClassName("id_group:" + group)[0].getElementsByClassName("subjects")[0];
                    if (sel.options.length == 1) addOption(sel, sel_original.options[sel_original.selectedIndex].text, sel_original.options[sel_original.selectedIndex].value, true);
                    else
                        for (i = 0; i < sel.options.length; i++) if (sel.options[i].value == sel_original.options[sel_original.selectedIndex].value) {
                            sel.selectedIndex = i;
                            break;
                        }

                    tr2[1].getElementsByClassName("teacher:" + group)[0].innerHTML = teacher_name;
                    tr2[1].getElementsByClassName("audience:" + group)[0].readOnly = false;
                    tr2[2].getElementsByClassName("teacher_pract:" + group)[0].innerHTML = teacher_name2;
                    tr2[2].getElementsByClassName("audience_pract:" + group)[0].readOnly = false;
                    subCountSubjectFullWeek(index, id, true);
                    paintCell(sel.parentNode, tr2[1].getElementsByClassName("teacher:" + group)[0], tr2[1].getElementsByClassName("audience:" + group)[0].parentNode, tr2[2].getElementsByClassName("teacher_pract:" + group)[0], tr2[2].getElementsByClassName("audience_pract:" + group)[0].parentNode, true);
                }
                else subCountSubjectWeek(index, id, true);
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

            var teachers_perevirka = true;
            for (i = 0; i < tr2[1].getElementsByTagName("td").length; i = i + 2) {
                if (tr2[1].getElementsByTagName("td")[i].innerHTML == teacher_name) teachers_perevirka = false;
                if (tr2[2].getElementsByTagName("td")[i].innerHTML == teacher_name) teachers_perevirka = false;
                if (tr2[1].getElementsByTagName("td")[i].innerHTML == teacher_name2) teachers_perevirka = false;
                if (tr2[2].getElementsByTagName("td")[i].innerHTML == teacher_name2) teachers_perevirka = false;
            }

            var day = rowDay(elem.parentNode.parentNode.className.replace(/[^0-9]/g, ''));
            for (i = day.start; i <= day.end; i++) {
                var sel;
                if (elem.parentNode.parentNode.parentNode.parentNode.id == "numerator")
                    sel = document.getElementsByClassName("rozklad")[1].getElementsByClassName("row:" + i)[0].getElementsByClassName("id_group:" + group)[0].getElementsByClassName("subjects")[0];
                else
                    sel = document.getElementsByClassName("rozklad")[0].getElementsByClassName("row:" + i)[0].getElementsByClassName("id_group:" + group)[0].getElementsByClassName("subjects")[0];
                if (sel.options[sel.selectedIndex].value == elem.options[elem.selectedIndex].value) {
                    teachers_perevirka = false;
                    break;
                }
            }

            if ((predmets[index]['count_dual'] >= 1) && (tr2[1].getElementsByClassName("teacher:" + group)[0].innerHTML == '') && (teachers_perevirka)) {
                var sel_original = tr[0].getElementsByClassName("id_group:" + group)[0].getElementsByClassName("subjects")[0];
                var sel = tr2[0].getElementsByClassName("id_group:" + group)[0].getElementsByClassName("subjects")[0];
                if (sel.options.length == 1) addOption(sel, sel_original.options[sel_original.selectedIndex].text, sel_original.options[sel_original.selectedIndex].value, true);
                else
                    for (i = 0; i < sel.options.length; i++) if (sel.options[i].value == sel_original.options[sel_original.selectedIndex].value) {
                        sel.selectedIndex = i;
                        break;
                    }

                tr2[1].getElementsByClassName("teacher:" + group)[0].innerHTML = teacher_name;
                tr2[1].getElementsByClassName("audience:" + group)[0].readOnly = false;
                tr2[2].getElementsByClassName("teacher_pract:" + group)[0].innerHTML = teacher_name2;
                tr2[2].getElementsByClassName("audience_pract:" + group)[0].readOnly = false;
                subCountSubjectFullWeek(index, id, true);
                paintCell(sel.parentNode, tr2[1].getElementsByClassName("teacher:" + group)[0], tr2[1].getElementsByClassName("audience:" + group)[0].parentNode, tr2[2].getElementsByClassName("teacher_pract:" + group)[0], tr2[2].getElementsByClassName("audience_pract:" + group)[0].parentNode, true);
            }
            else subCountSubjectWeek(index, id, true);
        } else {
            alert('Помилка: Викладач ' + teacher_name2 + ' вже зайнятий');
            tr[0].getElementsByClassName("id_group:" + group)[0].getElementsByClassName("subjects")[0].selectedIndex = 0;
            tr[1].getElementsByClassName("teacher:" + group)[0].innerHTML = '';
            tr[1].getElementsByClassName("audience:" + group)[0].readOnly = true;

            paintCell(tr[0].getElementsByClassName("id_group:" + group)[0].getElementsByClassName("subjects")[0].parentNode, tr[1].getElementsByClassName("teacher:" + group)[0], tr[1].getElementsByClassName("audience:" + group)[0].parentNode, tr[2].getElementsByClassName("teacher_pract:" + group)[0], tr[2].getElementsByClassName("audience_pract:" + group)[0].parentNode, false);
        }
    }

}

function addCountSubjectWeek() {
    if (prev_subject_id != -1) {

        if ((predmets[prev_arr_index]['dual'] == 1) && (prev_pract == true)) {
            console.log(predmets[prev_arr_index]['count_dual']);
            predmets[prev_arr_index]['count_dual'] = predmets[prev_arr_index]['count_dual'] + 0.5;
            console.log(predmets[prev_arr_index]['count_dual']);
        }
        else {
            console.log(predmets[prev_arr_index]['count_first']);
            predmets[prev_arr_index]['count_first'] = predmets[prev_arr_index]['count_first'] + 0.5;
            console.log(predmets[prev_arr_index]['count_first']);
        }
    }
}


function subCountSubjectFullWeek(index, subject_id, dual_pract) {
    if (subject_id != -1) {
        if ((predmets[index]['dual'] == 1) && (dual_pract == true)) {
            console.log(predmets[index]['count_dual']);
            predmets[index]['count_dual'] = predmets[index]['count_dual'] - 1;
            console.log(predmets[index]['count_dual']);
        }
        else {
            console.log(predmets[index]['count_first']);
            predmets[index]['count_first'] = predmets[index]['count_first'] - 1;
            console.log(predmets[index]['count_first']);
        }
    }
}

function subCountSubjectWeek(index, subject_id, dual_pract) {
    if (subject_id != -1) {
        if ((predmets[index]['dual'] == 1) && (dual_pract == true)) {
            console.log(predmets[index]['count_dual']);
            predmets[index]['count_dual'] = predmets[index]['count_dual'] - 0.5;
            console.log(predmets[index]['count_dual']);
        }
        else {
            console.log(predmets[index]['count_first'] + ' - ' + predmets[index]['id'] + ' - ' + predmets[index]['subject_title'] + ' - ' + predmets[index]['group']);
            predmets[index]['count_first'] = predmets[index]['count_first'] - 0.5;
            console.log(predmets[index]['count_first']);
        }
    }
}

function hoverNumerator(btn) {
    var head = document.getElementsByClassName("type_table")[0];
    var table = document.getElementsByClassName("rozklad")[0];
    if (btn.style.backgroundColor == 'rgb(238, 87, 0)') {
        btn.style.background = "green";
        head.style.display = 'none';
        table.style.display = 'none';
        btn.innerHTML = 'Показати чисельник';
    }
    else {
        btn.style.backgroundColor = "rgb(238, 87, 0)";
        head.style.display = '';
        table.style.display = '';
        btn.innerHTML = 'Сховати чисельник';
    }
}

function hoverDenumerator(btn) {
    var head = document.getElementsByClassName("type_table")[1];
    var table = document.getElementsByClassName("rozklad")[1];
    if (btn.style.backgroundColor == 'rgb(238, 87, 0)') {
        btn.style.background = "green";
        head.style.display = 'none';
        table.style.display = 'none';
        btn.innerHTML = 'Показати знаменник';
    }
    else {
        btn.style.backgroundColor = "rgb(238, 87, 0)";
        head.style.display = '';
        table.style.display = '';
        btn.innerHTML = 'Сховати знаменник';
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
    }
}

function changeAudience() {
    if (!((prev_input_sel === input_sel) && (input_value === input_sel.value))) {
        var tr, tr2;
        var gr = input_sel.className.replace(/[^0-9]/g, '');
        if (input_sel.parentNode.parentNode.parentNode.parentNode.id == "numerator") {
            tr = document.getElementsByClassName("rozklad")[0].getElementsByClassName(input_sel.parentNode.parentNode.className);
            tr2 = document.getElementsByClassName("rozklad")[1].getElementsByClassName(input_sel.parentNode.parentNode.className);
        }
        else {
            tr = document.getElementsByClassName("rozklad")[1].getElementsByClassName(input_sel.parentNode.parentNode.className);
            tr2 = document.getElementsByClassName("rozklad")[0].getElementsByClassName(input_sel.parentNode.parentNode.className);
        }
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

        var arr2_select = tr2[0].getElementsByTagName("select");
        var arr2_teacher = [];
        var arr2_teacher2 = [];
        var arr2_audience = tr2[1].getElementsByTagName("input");
        var arr2_audience2 = tr2[2].getElementsByTagName("input");

        for (var i = 0; i < tr[1].getElementsByTagName("td").length; i = i + 2) {
            arr_teacher.push(tr[1].getElementsByTagName("td")[i]);
            arr_teacher2.push(tr[2].getElementsByTagName("td")[i]);
            arr2_teacher.push(tr2[1].getElementsByTagName("td")[i]);
            arr2_teacher2.push(tr2[2].getElementsByTagName("td")[i]);
        }


        var table2_add = false;
        for (i = 0; i < arr_select.length; i++) {
            if (arr_select[i].parentNode.className != select.parentNode.className)
                if ((arr_select[i].options[arr_select[i].selectedIndex].value == select.options[select.selectedIndex].value) && (arr_teacher[i].innerHTML == teacher.innerHTML) && (arr_teacher2[i].innerHTML == teacher2.innerHTML)) {
                    arr_audience[i].value = audience.value;
                    arr_audience2[i].value = audience2.value;
                }
            if ((arr2_select[i].options[arr2_select[i].selectedIndex].value == select.options[select.selectedIndex].value) && (arr2_teacher[i].innerHTML == teacher.innerHTML) && (arr2_teacher2[i].innerHTML == teacher2.innerHTML)) {
                arr2_audience[i].value = audience.value;
                arr2_audience2[i].value = audience2.value;
                table2_add = true;
            }
        }

        peintInputs(tr);
        if (table2_add) peintInputs(tr2);

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

function paintCells() {
    var tr = document.getElementsByClassName("rozklad")[0].getElementsByClassName("row:0")[0].getElementsByTagName("td");
    var tr2;
    var groups = [];
    for (var i = 0; i < tr.length; i++) {
        if (tr[i].className.replace(/[^a-z_:]/g, '') == "id_group:") groups.push(tr[i].className.replace(/[^0-9]/g, ''));
    }
    for (i = 0; i < 20; i++) {
        tr = document.getElementsByClassName("rozklad")[0].getElementsByClassName("row:" + i);
        tr2 = document.getElementsByClassName("rozklad")[1].getElementsByClassName("row:" + i);
        groups.forEach(function (d) {
            if (tr[0].getElementsByClassName("id_group:" + d)[0].getElementsByTagName("select")[0].options[tr[0].getElementsByClassName("id_group:" + d)[0].getElementsByTagName("select")[0].selectedIndex].value != -1) {
                tr[1].getElementsByClassName("teacher:" + d)[0].style.backgroundColor = "#51a351";
                tr[1].getElementsByClassName("audience:" + d)[0].parentNode.style.backgroundColor = "#51a351";
                tr[2].getElementsByClassName("teacher_pract:" + d)[0].style.backgroundColor = "#51a351";
                tr[2].getElementsByClassName("audience_pract:" + d)[0].parentNode.style.backgroundColor = "#51a351";
                tr[0].getElementsByClassName("id_group:" + d)[0].style.backgroundColor = "#51a351";
            }
            if (tr2[0].getElementsByClassName("id_group:" + d)[0].getElementsByTagName("select")[0].options[tr2[0].getElementsByClassName("id_group:" + d)[0].getElementsByTagName("select")[0].selectedIndex].value != -1) {
                tr2[1].getElementsByClassName("teacher:" + d)[0].style.backgroundColor = "#51a351";
                tr2[1].getElementsByClassName("audience:" + d)[0].parentNode.style.backgroundColor = "#51a351";
                tr2[2].getElementsByClassName("teacher_pract:" + d)[0].style.backgroundColor = "#51a351";
                tr2[2].getElementsByClassName("audience_pract:" + d)[0].parentNode.style.backgroundColor = "#51a351";
                tr2[0].getElementsByClassName("id_group:" + d)[0].style.backgroundColor = "#51a351";
            }
        });
        peintInputs(tr);
        peintInputs(tr2);
    }
}


function hoverCourse(btn) {
    var table1 = document.getElementsByClassName("rozklad")[0];
    var table2 = document.getElementsByClassName("rozklad")[1];
    var course = btn.id.replace(/[^-0-9]/g, '');
    var group_id, arr_subjects, arr_teacher1, arr_teacher2, audience1, audience2;
    var arr2_subjects, arr2_teacher1, arr2_teacher2, audience1_2, audience2_2, table_width;
    var count = 0;
    var header = document.getElementById("header").getElementsByTagName("div");
    if (btn.style.backgroundColor == 'rgb(238, 87, 0)') {
        count = 0;
        for (var i = 0; i < table1.getElementsByClassName('course:' + course).length; i++) {
            group_id = table1.getElementsByClassName('course:' + course)[i].id.replace(/[^-0-9]/g, '');
            arr_subjects = table1.getElementsByClassName('id_group:' + group_id);
            arr_teacher1 = table1.getElementsByClassName('teacher:' + group_id);
            arr_teacher2 = table1.getElementsByClassName('teacher_pract:' + group_id);
            audience1 = table1.getElementsByClassName('audience:' + group_id);
            audience2 = table1.getElementsByClassName('audience_pract:' + group_id);

            arr2_subjects = table2.getElementsByClassName('id_group:' + group_id);
            arr2_teacher1 = table2.getElementsByClassName('teacher:' + group_id);
            arr2_teacher2 = table2.getElementsByClassName('teacher_pract:' + group_id);
            audience1_2 = table2.getElementsByClassName('audience:' + group_id);
            audience2_2 = table2.getElementsByClassName('audience_pract:' + group_id);
            table1.getElementsByClassName('course:' + course)[i].style.display = 'none';
            table2.getElementsByClassName('course:' + course)[i].style.display = 'none';
            for (var j = 0; j < arr_subjects.length; j++) {
                arr_subjects[j].style.display = 'none';
                arr_teacher1[j].style.display = 'none';
                arr_teacher2[j].style.display = 'none';
                audience1[j].parentNode.style.display = 'none';
                audience2[j].parentNode.style.display = 'none';

                arr2_subjects[j].style.display = 'none';
                arr2_teacher1[j].style.display = 'none';
                arr2_teacher2[j].style.display = 'none';
                audience1_2[j].parentNode.style.display = 'none';
                audience2_2[j].parentNode.style.display = 'none';
            }

            for (var z = 0; z < header.length; z++) {
                if (header[z].innerHTML == table1.getElementsByClassName('course:' + course)[i].innerHTML) header[z].style.display = 'none';
            }
            count++;
        }

        table_width = table1.style.width.replace(/[^-0-9]/g, '') - (count * 307);
        table1.style.width = table_width + 'px';
        table2.style.width = table_width + 'px';
        document.getElementById("header").style.width = table_width + 'px';
        btn.style.background = "green";

        btn.innerHTML = 'Показати ' + course + ' курс';
    }
    else {
        count = 0;
        for (i = 0; i < table1.getElementsByClassName('course:' + course).length; i++) {
            group_id = table1.getElementsByClassName('course:' + course)[i].id.replace(/[^-0-9]/g, '');
            arr_subjects = table1.getElementsByClassName('id_group:' + group_id);
            arr_teacher1 = table1.getElementsByClassName('teacher:' + group_id);
            arr_teacher2 = table1.getElementsByClassName('teacher_pract:' + group_id);
            audience1 = table1.getElementsByClassName('audience:' + group_id);
            audience2 = table1.getElementsByClassName('audience_pract:' + group_id);

            arr2_subjects = table2.getElementsByClassName('id_group:' + group_id);
            arr2_teacher1 = table2.getElementsByClassName('teacher:' + group_id);
            arr2_teacher2 = table2.getElementsByClassName('teacher_pract:' + group_id);
            audience1_2 = table2.getElementsByClassName('audience:' + group_id);
            audience2_2 = table2.getElementsByClassName('audience_pract:' + group_id);
            table1.getElementsByClassName('course:' + course)[i].style.display = '';
            table2.getElementsByClassName('course:' + course)[i].style.display = '';
            for (var j = 0; j < arr_subjects.length; j++) {
                arr_subjects[j].style.display = '';
                arr_teacher1[j].style.display = '';
                arr_teacher2[j].style.display = '';
                audience1[j].parentNode.style.display = '';
                audience2[j].parentNode.style.display = '';

                arr2_subjects[j].style.display = '';
                arr2_teacher1[j].style.display = '';
                arr2_teacher2[j].style.display = '';
                audience1_2[j].parentNode.style.display = '';
                audience2_2[j].parentNode.style.display = '';
            }

            for (var z = 0; z < header.length; z++) {
                if (header[z].innerHTML == table1.getElementsByClassName('course:' + course)[i].innerHTML) header[z].style.display = '';
            }
            count++;
        }

        table_width = parseInt(table1.style.width.replace(/[^-0-9]/g, '')) + (count * 307);
        table1.style.width = table_width + 'px';
        table2.style.width = table_width + 'px';
        document.getElementById("header").style.width = table_width + 'px';
        btn.style.backgroundColor = "rgb(238, 87, 0)";
        btn.innerHTML = 'Сховати ' + course + ' курс';
    }
}

function clear_schedule(){
    if(confirm("Ви дійсно хочете очистити весь розклад?")) {
        var tr = document.getElementsByClassName("rozklad")[0].getElementsByClassName("row:0")[0].getElementsByTagName("td");
        var tr2;
        var groups = [];
        for (var i = 0; i < tr.length; i++) {
            if (tr[i].className.replace(/[^a-z_:]/g, '') == "id_group:") groups.push(tr[i].className.replace(/[^0-9]/g, ''));
        }
        for (i = 0; i < 20; i++) {
            tr = document.getElementsByClassName("rozklad")[0].getElementsByClassName("row:" + i);
            tr2 = document.getElementsByClassName("rozklad")[1].getElementsByClassName("row:" + i);
            groups.forEach(function (d) {
                if (tr[0].getElementsByClassName("id_group:" + d)[0].getElementsByTagName("select")[0].options[tr[0].getElementsByClassName("id_group:" + d)[0].getElementsByTagName("select")[0].selectedIndex].value != -1) {
                    var sel = tr[0].getElementsByClassName("id_group:" + d)[0].getElementsByTagName("select")[0];
                    var teach1 = tr[1].getElementsByClassName("teacher:" + d)[0];
                    var teach2 = tr[2].getElementsByClassName("teacher_pract:" + d)[0];

                    for(var j=0; j<predmets.length; j++){
                        if ((d==predmets[j]['group_id'])&&(sel.options[sel.selectedIndex].value==predmets[j]['id'])&&(teach1.innerHTML==predmets[j]['teacher'])){
                            if((predmets[j]['teacher2']!='')&&(teach2.innerHTML==predmets[j]['teacher2'])) predmets[j]['count_dual'] = predmets[j]['count_dual']+0.5;
                            else predmets[j]['count_first'] = predmets[j]['count_first']+0.5;
                        }
                    }
                    sel.selectedIndex=0;
                    sel.options.length=1;
                    teach1.innerHTML = '';
                    teach2.innerHTML = '';
                    tr[1].getElementsByClassName("teacher:" + d)[0].style.backgroundColor = "#F9F9F9";
                    tr[1].getElementsByClassName("audience:" + d)[0].parentNode.style.backgroundColor = "#F9F9F9";
                    tr[1].getElementsByClassName("audience:" + d)[0].value = '';
                    tr[1].getElementsByClassName("audience:" + d)[0].readOnly = true;
                    tr[1].getElementsByClassName("audience:" + d)[0].style.backgroundColor = "#C3C7C2";
                    tr[2].getElementsByClassName("teacher_pract:" + d)[0].style.backgroundColor = "#F9F9F9";
                    tr[2].getElementsByClassName("audience_pract:" + d)[0].parentNode.style.backgroundColor = "#F9F9F9";
                    tr[2].getElementsByClassName("audience_pract:" + d)[0].value = '';
                    tr[2].getElementsByClassName("audience_pract:" + d)[0].style.backgroundColor = "#C3C7C2";
                    tr[2].getElementsByClassName("audience_pract:" + d)[0].readOnly = true;
                    tr[0].getElementsByClassName("id_group:" + d)[0].style.backgroundColor = "#F9F9F9";
                }
                if (tr2[0].getElementsByClassName("id_group:" + d)[0].getElementsByTagName("select")[0].options[tr2[0].getElementsByClassName("id_group:" + d)[0].getElementsByTagName("select")[0].selectedIndex].value != -1) {
                    sel = tr2[0].getElementsByClassName("id_group:" + d)[0].getElementsByTagName("select")[0];
                    teach1 = tr2[1].getElementsByClassName("teacher:" + d)[0];
                    teach2 = tr2[2].getElementsByClassName("teacher_pract:" + d)[0];

                    for(j=0; j<predmets.length; j++){
                        if ((d==predmets[j]['group_id'])&&(sel.options[sel.selectedIndex].value==predmets[j]['id'])&&(teach1.innerHTML==predmets[j]['teacher'])){
                            if((predmets[j]['teacher2']!='')&&(teach2.innerHTML==predmets[j]['teacher2'])) predmets[j]['count_dual'] = predmets[j]['count_dual']+0.5;
                            else predmets[j]['count_first'] = predmets[j]['count_first']+0.5;
                        }
                    }
                    sel.selectedIndex=0;
                    sel.options.length=1;
                    teach1.innerHTML = '';
                    teach2.innerHTML = '';
                    tr2[1].getElementsByClassName("teacher:" + d)[0].style.backgroundColor = "#F9F9F9";
                    tr2[1].getElementsByClassName("audience:" + d)[0].parentNode.style.backgroundColor = "#F9F9F9";
                    tr2[1].getElementsByClassName("audience:" + d)[0].value = '';
                    tr2[1].getElementsByClassName("audience:" + d)[0].readOnly = true;
                    tr2[1].getElementsByClassName("audience:" + d)[0].style.backgroundColor = "#C3C7C2";
                    tr2[2].getElementsByClassName("teacher_pract:" + d)[0].style.backgroundColor = "#F9F9F9";
                    tr2[2].getElementsByClassName("audience_pract:" + d)[0].parentNode.style.backgroundColor = "#F9F9F9";
                    tr2[2].getElementsByClassName("audience_pract:" + d)[0].value = '';
                    tr2[2].getElementsByClassName("audience_pract:" + d)[0].readOnly = true;
                    tr2[2].getElementsByClassName("audience_pract:" + d)[0].style.backgroundColor = "#C3C7C2";
                    tr2[0].getElementsByClassName("id_group:" + d)[0].style.backgroundColor = "#F9F9F9";
                }
            });
        }
    }
}