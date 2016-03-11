<?php
require_once "queries.php";

class GlobalFunction{

    public $groups;
    public $data;

    public function __construct($id)
    {
        $select = new Query();
        $select->openConnect();
        $q = $select->selectLoad($id);
        while ($us = mysql_fetch_array($q)) {
            $this->data[] = $us;
        }
        mysql_close();
    }

    public function createGroups($season){
        $arr = array();

        foreach($this->data as $value){
            $tmp = explode('|', $value['weeks']);
            if ($season==0) {
                if ($value['course'] == 1 && $tmp[0] != '') $arr[1][] = $value['group'];
                if ($value['course'] == 2 && $tmp[2] != '') $arr[2][] = $value['group'];
                if ($value['course'] == 3 && $tmp[4] != '') $arr[3][] = $value['group'];
                if ($value['course'] == 4 && $tmp[6] != '') $arr[4][] = $value['group'];
            } else {
                if ($value['course'] == 1 && $tmp[1] != '') $arr[1][] = $value['group'];
                if ($value['course'] == 2 && $tmp[3] != '') $arr[2][] = $value['group'];
                if ($value['course'] == 3 && $tmp[5] != '') $arr[3][] = $value['group'];
                if ($value['course'] == 4 && $tmp[7] != '') $arr[4][] = $value['group'];
            }
        }

        if(isset($arr[1])){ $arr[1] = array_unique($arr[1]); sort($arr[1]);}
        if(isset($arr[2])){ $arr[2] = array_unique($arr[2]); sort($arr[2]);}
        if(isset($arr[3])){ $arr[3] = array_unique($arr[3]); sort($arr[3]);}
        if(isset($arr[4])){ $arr[4] = array_unique($arr[4]); sort($arr[4]);}

        $c=0;
        foreach ($arr as $tmp) {
            foreach ($tmp as $i)
                foreach($this->data as $value) {
                    if ($value['group'] == $i) { $t['id'] = $value['group_id']; $t['title']=$i; $t['course'] = $value['course']; $this->groups[$c][]=$t; break;}
                }
            $c++;
        }

        return $this->groups;
    }

    public function createSubject($season){
        $subjects = array();
        foreach ($this->data as $value){
            $tmp = explode('|', $value['weeks']);
            $t = false;
            if ($season==0) {
                for ($i=0; $i<8; $i = $i+2)
                    if($tmp[$i]!='') {$t=true; break;}
            } else {
                for ($i=1; $i<8; $i = $i+2)
                    if ($tmp[$i] != '') {$t = true; break;}
            }

            if ($t==false) continue;

            if (($value['dual_labs']==1)||($value['dual_practs']==1)) $rozdv = true;
            else $rozdv=false;

            $semesters=array();
            $hour_lectures=array();
            $hour_dual=array();
            $tmp_arr=array();



            if ($rozdv) {
                $hour_lectures = explode('|', $value['lectures']);
                if ($value['dual_labs'] == 1) $hour_dual = explode('|', $value['labs']);
                else $hour_dual = explode('|', $value['practs']);
                $semesters = explode('|', $value['semesters']);
            }
            $weeks = explode('|', $value['weeks']);
            if ($season==0) {
                if ($value['course'] == 1 && $weeks[0] != ''){
                    $tmp_arr['id']=$value['subject_id'];
                    $tmp_arr['subject_title']=$value['subject'];
                    $tmp_arr['group_id']=$value['group_id'];
                    $tmp_arr['group']=$value['group'];
                    if ($rozdv) {
                        if($value['type']==1){
                            $tmp_arr['teacher_id'] = $value['teacher_id'];
                            $tmp_arr['teacher'] = $value['last_name'].' '.$value['first_name'].' '.$value['middle_name'];
                            foreach ($this->data as $tmp) if(($tmp['subject_id']==$value['subject_id'])&&($tmp['course']==$value['course'])&&($tmp['group_id']==$value['group_id'])&&($tmp['type']!=1)){
                                $tmp_arr['teacher2_id'] = $tmp['teacher_id'];
                                $tmp_arr['teacher2'] = $tmp['last_name'].' '.$tmp['first_name'].' '.$tmp['middle_name'];
                                break;
                            }
                        }
                        else {
                            continue;
                        }
                        $count_first = round($hour_lectures[0] / $semesters[0]) / 2;
                        $count_dual = round($hour_dual[0] / $semesters[0]) / 2;
                        $tmp_arr['dual']=1;
                    } else {$count_first = $weeks[0]/2; $count_dual=0; $tmp_arr['teacher_id'] = $value['teacher_id']; $tmp_arr['teacher'] = $value['last_name'].' '.$value['first_name'].' '.$value['middle_name']; $tmp_arr['dual']=0;}
                    $tmp_arr['count_first']=$count_first;
                    $tmp_arr['count_dual']=$count_dual;
                    $subjects[] = $tmp_arr;
                }

                if ($value['course'] == 2 && $weeks[2] != '') {
                    $tmp_arr['id']=$value['subject_id'];
                    $tmp_arr['subject_title']=$value['subject'];
                    $tmp_arr['group_id']=$value['group_id'];
                    $tmp_arr['group']=$value['group'];
                    if ($rozdv) {
                        if($value['type']==1){
                            $tmp_arr['teacher_id'] = $value['teacher_id'];
                            $tmp_arr['teacher'] = $value['last_name'].' '.$value['first_name'].' '.$value['middle_name'];
                            foreach ($this->data as $tmp) if(($tmp['subject_id']==$value['subject_id'])&&($tmp['course']==$value['course'])&&($tmp['group_id']==$value['group_id'])&&($tmp['type']!=1)){
                                $tmp_arr['teacher2_id'] = $tmp['teacher_id'];
                                $tmp_arr['teacher2'] = $tmp['last_name'].' '.$tmp['first_name'].' '.$tmp['middle_name'];
                                break;
                            }
                        }
                        else {
                            continue;
                        }
                        $count_first = round($hour_lectures[2] / $semesters[2]) / 2;
                        $count_dual = round($hour_dual[2] / $semesters[2]) / 2;
                        $tmp_arr['dual']=1;
                    } else {$count_first = $weeks[2]/2; $count_dual=0; $tmp_arr['teacher_id'] = $value['teacher_id']; $tmp_arr['teacher'] = $value['last_name'].' '.$value['first_name'].' '.$value['middle_name']; $tmp_arr['dual']=0;}
                    $tmp_arr['count_first']=$count_first;
                    $tmp_arr['count_dual']=$count_dual;
                    $subjects[] = $tmp_arr;
                }

                if ($value['course'] == 3 && $weeks[4] != ''){
                    $tmp_arr['id']=$value['subject_id'];
                    $tmp_arr['subject_title']=$value['subject'];
                    $tmp_arr['group_id']=$value['group_id'];
                    $tmp_arr['group']=$value['group'];
                    if ($rozdv) {
                        if($value['type']==1){
                            $tmp_arr['teacher_id'] = $value['teacher_id'];
                            $tmp_arr['teacher'] = $value['last_name'].' '.$value['first_name'].' '.$value['middle_name'];
                            foreach ($this->data as $tmp) if(($tmp['subject_id']==$value['subject_id'])&&($tmp['course']==$value['course'])&&($tmp['group_id']==$value['group_id'])&&($tmp['type']!=1)){
                                $tmp_arr['teacher2_id'] = $tmp['teacher_id'];
                                $tmp_arr['teacher2'] = $tmp['last_name'].' '.$tmp['first_name'].' '.$tmp['middle_name'];
                                break;
                            }
                        }
                        else {
                            continue;
                        }
                        $count_first = round($hour_lectures[4] / $semesters[4]) / 2;
                        $count_dual = round($hour_dual[4] / $semesters[4]) / 2;
                        $tmp_arr['dual']=1;
                    } else {$count_first = $weeks[4]/2; $count_dual=0; $tmp_arr['teacher_id'] = $value['teacher_id']; $tmp_arr['teacher'] = $value['last_name'].' '.$value['first_name'].' '.$value['middle_name']; $tmp_arr['dual']=0;}
                    $tmp_arr['count_first']=$count_first;
                    $tmp_arr['count_dual']=$count_dual;
                    $subjects[] = $tmp_arr;
                }

                if ($value['course'] == 4 && $weeks[6] != '') {
                    $tmp_arr['id']=$value['subject_id'];
                    $tmp_arr['subject_title']=$value['subject'];
                    $tmp_arr['group_id']=$value['group_id'];
                    $tmp_arr['group']=$value['group'];
                    if ($rozdv) {
                        if($value['type']==1){
                            $tmp_arr['teacher_id'] = $value['teacher_id'];
                            $tmp_arr['teacher'] = $value['last_name'].' '.$value['first_name'].' '.$value['middle_name'];
                            foreach ($this->data as $tmp) if(($tmp['subject_id']==$value['subject_id'])&&($tmp['course']==$value['course'])&&($tmp['group_id']==$value['group_id'])&&($tmp['type']!=1)){
                                $tmp_arr['teacher2_id'] = $tmp['teacher_id'];
                                $tmp_arr['teacher2'] = $tmp['last_name'].' '.$tmp['first_name'].' '.$tmp['middle_name'];
                                break;
                            }
                        }
                        else {
                            continue;
                        }
                        $count_first = round($hour_lectures[6] / $semesters[6]) / 2;
                        $count_dual = round($hour_dual[6] / $semesters[6]) / 2;
                        $tmp_arr['dual']=1;
                    } else {$count_first = $weeks[6]/2; $count_dual=0; $tmp_arr['teacher_id'] = $value['teacher_id']; $tmp_arr['teacher'] = $value['last_name'].' '.$value['first_name'].' '.$value['middle_name']; $tmp_arr['dual']=0;}
                    $tmp_arr['count_first']=$count_first;
                    $tmp_arr['count_dual']=$count_dual;
                    $subjects[] = $tmp_arr;
                }
            } else {
                if ($value['course'] == 1 && $weeks[1] != '') {
                    $tmp_arr['id']=$value['subject_id'];
                    $tmp_arr['subject_title']=$value['subject'];
                    $tmp_arr['group_id']=$value['group_id'];
                    $tmp_arr['group']=$value['group'];
                    if ($rozdv) {
                        if($value['type']==1){
                            $tmp_arr['teacher_id'] = $value['teacher_id'];
                            $tmp_arr['teacher'] = $value['last_name'].' '.$value['first_name'].' '.$value['middle_name'];
                            foreach ($this->data as $tmp) if(($tmp['subject_id']==$value['subject_id'])&&($tmp['course']==$value['course'])&&($tmp['group_id']==$value['group_id'])&&($tmp['type']!=1)){
                                $tmp_arr['teacher2_id'] = $tmp['teacher_id'];
                                $tmp_arr['teacher2'] = $tmp['last_name'].' '.$tmp['first_name'].' '.$tmp['middle_name'];
                                break;
                            }
                        }
                        else {
                            continue;
                        }
                        $count_first = round($hour_lectures[1] / $semesters[1]) / 2;
                        $count_dual = round($hour_dual[1] / $semesters[1]) / 2;
                        $tmp_arr['dual']=1;
                    } else {$count_first = $weeks[1]/2; $count_dual=0; $tmp_arr['teacher_id'] = $value['teacher_id']; $tmp_arr['teacher'] = $value['last_name'].' '.$value['first_name'].' '.$value['middle_name']; $tmp_arr['dual']=0;}
                    $tmp_arr['count_first']=$count_first;
                    $tmp_arr['count_dual']=$count_dual;
                    $subjects[] = $tmp_arr;
                }
                if ($value['course'] == 2 && $weeks[3] != '') {
                    $tmp_arr['id']=$value['subject_id'];
                    $tmp_arr['subject_title']=$value['subject'];
                    $tmp_arr['group_id']=$value['group_id'];
                    $tmp_arr['group']=$value['group'];
                    if ($rozdv) {
                        if($value['type']==1){
                            $tmp_arr['teacher_id'] = $value['teacher_id'];
                            $tmp_arr['teacher'] = $value['last_name'].' '.$value['first_name'].' '.$value['middle_name'];
                            foreach ($this->data as $tmp) if(($tmp['subject_id']==$value['subject_id'])&&($tmp['course']==$value['course'])&&($tmp['group_id']==$value['group_id'])&&($tmp['type']!=1)){
                                $tmp_arr['teacher2_id'] = $tmp['teacher_id'];
                                $tmp_arr['teacher2'] = $tmp['last_name'].' '.$tmp['first_name'].' '.$tmp['middle_name'];
                                break;
                            }
                        }
                        else {
                            continue;
                        }
                        $count_first = round($hour_lectures[3] / $semesters[3]) / 2;
                        $count_dual = round($hour_dual[3] / $semesters[3]) / 2;
                        $tmp_arr['dual']=1;
                    } else {$count_first = $weeks[3]/2; $count_dual=0; $tmp_arr['teacher_id'] = $value['teacher_id']; $tmp_arr['teacher'] = $value['last_name'].' '.$value['first_name'].' '.$value['middle_name']; $tmp_arr['dual']=0;}
                    $tmp_arr['count_first']=$count_first;
                    $tmp_arr['count_dual']=$count_dual;
                    $subjects[] = $tmp_arr;
                }
                if ($value['course'] == 3 && $weeks[5] != '') {
                    $tmp_arr['id']=$value['subject_id'];
                    $tmp_arr['subject_title']=$value['subject'];
                    $tmp_arr['group_id']=$value['group_id'];
                    $tmp_arr['group']=$value['group'];
                    if ($rozdv) {
                        if($value['type']==1){
                            $tmp_arr['teacher_id'] = $value['teacher_id'];
                            $tmp_arr['teacher'] = $value['last_name'].' '.$value['first_name'].' '.$value['middle_name'];
                            foreach ($this->data as $tmp) if(($tmp['subject_id']==$value['subject_id'])&&($tmp['course']==$value['course'])&&($tmp['group_id']==$value['group_id'])&&($tmp['type']!=1)){
                                $tmp_arr['teacher2_id'] = $tmp['teacher_id'];
                                $tmp_arr['teacher2'] = $tmp['last_name'].' '.$tmp['first_name'].' '.$tmp['middle_name'];
                                break;
                            }
                        }
                        else {
                            continue;
                        }
                        $count_first = round($hour_lectures[5] / $semesters[5]) / 2;
                        $count_dual = round($hour_dual[5] / $semesters[5]) / 2;
                        $tmp_arr['dual']=1;
                    } else {$count_first = $weeks[5]/2; $count_dual=0; $tmp_arr['teacher_id'] = $value['teacher_id']; $tmp_arr['teacher'] = $value['last_name'].' '.$value['first_name'].' '.$value['middle_name']; $tmp_arr['dual']=0;}
                    $tmp_arr['count_first']=$count_first;
                    $tmp_arr['count_dual']=$count_dual;
                    $subjects[] = $tmp_arr;

                }
                if ($value['course'] == 4 && $weeks[7] != ''){
                    $tmp_arr['id']=$value['subject_id'];
                    $tmp_arr['subject_title']=$value['subject'];
                    $tmp_arr['group_id']=$value['group_id'];
                    $tmp_arr['group']=$value['group'];
                    if ($rozdv) {
                        if($value['type']==1){
                            $tmp_arr['teacher_id'] = $value['teacher_id'];
                            $tmp_arr['teacher'] = $value['last_name'].' '.$value['first_name'].' '.$value['middle_name'];
                            foreach ($this->data as $tmp) if(($tmp['subject_id']==$value['subject_id'])&&($tmp['course']==$value['course'])&&($tmp['group_id']==$value['group_id'])&&($tmp['type']!=1)){
                                $tmp_arr['teacher2_id'] = $tmp['teacher_id'];
                                $tmp_arr['teacher2'] = $tmp['last_name'].' '.$tmp['first_name'].' '.$tmp['middle_name'];
                                break;
                            }
                        }
                        else {
                            continue;
                        }
                        $count_first = round($hour_lectures[7] / $semesters[7]) / 2;
                        $count_dual = round($hour_dual[7] / $semesters[7]) / 2;
                        $tmp_arr['dual']=1;
                    } else {$count_first = $weeks[7]/2; $count_dual=0; $tmp_arr['teacher_id'] = $value['teacher_id']; $tmp_arr['teacher'] = $value['last_name'].' '.$value['first_name'].' '.$value['middle_name']; $tmp_arr['dual']=0;}
                    $tmp_arr['count_first']=$count_first;
                    $tmp_arr['count_dual']=$count_dual;
                    $subjects[] = $tmp_arr;
                }
            }
        }
        return $subjects;
    }

    function getAudience(){
        $select = new Query();
        $select->openConnect();
        $q = $select->selectQuery(array("number"), "audience");
        $audience='';
        while($us = mysql_fetch_array($q)){
            $audience=$audience." ".$us['number'];
        }
        return $audience;
    }

    function getFullAudiences(){
        $select = new Query();
        $select->openConnect();
        $q = $select->selectQuery(array("id","number"), "audience");
        $arr = array();
        while($us = mysql_fetch_array($q)){
            $tmp = array();
            $tmp["id"] = $us["id"];
            $tmp["number"] = $us["number"];
            $arr[] = $tmp;
        }

        for ($i=0; $i<count($arr)-1; $i++) {
            for ($j = 0; $j<count($arr)-$i-1; $j++){
                if($arr[$j]['number']>$arr[$j+1]['number']){
                    $tmp = $arr[$j];
                    $arr[$j] = $arr[$j+1];
                    $arr[$j+1] = $tmp;
                }
            }
        }

        return $arr;
    }

    function getTimetable($year, $semester, $day=""){
        $select = new Query();
        $select->openConnect();
        $arr = array();
        $arr_teacher = array();
        $arr_audience = array();
        $q = $select->selectQuery(array("id","last_name","first_name","middle_name"), "employee");
        while ($us = mysql_fetch_array($q)) {
            $tmp=array();
            $tmp["id"] = $us["id"];
            $tmp["last_name"] = $us["last_name"];
            $tmp["first_name"] = $us["first_name"];
            $tmp["middle_name"] = $us["middle_name"];
            $arr_teacher[] = $tmp;
        }

        $q = $select->selectQuery(array("id","number"), "audience");
        while ($us = mysql_fetch_array($q)) {
            $tmp = array();
            $tmp["id"] = $us["id"];
            $tmp["number"] = $us["number"];
            $arr_audience[] = $tmp;
        }

        $q = $select->selectTimetable($year,$semester,$day);
        while ($us = mysql_fetch_array($q)){

            $tmp = array();
            $tmp["id"] = $us["id"];
            $tmp["study_year_id"] = $us["study_year_id"];
            $tmp["semester"] = $us["semester"];
            $tmp["para"] = $us["para"];
            $tmp["day"] = $us["day"];
            $tmp["group_id"] = $us["group_id"];
            $tmp["subject_id"] = $us["subject_id"];
            $tmp["subject"] = $us["subject"];
            $tmp["teacher1_id"] = $us["teacher1_id"]; $tmp["teacher1_name"] = '';
            $tmp["teacher2_id"] = $us["teacher2_id"]; $tmp["teacher2_name"] = '';
            foreach($arr_teacher as $v){
                if (($v["id"]==$tmp["teacher1_id"])&&($tmp["teacher1_id"]!='')) $tmp["teacher1_name"] = $v["last_name"]." ".$v["first_name"]." ".$v["middle_name"];
                if (($v["id"]==$tmp["teacher2_id"])&&($tmp["teacher2_id"]!='')) $tmp["teacher2_name"] = $v["last_name"]." ".$v["first_name"]." ".$v["middle_name"];
            }
            $tmp["audience1_id"] = $us["audience1_id"]; $tmp["audience1_number"] = '';
            $tmp["audience2_id"] = $us["audience2_id"]; $tmp["audience2_number"] = '';
            foreach($arr_audience as $v){
                if (($v["id"]==$tmp["audience1_id"])&&($tmp["audience1_id"]!='')) $tmp["audience1_number"] = $v["number"];
                if (($v["id"]==$tmp["audience2_id"])&&($tmp["audience2_id"]!='')) $tmp["audience2_number"] = $v["number"];
            }
            $tmp["type"] = $us["type"];
            $arr[] = $tmp;
        }

        return $arr;
    }

    function getTeachers($load){
        $teachers = array();
        foreach($load as $tmp){
            $teachers[$tmp['teacher_id']] = $tmp['teacher'];
            if($tmp['count_dual']!=0) $teachers[$tmp['teacher2_id']] = $tmp['teacher2'];
        }
        asort($teachers);
        return $teachers;

    }

    function getActualSchedule($date, $day){
        $select = new Query();
        $select->openConnect();
        $arr = array();
        $arr_teacher = array();
        $arr_audience = array();
        $q = $select->selectQuery(array("id","last_name","first_name","middle_name"), "employee");
        while ($us = mysql_fetch_array($q)) {
            $tmp=array();
            $tmp["id"] = $us["id"];
            $tmp["last_name"] = $us["last_name"];
            $tmp["first_name"] = $us["first_name"];
            $tmp["middle_name"] = $us["middle_name"];
            $arr_teacher[] = $tmp;
        }

        $q = $select->selectQuery(array("id","number"), "audience");
        while ($us = mysql_fetch_array($q)) {
            $tmp = array();
            $tmp["id"] = $us["id"];
            $tmp["number"] = $us["number"];
            $arr_audience[] = $tmp;
        }

        $arr_date = explode(".", $date);
        $q = $select->selectActualShedule($arr_date[2]."-".$arr_date[1]."-".$arr_date[0], $day);
        while ($us = mysql_fetch_array($q)){

            $tmp = array();
            $tmp["id"] = $us["id"];
            $tmp["para"] = $us["para"];
            $tmp["day"] = $us["day"];
            $tmp["group_id"] = $us["group_id"];
            $tmp["subject_id"] = $us["subject_id"];
            $tmp["subject"] = $us["subject"];
            $tmp["teacher1_id"] = $us["teacher1_id"]; $tmp["teacher1_name"] = '';
            $tmp["teacher2_id"] = $us["teacher2_id"]; $tmp["teacher2_name"] = '';
            foreach($arr_teacher as $v){
                if (($v["id"]==$tmp["teacher1_id"])&&($tmp["teacher1_id"]!='')) $tmp["teacher1_name"] = $v["last_name"]." ".$v["first_name"]." ".$v["middle_name"];
                if (($v["id"]==$tmp["teacher2_id"])&&($tmp["teacher2_id"]!='')) $tmp["teacher2_name"] = $v["last_name"]." ".$v["first_name"]." ".$v["middle_name"];
            }
            $tmp["audience1_id"] = $us["audience1_id"]; $tmp["audience1_number"] = '';
            $tmp["audience2_id"] = $us["audience2_id"]; $tmp["audience2_number"] = '';
            foreach($arr_audience as $v){
                if (($v["id"]==$tmp["audience1_id"])&&($tmp["audience1_id"]!='')) $tmp["audience1_number"] = $v["number"];
                if (($v["id"]==$tmp["audience2_id"])&&($tmp["audience2_id"]!='')) $tmp["audience2_number"] = $v["number"];
            }
            $tmp["type"] = $us["type"];
            $tmp["type_lesson"] = $us["type_lesson"];
            $arr[] = $tmp;
        }
        return $arr;
    }
}
?>