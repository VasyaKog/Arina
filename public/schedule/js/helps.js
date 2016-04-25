/**
 * Created by kostia on 14.06.15.
 */
function show_help_teacher(state){
    paintHelpCellsTeachers();
    document.getElementById('help_block').style.display = state;
    document.getElementById('teacher_block').style.display = state;
    document.getElementById('audience_block').style.display = 'none';
    document.getElementById('subject_block').style.display = 'none';
    document.getElementById('wrap').style.display = state;
}

function show_help_audience(state){
    paintHelpCellsAudience();
    document.getElementById('help_block').style.display = state;
    document.getElementById('teacher_block').style.display = 'none';
    document.getElementById('audience_block').style.display = state;
    document.getElementById('subject_block').style.display = 'none';
    document.getElementById('wrap').style.display = state;
}

function show_help_subjects(state){
    getHelpSubject();
    document.getElementById('help_block').style.display = state;
    document.getElementById('teacher_block').style.display = 'none';
    document.getElementById('audience_block').style.display = 'none';
    document.getElementById('subject_block').style.display = state;
    document.getElementById('wrap').style.display = state;
}

function hideHelp(state){
    document.getElementById('help_block').style.display = state;
    document.getElementById('teacher_block').style.display = state;
    document.getElementById('audience_block').style.display = state;
    document.getElementById('wrap').style.display = state;
}

function paintHelpCellsTeachers(){
    var tab_teach_numerator = document.getElementById('teacher_numerator');
    var tab_teach_denumerator = document.getElementById('teacher_denumerator');

    var data_numerator = document.getElementById('numerator');
    var data_denumerator = document.getElementById('denumerator');

    for (var i=2; i<tab_teach_numerator.getElementsByTagName('tr').length; i++) {
        var tab_teach = tab_teach_numerator.getElementsByTagName('tr')[i];
        var tab2_teach = tab_teach_denumerator.getElementsByTagName('tr')[i];
        for (var j = 0; j < 20; j++) {
            var teacher1 = data_numerator.getElementsByClassName("row:" + j)[1].getElementsByTagName("td");
            var teacher2 = data_numerator.getElementsByClassName("row:" + j)[2].getElementsByTagName("td");

            var denum_teacher1 = data_denumerator.getElementsByClassName("row:" + j)[1].getElementsByTagName("td");
            var denum_teacher2 = data_denumerator.getElementsByClassName("row:" + j)[2].getElementsByTagName("td");

            var perevirka_num = false;
            var perevirka_denum = false;
            for (var z = 0; z < data_numerator.getElementsByClassName("row:" + j)[0].getElementsByTagName("select").length; z++) {
                if(tab_teach.getElementsByClassName("teacherName")[0].innerHTML==teacher1[z*2].innerHTML) perevirka_num=true;
                if(tab_teach.getElementsByClassName("teacherName")[0].innerHTML==teacher2[z*2].innerHTML) perevirka_num=true;
                if(tab2_teach.getElementsByClassName("teacherName")[0].innerHTML==denum_teacher1[z*2].innerHTML) perevirka_denum=true;
                if(tab2_teach.getElementsByClassName("teacherName")[0].innerHTML==denum_teacher2[z*2].innerHTML) perevirka_denum=true;
            }
            if (perevirka_num) tab_teach.getElementsByClassName('td:'+j)[0].style.backgroundColor = "#E76060";
            else tab_teach.getElementsByClassName('td:'+j)[0].style.backgroundColor = "#F9F9F9";

            if (perevirka_denum) tab2_teach.getElementsByClassName('td:'+j)[0].style.backgroundColor = "#E76060";
            else tab2_teach.getElementsByClassName('td:'+j)[0].style.backgroundColor = "#F9F9F9";
        }
    }
}

function paintHelpCellsAudience(){
    var tab_audience_numerator = document.getElementById('audience_numerator');
    var tab_audience_denumerator = document.getElementById('audience_denumerator');

    var data_numerator = document.getElementById('numerator');
    var data_denumerator = document.getElementById('denumerator');

    for (var i=2; i<tab_audience_numerator.getElementsByTagName('tr').length; i++) {
        var tab_audience = tab_audience_numerator.getElementsByTagName('tr')[i];
        var tab2_audience = tab_audience_denumerator.getElementsByTagName('tr')[i];
        for (var j = 0; j < 20; j++) {
            var audience1 = data_numerator.getElementsByClassName("row:" + j)[1].getElementsByTagName("input");
            var audience2 = data_numerator.getElementsByClassName("row:" + j)[2].getElementsByTagName("input");

            var denum_audience1 = data_denumerator.getElementsByClassName("row:" + j)[1].getElementsByTagName("input");
            var denum_audience2 = data_denumerator.getElementsByClassName("row:" + j)[2].getElementsByTagName("input");

            var perevirka_num = false;
            var perevirka_denum = false;
            for (var z = 0; z < data_numerator.getElementsByClassName("row:" + j)[0].getElementsByTagName("select").length; z++) {
                if(tab_audience.getElementsByClassName("audienceNumber")[0].innerHTML==audience1[z].value) perevirka_num=true;
                if(tab_audience.getElementsByClassName("audienceNumber")[0].innerHTML==audience2[z].value) perevirka_num=true;
                if(tab2_audience.getElementsByClassName("audienceNumber")[0].innerHTML==denum_audience1[z].value) perevirka_denum=true;
                if(tab2_audience.getElementsByClassName("audienceNumber")[0].innerHTML==denum_audience2[z].value) perevirka_denum=true;
            }
            if (perevirka_num) tab_audience.getElementsByClassName('td:'+j)[0].style.backgroundColor = "#E76060";
            else tab_audience.getElementsByClassName('td:'+j)[0].style.backgroundColor = "#F9F9F9";

            if (perevirka_denum) tab2_audience.getElementsByClassName('td:'+j)[0].style.backgroundColor = "#E76060";
            else tab2_audience.getElementsByClassName('td:'+j)[0].style.backgroundColor = "#F9F9F9";
        }
    }
}

function getHelpSubject(){
    var table = document.getElementById('subject_block').getElementsByTagName('table')[0];
    var head = '<tr><th>Група</th><th>Предмет</th><th>Кількість лекцій</th><th>Кількість практичних</th></tr>';
    var rows = '';
    var group_prev = '';
    var count = 0;
    predmets.forEach(function (dat){
        if ((dat['count_first']!=0)||(dat['count_dual']!=0)){
            if(group_prev!=dat['group'])
            rows+='<tr><td style="background-color: #E76060; padding: 0px 5px 0px 5px;">'+dat['group']+'</td>'+'<td style="background-color: #E76060">'+dat['subject_title']+'</td>'+'<td style="background-color: #E76060" class="num">'+dat['count_first']+'</td>'+'<td style="background-color: #E76060" class="num">'+dat['count_dual']+'</td></tr>';
            else
                rows+='<tr><td style="padding: 0px 5px 0px 5px;">'+dat['group']+'</td>'+'<td>'+dat['subject_title']+'</td>'+'<td class="num">'+dat['count_first']+'</td>'+'<td class="num">'+dat['count_dual']+'</td></tr>';
            group_prev = dat['group'];
            count++;
        }
    });
    if (count>0) table.innerHTML = head+rows;
    else {table.innerHTML = '<h3 class="table_type">Вітаю! Вільних годин немає, отже всі пари заповнені.</h3>'; table.style.border='none';}
}