<?php
    require_once "connect.php";
    class Query{
        private $db;
        public function openConnect(){
            $userName = "root";
            $password = "svenacer1ABC";
            $dbName = "khpk";
            $this->db = new Connect($userName, $password, $dbName);
        }

        public function selectQuery($selectionValues = array(), $table, $where = ""){
            $select = "";
            for($i = 0; $i<count($selectionValues); $i++){
                //$selectionValues[$i] = mysql_real_escape_string($selectionValues[$i]);
                //$selectionValues[$i] = htmlspecialchars($selectionValues[$i]);

                $select .= $selectionValues[$i] . (($i == count($selectionValues) - 1) ? "" : ", ");
            }

      //echo "<br/>"."SELECT ".$select." FROM ".$table." ".(($where == "") ? "" : " WHERE ".$where)."<br/>";

            $temp = mysql_query("SELECT ".$select." FROM ".$table." ".(($where == "") ? "" : " WHERE ".$where)) or die(mysql_error());
            return $temp;
        }

        public function selectLoad($id)
        {
            $select = "SELECT `l`.`id`, `l`.`teacher_id`, `t`.`last_name`, `t`.`first_name`, `t`.`middle_name`, `l`.`group_id`, `g`.`title` AS `group`, `wp`.`subject_id`, `sb`.`title` AS `subject`, `wp`.`lectures`, `wp`.`labs`, `wp`.`practs`, `wp`.`weeks`, `wp`.`dual_practice`, `wp`.`dual_labs`, `l`.`course`, `pl`.`semesters`, `l`.`type` FROM  `wp_plan`  `pl`
            INNER JOIN (`subject` `sb` INNER JOIN (`wp_subject` `wp` INNER JOIN (`group` `g` INNER JOIN (`load` `l` INNER JOIN `employee` `t` ON `l`.`teacher_id` = `t`.`id`) ON `g`.`id` = `l`.`group_id`) ON `wp`.`id` = `l`.`wp_subject_id`) ON `sb`.`id` = `wp`.`subject_id`) ON `pl`.`id` = `wp`.`plan_id`
            WHERE  `l`.`study_year_id` = ".$id." ORDER BY `l`.`course`, `g`.`title`, `t`.`last_name` ASC";
            $temp = mysql_query($select);
            return $temp;
        }

        public function selectTimetable($id, $semester, $day=""){
            if($day=="")
            $select = "SELECT `tim`.`id`, `tim`.`study_year_id`, `tim`.`semester`, `tim`.`para`, `tim`.`day`, `tim`.`group_id`, `tim`.`subject_id`, `sb`.`title` AS `subject`, `tim`.`teacher1_id`, `tim`.`teacher2_id`, `tim`.`audience1_id`, `tim`.`audience2_id`, `tim`.`type`
            FROM  `timetable` `tim`  INNER JOIN `subject` `sb` ON `tim`.`subject_id` = `sb`.`id`
            WHERE  `tim`.`study_year_id` = ".$id." AND `tim`.`semester`='".$semester."' ORDER BY `tim`.`para`, `tim`.`type` ASC";
            else
            $select = "SELECT `tim`.`id`, `tim`.`study_year_id`, `tim`.`semester`, `tim`.`para`, `tim`.`day`, `tim`.`group_id`, `tim`.`subject_id`, `sb`.`title` AS `subject`, `tim`.`teacher1_id`, `tim`.`teacher2_id`, `tim`.`audience1_id`, `tim`.`audience2_id`, `tim`.`type`
            FROM  `timetable` `tim`  INNER JOIN `subject` `sb` ON `tim`.`subject_id` = `sb`.`id`
            WHERE  `tim`.`study_year_id` = ".$id." AND `tim`.`semester`='".$semester."' AND `tim`.`day`=".$day." ORDER BY `tim`.`para`, `tim`.`type` ASC";
            $temp = mysql_query($select);
            return $temp;
        }

        public function selectActualShedule($date, $day){
            $select = "SELECT `sh`.`id`, `sh`.`date`, `sh`.`para`, `sh`.`day`, `sh`.`group_id`, `sh`.`subject_id`, `sb`.`title` AS `subject`, `sh`.`teacher1_id`, `sh`.`teacher2_id`, `sh`.`audience1_id`, `sh`.`audience2_id`, `sh`.`type`, `sh`.`type_lesson`
            FROM  `actual_shedule` `sh`  INNER JOIN `subject` `sb` ON `sh`.`subject_id` = `sb`.`id`
            WHERE  `sh`.`date` = '".$date."' AND `sh`.`day`=".$day." ORDER BY `sh`.`para`, `sh`.`type` ASC";
            $temp = mysql_query($select);
            return $temp;
        }

        public function insertQuery($insertValues = array(), $table){
            $insert = "";
            for($i = 0; $i<count($insertValues); $i++){
                //$insertValues[$i] = mysql_real_escape_string($insertValues[$i]);
                //$insertValues[$i] = htmlspecialchars($insertValues[$i]);

                $insert .= "'".$insertValues[$i] . (($i == count($insertValues) - 1) ? "'" : "', ");
            }

            $temp = mysql_query("INSERT INTO ".$table." VALUES(".$insert.")") or die(mysql_error());
            return true;
        }

        public function insertTimetable($insertValues, $table, $year, $semester){
            mysql_query("DELETE FROM ".$table." WHERE `study_year_id`=".$year." AND `semester`='".$semester."'");
            $temp = mysql_query("INSERT INTO ".$table." (`study_year_id`, `semester`, `para`, `day`, `group_id`, `subject_id`, `teacher1_id`, `teacher2_id`, `audience1_id`, `audience2_id`, `type`) VALUES ".$insertValues);
            return $temp;
        }

        public function insertActualSchedule($insertValues, $table, $date){
            mysql_query("DELETE FROM ".$table." WHERE `date`='".$date."'");
            $temp = mysql_query("INSERT INTO ".$table." (`date`, `para`, `day`, `group_id`, `subject_id`, `teacher1_id`, `teacher2_id`, `audience1_id`, `audience2_id`, `type`, `type_lesson`) VALUES ".$insertValues);
            return $temp;
        }

        public function updateQuery($fields = array(), $data = array(),$table, $where){
            $update = "";
            for($i = 0; $i<count($fields); $i++){
                //$fields[$i] = mysql_real_escape_string($fields[$i]);
                //$fields[$i] = htmlspecialchars($fields[$i]);

                //$data[$i] = mysql_real_escape_string($data[$i]);
                //$data[$i] = htmlspecialchars($data[$i]);

                $update .=  $fields[$i] . " = '" . $data[$i] . (($i == count($fields) - 1) ? "'" : "', ");
            }

            $temp = mysql_query("UPDATE ".$table." SET ".$update." WHERE ".$where, $this->$db)or die(mysql_error());
            return true;
        }

        public function deleteQuery($data = array(), $table, $where){
            $delete = "";
            for($i = 0; $i<count($data); $i++){
                //$data[$i] = mysql_real_escape_string($data[$i]);
                //$data[$i] = htmlspecialchars($data[$i]);

                $delete .=  $data[$i] . (($i == count($data) - 1) ? "" : ", ");
            }

            $temp =  mysql_query("DELETE ".$delete." FROM ".$table." WHERE ".$where)or die(mysql_error());
            return true;
        }
    }
?>