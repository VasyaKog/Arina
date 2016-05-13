/**
 * Created by kostia on 05.06.15.
 */
function save_timetable(){
    var count = checkData();
    var count_error_auditors = checkAudience();
    if ((count!=0)||(count_error_auditors!=0)){
        var text='';
        if((count!=0)&&(count_error_auditors==0)) text = "Увага! Ще є "+count+" пердметів в яких є вільні години! Продовжити збереження?";
        else if((count==0)&&(count_error_auditors!=0)) text = "Увага! Є "+count_error_auditors+" спірних або не заповнених аудиторій! Продовжити збереження?";
        else text = "Увага! Ще є "+count+" пердметів в яких є вільні години та "+count_error_auditors+" спірних або не заповнених аудиторій! Продовжити збереження?";
        if(confirm(text)){
            createArrayTimetable();
        }
        else {
            alert('Збереження було відмінено!');
        }
    }
    else {
        createArrayTimetable();
    }
}

function checkData(){
    var count=0;
    predmets.forEach(function(dat){
        if (dat['count_first']!=0) count++;
        if (dat['count_dual']!=0) count++;
    });

    return count;
}

function checkAudience(){
    var count=0;
    var table_numerator =  document.getElementsByClassName("rozklad")[0];
    var table_denumerator = document.getElementsByClassName("rozklad")[1];
    for (var i=0; i<table_numerator.getElementsByTagName('input').length; i++){
        if(table_numerator.getElementsByTagName('input')[i].style.backgroundColor=="rgb(231, 96, 96)") count++;
        if(table_denumerator.getElementsByTagName('input')[i].style.backgroundColor=="rgb(231, 96, 96)") count++;
    }
    return count;
}

function getDay(row_id){
    var result = null;
    if ((row_id>=0)&&(row_id<4)) result=1;
    if ((row_id>=4)&&(row_id<8)) result=2;
    if ((row_id>=8)&&(row_id<12)) result=3;
    if ((row_id>=12)&&(row_id<16)) result=4;
    if ((row_id>=16)&&(row_id<20)) result=5;
    return result;
}

function createArrayTimetable(){
    var table_numerator =  document.getElementsByClassName("rozklad")[0];
    var table_denumerator = document.getElementsByClassName("rozklad")[1];
    var arr = [];
    for(var i=0; i<20; i++){
        for (var j=0; j<table_numerator.getElementsByClassName("row:"+i)[0].getElementsByTagName("select").length; j++) {
            var tmp_arr=[];
            var sel = table_numerator.getElementsByClassName("row:"+i)[0].getElementsByTagName("select");
            var sel2 = table_denumerator.getElementsByClassName("row:"+i)[0].getElementsByTagName("select");
            var teacher1;
            var teacher2;
            var audience1;
            var audience2;

            if (sel[j].options[sel[j].selectedIndex].value!=-1) {
                tmp_arr=[];
                teacher1 = table_numerator.getElementsByClassName("row:"+i)[1].getElementsByTagName("td");
                teacher2 = table_numerator.getElementsByClassName("row:"+i)[2].getElementsByTagName("td");
                audience1 = table_numerator.getElementsByClassName("row:"+i)[1].getElementsByTagName("input");
                audience2 = table_numerator.getElementsByClassName("row:"+i)[2].getElementsByTagName("input");

                tmp_arr['year'] = year;
                tmp_arr['semester'] = sem;
                tmp_arr['para'] = sel[j].id.replace(/[^-0-9]/g, '');
                tmp_arr['day'] = getDay(i);
                tmp_arr['group_id'] = sel[j].parentNode.className.replace(/[^0-9]/g, '');
                tmp_arr['subject_id'] = sel[j].options[sel[j].selectedIndex].value;
                predmets.forEach(function (dat){
                    if((dat['group_id']==tmp_arr['group_id'])&&(dat['id']==tmp_arr['subject_id'])&&(dat['teacher']==teacher1[j*2].innerHTML)) {
                        if ((teacher1[j*2].innerHTML!='')&&(teacher2[j*2].innerHTML=='')) {
                            tmp_arr['teacher1_id'] = dat['teacher_id'];
                            tmp_arr['teacher2_id'] = null;
                        }
                        else if((teacher1[j*2].innerHTML!='')&&(teacher2[j*2].innerHTML!='')) {
                            tmp_arr['teacher1_id'] = dat['teacher_id'];
                            tmp_arr['teacher2_id'] = dat['teacher2_id'];
                        }
                        else {
                            tmp_arr['teacher1_id'] = null;
                            tmp_arr['teacher2_id'] = null;
                        }
                    }
                });

                tmp_arr['audience1_id']=null;
                tmp_arr['audience2_id']=null;

                arr_auditor.forEach(function (aud){
                    if((aud["number"]==audience1[j].value)&&(audience1[j].value!='')) tmp_arr['audience1_id'] = aud["id"];
                    if((aud["number"]==audience2[j].value)&&(audience2[j].value!='')) tmp_arr['audience2_id'] = aud["id"];
                });

                tmp_arr["type"] = 0;
                arr.push(tmp_arr);
            }

            if (sel2[j].options[sel2[j].selectedIndex].value!=-1) {
                tmp_arr=[];
                teacher1 = table_denumerator.getElementsByClassName("row:"+i)[1].getElementsByTagName("td");
                teacher2 = table_denumerator.getElementsByClassName("row:"+i)[2].getElementsByTagName("td");
                audience1 = table_denumerator.getElementsByClassName("row:"+i)[1].getElementsByTagName("input");
                audience2 = table_denumerator.getElementsByClassName("row:"+i)[2].getElementsByTagName("input");

                tmp_arr['year'] = year;
                tmp_arr['semester'] = sem;
                tmp_arr['para'] = sel2[j].id.replace(/[^-0-9]/g, '');
                tmp_arr['day'] = getDay(i);
                tmp_arr['group_id'] = sel2[j].parentNode.className.replace(/[^0-9]/g, '');
                tmp_arr['subject_id'] = sel2[j].options[sel2[j].selectedIndex].value;
                predmets.forEach(function (dat){
                    if((dat['group_id']==tmp_arr['group_id'])&&(dat['id']==tmp_arr['subject_id'])&&(dat['teacher']==teacher1[j*2].innerHTML)) {
                        if ((teacher1[j*2].innerHTML!='')&&(teacher2[j*2].innerHTML=='')) {
                            tmp_arr['teacher1_id'] = dat['teacher_id'];
                            tmp_arr['teacher2_id'] = null;
                        }
                        else if((teacher1[j*2].innerHTML!='')&&(teacher2[j*2].innerHTML!='')) {
                            tmp_arr['teacher1_id'] = dat['teacher_id'];
                            tmp_arr['teacher2_id'] = dat['teacher2_id'];
                        }
                        else {
                            tmp_arr['teacher1_id'] = null;
                            tmp_arr['teacher2_id'] = null;
                        }
                    }
                });

                tmp_arr['audience1_id']=null;
                tmp_arr['audience2_id']=null;

                arr_auditor.forEach(function (aud){
                    if((aud["number"]==audience1[j].value)&&(audience1[j].value!='')) tmp_arr['audience1_id'] = aud["id"];
                    if((aud["number"]==audience2[j].value)&&(audience2[j].value!='')) tmp_arr['audience2_id'] = aud["id"];
                });

                tmp_arr["type"] = 1;
                arr.push(tmp_arr);
            }
        }
    }

    var result = "";

    for (i=0; i<arr.length; i++) {
        result += " (";
        result += arr[i]["year"]+", ";
        result += "'"+arr[i]["semester"]+"', ";
        result += "'"+arr[i]["para"]+"', ";
        result += arr[i]["day"]+", ";
        result += arr[i]["group_id"]+", ";
        result += arr[i]["subject_id"]+", ";
        result += arr[i]["teacher1_id"]+", ";
        result += arr[i]["teacher2_id"]+", ";
        result += arr[i]["audience1_id"]+", ";
        result += arr[i]["audience2_id"]+", ";
        result += arr[i]["type"]+"),";
    }


    result = result.slice(0,-1);


    $.ajax({
        type: 'post',
        url: 'save_data.php',
        data: 'arr='+result+'&year='+year+'&sem='+sem,
        dataType: 'html',
        success: function(results)
        {
            if(results == 'error'){ alert('Помилка при збереженні');
            }else{
                alert('Успішно збережено');
            }
        }
    });
    return arr;
}