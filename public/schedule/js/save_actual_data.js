/**
 * Created by kostia on 23.06.15.
 */
function save_actual_schedule(){
    var count_radio = checkRadio();
    var count_error_auditors = checkAudience();
    console.log(count_radio);
    console.log(count_error_auditors);
    if ((count_radio!=0)||(count_error_auditors!=0)){
        var text='';
        if((count_radio!=0)&&(count_error_auditors==0)) text = "Увага! Ще є "+count_radio+" пар в яких не вибрано тип! Продовжити збереження?";
        else if((count_radio==0)&&(count_error_auditors!=0)) text = "Увага! Є "+count_error_auditors+" спірних або не заповнених аудиторій! Продовжити збереження?";
        else text = "Увага! Ще є "+count_radio+" пар в яких не вибрано тип та "+count_error_auditors+" спірних або не заповнених аудиторій! Продовжити збереження?";
        if(confirm(text)){
            createArrayActualSchedule();
        }
        else {
            alert('Збереження було відмінено!');
        }
    }
    else {
        createArrayActualSchedule();
    }
}

function checkAudience(){
    var count=0;
    var table =  document.getElementsByClassName("rozklad")[0];
    for (var i=0; i<table.getElementsByTagName('input').length; i++)
        if(table.getElementsByTagName('input')[i].style.backgroundColor=="rgb(231, 96, 96)") count++;
    return count;
}

function checkRadio(){
    var count=0;
    var table =  document.getElementsByClassName("rozklad")[0];
    var sel = table.getElementsByClassName('row:0')[0].getElementsByTagName('select');
    var group_id = '';
    for (var i=0; i<sel.length; i++){
        group_id = sel[i].parentNode.className.replace(/[^0-9]/g, '');
        for (var j=0; j<=3; j++){
            var tmp_sel = table.getElementsByClassName('row:'+j)[0].getElementsByClassName('id_group:'+group_id)[0].getElementsByTagName('select')[0];
            if ((tmp_sel.selectedIndex!=0)||(tmp_sel.options.length>1)){
            var radio = document.getElementsByName('radio_'+j+':'+group_id);
            if((!radio[0].checked)&&(!radio[1].checked)&&(!radio[2].checked)) count++;
            }
        }
    }
    return count;
}

function createArrayActualSchedule(){
    var table =  document.getElementsByClassName("rozklad")[0];
    var arr = [];
    for(var i=0; i<4; i++){
        for (var j=0; j<table.getElementsByClassName("row:"+i)[0].getElementsByTagName("select").length; j++) {
            var tmp_arr=[];
            var sel = table.getElementsByClassName("row:"+i)[0].getElementsByTagName("select");
            var teacher1;
            var teacher2;
            var audience1;
            var audience2;

            if (sel[j].options[sel[j].selectedIndex].value!=-1) {
                tmp_arr=[];
                teacher1 = table.getElementsByClassName("row:"+i)[1].getElementsByTagName("td");
                teacher2 = table.getElementsByClassName("row:"+i)[2].getElementsByTagName("td");
                audience1 = table.getElementsByClassName("row:"+i)[1].getElementsByTagName("input");
                audience2 = table.getElementsByClassName("row:"+i)[2].getElementsByTagName("input");

                var arr_date = actual_date.split('.');
                tmp_arr['date'] = arr_date[2]+'-'+arr_date[1]+'-'+arr_date[0];
                tmp_arr['para'] = sel[j].id.replace(/[^-0-9]/g, '');
                tmp_arr['day'] = actual_day;
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

                tmp_arr["type"] = actual_type;

                var type_lesson = null;
                var radio = document.getElementsByName('radio_'+i+':'+tmp_arr['group_id']);
                if (radio[0].checked) type_lesson=0;
                if (radio[1].checked) type_lesson=1;
                if (radio[2].checked) type_lesson=2;
                tmp_arr["type_lesson"] = type_lesson;
                arr.push(tmp_arr);
            }
        }
    }

    var result = "";

    for (i=0; i<arr.length; i++) {
        result += " (";
        result += "'"+arr[i]["date"]+"', ";
        result += "'"+arr[i]["para"]+"', ";
        result += "'"+arr[i]["day"]+"', ";
        result += arr[i]["group_id"]+", ";
        result += arr[i]["subject_id"]+", ";
        result += arr[i]["teacher1_id"]+", ";
        result += arr[i]["teacher2_id"]+", ";
        result += arr[i]["audience1_id"]+", ";
        result += arr[i]["audience2_id"]+", ";
        result += arr[i]["type"]+", ";
        result += arr[i]["type_lesson"]+"),";
    }


    result = result.slice(0,-1);

    console.log(result);

    $.ajax({
        type: 'post',
        url: 'actual_save_data.php',
        data: 'arr='+result+'&date='+arr_date[2]+'-'+arr_date[1]+'-'+arr_date[0],
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